<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\Service;
use App\Models\Project;
use App\Models\BlogPost;
use App\Models\Setting;
use App\Models\Keyword;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML (compressed) and HTML sitemaps with full image and SEO data.';

    protected $urls = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting professional sitemap generation...');
        $start = microtime(true);

        // Paths
        $tempPath = storage_path('app/sitemap.xml.tmp');
        $finalPath = public_path('sitemap.xml');
        $finalGzPath = public_path('sitemap.xml.gz');
        $htmlPath = public_path('sitemap.html');

        try {
            // Reset URLs
            $this->urls = [];

            // 1. Add Static Pages
            $this->addStaticPages();

            // 2. Add Dynamic Content
            $this->addDynamicContent();

            // 3. Generate XML Content
            $xmlContent = $this->generateXml();

            // 4. Write to File
            File::put($tempPath, $xmlContent);

            // Move to public (Atomic operation)
            if (File::exists($tempPath)) {
                if (File::exists($finalPath)) File::delete($finalPath);
                File::move($tempPath, $finalPath);
            }

            // 5. Generate Gzip Version
            $this->createGzip($finalPath, $finalGzPath);

            // 6. Generate HTML Sitemap
            $this->generateHtmlSitemap($htmlPath);

            $duration = round(microtime(true) - $start, 2);
            $this->info("Sitemap generated successfully at {$finalPath}");
            $this->info("Gzipped Sitemap generated at {$finalGzPath}");
            $this->info("HTML Sitemap generated at {$htmlPath}");
            $this->info("Duration: {$duration}s");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Sitemap generation failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    protected function addUrl($loc, $lastmod, $priority, $freq, $images = [], $title = null, $description = null)
    {
        $this->urls[] = [
            'loc' => $loc,
            'lastmod' => $lastmod,
            'priority' => $priority,
            'changefreq' => $freq,
            'images' => $images,
            'title' => $title,
            'description' => $description,
        ];
    }

    protected function generateXml()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;

        foreach ($this->urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;

            if ($url['lastmod']) {
                $date = $url['lastmod'] instanceof Carbon ? $url['lastmod']->toIso8601String() : date('c', strtotime($url['lastmod']));
                $xml .= '    <lastmod>' . $date . '</lastmod>' . PHP_EOL;
            }

            if ($url['changefreq']) {
                $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            }

            if ($url['priority']) {
                $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            }

            // Images
            foreach ($url['images'] as $img) {
                $xml .= '    <image:image>' . PHP_EOL;
                $xml .= '      <image:loc>' . htmlspecialchars($img['url']) . '</image:loc>' . PHP_EOL;
                if (!empty($img['title'])) {
                    $xml .= '      <image:title>' . htmlspecialchars(\Illuminate\Support\Str::limit($img['title'], 250)) . '</image:title>' . PHP_EOL;
                }
                if (!empty($img['caption'])) {
                    $xml .= '      <image:caption>' . htmlspecialchars(\Illuminate\Support\Str::limit($img['caption'], 1000)) . '</image:caption>' . PHP_EOL;
                }
                $xml .= '    </image:image>' . PHP_EOL;
            }

            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';
        return $xml;
    }

    /**
     * Add static pages defined in the system.
     */
    protected function addStaticPages()
    {
        $this->info('Adding static pages...');

        // Fetch settings
        $settings = [];
        if (class_exists(Setting::class)) {
            $settings = Setting::pluck('value', 'key')->toArray();
        }

        $siteName = $settings['site_name'] ?? config('app.name');

        // Define routes and their configuration keys
        $routes = [
            'home' => [
                'route' => 'home',
                'active_key' => 'sitemap_home_active',
                'priority_key' => 'sitemap_home_priority',
                'freq_key' => 'sitemap_home_freq',
                'default_priority' => 1.0,
                'default_freq' => 'daily',
                'title' => $settings['site_name'] ?? config('app.name'), // Use site_name as home title usually
                'description' => $settings['site_description'] ?? '',
            ],
            'about' => [
                'route' => 'about',
                'active_key' => 'sitemap_about_active',
                'priority_key' => 'sitemap_about_priority',
                'freq_key' => 'sitemap_about_freq',
                'default_priority' => 0.8,
                'default_freq' => 'monthly',
                'title' => $settings['about_meta_title'] ?? ('من نحن | ' . $siteName),
                'description' => $settings['about_meta_description'] ?? 'تعرف على أفضل معلم دهانات وديكورات في جدة',
            ],
            'contact' => [
                'route' => 'contact',
                'active_key' => 'sitemap_contact_active',
                'priority_key' => 'sitemap_contact_priority',
                'freq_key' => 'sitemap_contact_freq',
                'default_priority' => 0.8,
                'default_freq' => 'monthly',
                'title' => $settings['contact_meta_title'] ?? ('اتصل بنا | ' . $siteName),
                'description' => $settings['contact_meta_description'] ?? '',
            ],
            'services.index' => [
                'route' => 'services.index',
                'active_key' => null,
                'default_priority' => 0.9,
                'default_freq' => 'weekly',
                'title' => $settings['services_meta_title'] ?? ('خدماتنا | ' . $siteName),
                'description' => $settings['services_meta_description'] ?? '',
            ],
            'projects.index' => [
                'route' => 'projects.index',
                'active_key' => null,
                'default_priority' => 0.9,
                'default_freq' => 'weekly',
                'title' => $settings['projects_meta_title'] ?? ('مشاريعنا | ' . $siteName),
                'description' => $settings['projects_meta_description'] ?? 'شاهد معرض أعمالنا ومشاريعنا السابقة',
            ],
            'blog.index' => [
                'route' => 'blog.index',
                'active_key' => null,
                'default_priority' => 0.9,
                'default_freq' => 'daily',
                'title' => $settings['blog_meta_title'] ?? ('المدونة | ' . $siteName),
                'description' => $settings['blog_meta_description'] ?? 'اقرأ أحدث المقالات والنصائح في عالم الدهانات والديكور',
            ],
        ];

        foreach ($routes as $key => $config) {
            if (!Route::has($config['route'])) {
                continue;
            }

            // Check if active
            $isActive = true;
            if (isset($config['active_key']) && $config['active_key']) {
                $isActive = isset($settings[$config['active_key']]) ? (bool)$settings[$config['active_key']] : true;
            }

            if (!$isActive) {
                $this->info("Skipping {$config['route']} (disabled in settings)");
                continue;
            }

            // Get Priority & Freq
            $priority = $config['default_priority'];
            if (isset($config['priority_key']) && isset($settings[$config['priority_key']])) {
                $priority = (float)$settings[$config['priority_key']];
            }

            $freq = $config['default_freq'];
            if (isset($config['freq_key']) && isset($settings[$config['freq_key']]) && !empty($settings[$config['freq_key']])) {
                $freq = $settings[$config['freq_key']];
            }

            $this->addUrl(
                route($config['route']),
                now(),
                $priority,
                $freq,
                [], // No images for static pages usually, or hardcoded
                $config['title'],
                $config['description']
            );
        }
    }

    /**
     * Add dynamic content (Services, Projects, Blog Posts).
     */
    protected function addDynamicContent()
    {
        // Services
        if (class_exists(Service::class) && Route::has('services.show')) {
            $this->info('Adding Services...');
            Service::where('active', true)->chunk(100, function ($services) {
                foreach ($services as $service) {
                    $this->addItemToSitemap($service, 'services.show', 0.8, 'weekly');
                }
            });
        }

        // Projects
        if (class_exists(Project::class) && Route::has('projects.show')) {
            $this->info('Adding Projects...');
            $query = Project::where('active', true);
            if (method_exists(Project::class, 'images')) {
                $query->with(['images' => function ($q) {
                    $q->where('active', true)->orderBy('sort_order');
                }]);
            }

            $query->chunk(100, function ($projects) {
                foreach ($projects as $project) {
                    $this->addItemToSitemap($project, 'projects.show', 0.8, 'weekly');
                }
            });
        }

        // Blog Posts
        if (class_exists(BlogPost::class) && Route::has('blog.show')) {
            $this->info('Adding Blog Posts...');
            BlogPost::where('active', true)
                ->whereDate('published_at', '<=', now())
                ->chunk(100, function ($posts) {
                    foreach ($posts as $post) {
                        $this->addItemToSitemap($post, 'blog.show', 0.7, 'daily');
                    }
                });
        }

        if (class_exists(Keyword::class) && Route::has('keywords.show')) {
            $this->info('Adding Keywords...');
            Keyword::where('active', true)
                ->withCount(['services', 'projects', 'blogPosts', 'seoPages'])
                ->chunk(200, function ($keywords) {
                    foreach ($keywords as $keyword) {
                        $usage = (int) ($keyword->services_count ?? 0)
                            + (int) ($keyword->projects_count ?? 0)
                            + (int) ($keyword->blog_posts_count ?? 0)
                            + (int) ($keyword->seo_pages_count ?? 0);
                        if ($usage < 2 && empty($keyword->description)) {
                            continue;
                        }
                        $this->addItemToSitemap($keyword, 'keywords.show', 0.4, 'weekly');
                    }
                });
        }
    }

    /**
     * Helper to add a single model item to the sitemap with images.
     */
    protected function addItemToSitemap(Model $model, string $routeName, float $priority, string $freq)
    {
        if (empty($model->slug)) return;

        $url = route($routeName, $model->slug);

        // Determine Last Modified
        $lastMod = $model->updated_at ?? $model->published_at ?? $model->created_at ?? now();

        // Title & Description
        $title = $model->meta_title ?? $model->title ?? $model->name ?? '';
        $description = $model->meta_description ?? $model->description ?? $model->content ?? '';

        // Process Images
        $images = $this->getImagesFromModel($model);
        $processedImages = [];

        foreach ($images as $imgData) {
            try {
                $imgUrl = $this->ensureAbsoluteUrl($imgData['url']);
                if ($imgUrl) {
                    $processedImages[] = [
                        'url' => $imgUrl,
                        'title' => strip_tags($imgData['title'] ?? $title),
                        'caption' => strip_tags($imgData['caption'] ?? $description),
                    ];
                }
            } catch (\Throwable $e) {
                // Ignore
            }
        }

        $this->addUrl(
            $url,
            $lastMod,
            $priority,
            $freq,
            $processedImages,
            strip_tags($title),
            strip_tags($description)
        );
    }

    /**
     * Extract images from a model (Main Image + Gallery).
     */
    protected function getImagesFromModel(Model $model): array
    {
        $images = [];

        // 1. Main Image
        $mainImage = $model->main_image ?? $model->image_path ?? $model->image ?? null;
        if ($mainImage) {
            $images[] = [
                'url' => $mainImage,
                'title' => $model->title ?? null,
                'caption' => $model->meta_description ?? $model->description ?? null,
            ];
        }

        // 2. Gallery Images (Relationship)
        if (method_exists($model, 'images')) {
            try {
                $gallery = $model->relationLoaded('images') ? $model->images : $model->images;
                foreach ($gallery as $img) {
                    $path = $img->image_path ?? $img->path ?? $img->url ?? null;
                    if ($path) {
                        $images[] = [
                            'url' => $path,
                            'title' => $img->title ?? null,
                            'caption' => $img->caption ?? $img->description ?? null,
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // Relationship might not exist or fail
            }
        }

        return $images;
    }

    /**
     * Ensure the URL is absolute (http/https).
     */
    protected function ensureAbsoluteUrl(?string $path): ?string
    {
        if (empty($path)) return null;

        $path = trim($path);

        // Already absolute
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        // Handle root-relative paths
        if (str_starts_with($path, '/')) {
            return config('app.url') . $path;
        }

        // Storage URL (if it looks like a relative path)
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        // Fallback to asset() which handles base URL
        return asset($path);
    }

    /**
     * Create a Gzip compressed version of the file.
     */
    protected function createGzip(string $source, string $destination)
    {
        $this->info('Compressing sitemap...');
        try {
            $data = File::get($source);
            $gzipped = gzencode($data, 9);
            File::put($destination, $gzipped);
        } catch (\Throwable $e) {
            $this->error("Failed to gzip sitemap: " . $e->getMessage());
        }
    }

    /**
     * Generate a human-readable HTML sitemap.
     */
   /*  protected function generateHtmlSitemap(string $path)
    {
        $this->info('Generating HTML sitemap...');

        $appName = config('app.name', 'Site Map');
        $date = now()->format('Y-m-d');

        $html = '<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خريطة الموقع - ' . $appName . '</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; padding: 2rem; max-width: 800px; margin: 0 auto; line-height: 1.6; color: #333; background: #f9fafb; }
        .container { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h1 { border-bottom: 2px solid #eee; padding-bottom: 0.5rem; margin-top: 0; color: #111; }
        h2 { margin-top: 2rem; color: #4b5563; border-bottom: 1px solid #eee; padding-bottom: 0.5rem; font-size: 1.5rem; }
        ul { list-style: none; padding: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }
        li { margin-bottom: 0.5rem; }
        a { text-decoration: none; color: #2563eb; transition: color 0.2s; display: block; padding: 0.5rem; border-radius: 4px; background: #f3f4f6; }
        a:hover { text-decoration: none; color: #1d4ed8; background: #e5e7eb; }
        .footer { margin-top: 3rem; font-size: 0.8rem; color: #9ca3af; border-top: 1px solid #eee; padding-top: 1rem; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>خريطة الموقع</h1>
        <p>آخر تحديث: ' . $date . '</p>';

        // Static Section
        $html .= '<section><h2>الصفحات الرئيسية</h2><ul>';

        // Use the collected URLs to populate HTML map if possible, but for simplicity/speed we use the old logic for HTML or adapt
        // We can iterate over static routes defined in addStaticPages if we made it return array, but hardcoding is fine for now
        // Let's reuse the $routes array concept if we refactor, but for now stick to simple list
        $staticRoutes = ['home', 'about', 'contact', 'services.index', 'projects.index', 'blog.index'];
        $titles = [
            'home' => 'الرئيسية',
            'about' => 'من نحن',
            'contact' => 'اتصل بنا',
            'services.index' => 'خدماتنا',
            'projects.index' => 'مشاريعنا',
            'blog.index' => 'المدونة',
        ];

        foreach ($staticRoutes as $r) {
            if (Route::has($r)) {
                $html .= '<li><a href="' . route($r) . '">' . ($titles[$r] ?? $r) . '</a></li>';
            }
        }
        $html .= '</ul></section>';

        // Dynamic Sections
        $sections = [
            'الخدمات' => [Service::class, 'services.show'],
            'المشاريع' => [Project::class, 'projects.show'],
            'المدونة' => [BlogPost::class, 'blog.show'],
        ];

        foreach ($sections as $title => $data) {
            [$class, $route] = $data;
            if (class_exists($class) && Route::has($route)) {
                $items = $class::where('active', true)->latest()->take(100)->get();
                if ($items->count() > 0) {
                    $html .= "<section><h2>{$title}</h2><ul>";
                    foreach ($items as $item) {
                        if (!empty($item->slug)) {
                            $html .= '<li><a href="' . route($route, $item->slug) . '">' . ($item->title ?? 'بدون عنوان') . '</a></li>';
                        }
                    }
                    $html .= '</ul></section>';
                }
            }
        }

        $html .= '<div class="footer">
            &copy; ' . date('Y') . ' ' . $appName . ' - جميع الحقوق محفوظة
        </div>
    </div>
</body>
</html>';

        File::put($path, $html);
    } */

protected function generateHtmlSitemap(string $path)
{
    $this->info('Generating HTML sitemap (HTML view)...');

    $appName = config('app.name', 'Site Map');
    $generatedDate = now();
    $date = $generatedDate->format('Y-m-d');

    // Use collected URLs (populated earlier in handle())
    $urls = $this->urls ?? [];

    // Group URLs heuristically by first path segment for better sections
    $groups = [
        'home' => ['title' => 'الصفحات الرئيسية', 'items' => []],
        'services' => ['title' => 'الخدمات', 'items' => []],
        'projects' => ['title' => 'المشاريع', 'items' => []],
        'blog' => ['title' => 'المدونة', 'items' => []],
        'keywords' => ['title' => 'الكلمات المفتاحية', 'items' => []],
        'other' => ['title' => 'روابط عامة', 'items' => []],
    ];

    foreach ($urls as $u) {
        $loc = $u['loc'] ?? '';
        // try determine section from URL path
        $pathPart = parse_url($loc, PHP_URL_PATH) ?? '';
        $firstSeg = strtolower(trim(explode('/', ltrim($pathPart, '/'))[0] ?? ''));
        if ($firstSeg === '' || $firstSeg === 'home') {
            $groups['home']['items'][] = $u;
        } elseif (str_contains($firstSeg, 'service') || $firstSeg === 'services') {
            $groups['services']['items'][] = $u;
        } elseif (str_contains($firstSeg, 'project') || $firstSeg === 'projects') {
            $groups['projects']['items'][] = $u;
        } elseif ($firstSeg === 'blog' || str_contains($firstSeg, 'post')) {
            $groups['blog']['items'][] = $u;
        } elseif (str_contains($firstSeg, 'keyword') || $firstSeg === 'keywords' || $firstSeg === 'tag') {
            $groups['keywords']['items'][] = $u;
        } else {
            $groups['other']['items'][] = $u;
        }
    }

    // Helper closures
    $fmtDate = function ($d) {
        try {
            if ($d instanceof \Carbon\Carbon) return $d->format('Y-m-d');
            if (is_numeric($d)) return date('Y-m-d', (int)$d);
            if (empty($d)) return '';
            return date('Y-m-d', strtotime($d));
        } catch (\Throwable $e) {
            return '';
        }
    };

    $escape = function ($v) {
        return htmlspecialchars((string)($v ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    };

    // Build HTML via output buffering for readability
    ob_start();
    ?>
    <!doctype html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>خريطة الموقع — <?= $escape($appName) ?></title>
        <meta name="description" content="<?= $escape("خريطة موقع {$appName} — عرض شامل للصفحات، المنشورات، المشاريع، والخدمات مع بيانات SEO (lastmod, priority, changefreq) وصور مصغرة.") ?>">
        <link rel="canonical" href="<?= $escape(url('/sitemap.html')) ?>">
        <link rel="alternate" type="application/xml" title="Sitemap XML" href="<?= $escape(url('/sitemap.xml')) ?>">
        <style>
            :root{ --bg:#f7fafc; --card:#ffffff; --muted:#6b7280; --accent:#0ea5a4; --accent-2:#0b5dff; --maxw:1200px; }
            html,body{height:100%}
            body{font-family: system-ui, -apple-system, "Noto Naskh Arabic", "Noto Sans", Roboto, "Segoe UI", Arial; background:var(--bg); color:#0f172a; margin:0; padding:1.25rem;}
            .wrap{max-width:var(--maxw); margin:0 auto;}
            header{display:flex; gap:1rem; align-items:center; justify-content:space-between; margin-bottom:1rem; flex-wrap:wrap;}
            h1{font-size:1.25rem; margin:0;}
            .sub{color:var(--muted); font-size:0.95rem}
            .controls{display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;}
            .input, .select, .btn{padding:0.5rem 0.75rem; border-radius:8px; border:1px solid #e6eef7; background:#fff; font-size:0.95rem;}
            .grid{display:grid; grid-template-columns: 1fr 320px; gap:1rem; align-items:start;}
            main{background:var(--card); padding:1rem; border-radius:10px; box-shadow:0 8px 28px rgba(2,6,23,0.06);}
            aside{background:var(--card); padding:1rem; border-radius:10px; box-shadow:0 6px 18px rgba(2,6,23,0.04); height:fit-content;}
            .stats{display:flex; gap:1rem; align-items:center; flex-wrap:wrap; margin-bottom:0.5rem}
            .card-small{background:#f8fafc; padding:0.5rem 0.75rem; border-radius:8px; border:1px solid #eef2f7; font-size:0.9rem}
            .section{margin-top:1rem}
            .section h2{margin:0 0 0.6rem 0; font-size:1.05rem; color:#111; border-bottom:1px dashed #eef2f7; padding-bottom:0.45rem}
            .list{display:grid; gap:0.6rem}
            .item{display:flex; gap:0.8rem; align-items:flex-start; padding:0.55rem; border-radius:8px; background:#fff; border:1px solid #f1f5f9}
            .thumb{width:90px;height:64px;object-fit:cover;border-radius:6px;flex:0 0 90px}
            .item .col{flex:1;min-width:0}
            .item a{font-weight:600;color:var(--accent-2);text-decoration:none}
            .meta-small{color:var(--muted);font-size:0.86rem;margin-top:0.35rem}
            .badge{display:inline-block;padding:0.2rem 0.45rem;border-radius:6px;background:#eff6ff;color:#0369a1;font-size:0.78rem;margin-left:0.35rem}
            footer{margin-top:1rem;text-align:center;color:var(--muted);font-size:0.85rem}
            .controls .spacer{width:8px}
            .hidden{display:none !important}
            @media (max-width:900px){ .grid{grid-template-columns:1fr} .thumb{display:none} }
        </style>
    </head>
    <body>
    <div class="wrap" role="main">
        <header>
            <div>
                <h1>خريطة الموقع — <?= $escape($appName) ?></h1>
                <div class="sub">تم التوليد: <?= $escape($date) ?> — الروابط الإجمالية: <strong id="totalCount"><?= count($urls) ?></strong></div>
            </div>

            <div class="controls" role="region" aria-label="أدوات التحكم">
                <input id="search" class="input" placeholder="ابحث بعنوان، وصف، أو رابط..." aria-label="بحث في خريطة الموقع">
                <select id="filterSection" class="select" aria-label="تحديد القسم">
                    <option value="">كل الأقسام</option>
                    <?php foreach ($groups as $key => $g): ?>
                        <?php if (count($g['items'])>0): ?>
                            <option value="<?= $escape($key) ?>"><?= $escape($g['title']) ?> (<?= count($g['items']) ?>)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>

                <select id="sortBy" class="select" aria-label="الفرز">
                    <option value="default">الترتيب الافتراضي</option>
                    <option value="lastmod_desc">الأحدث أولا</option>
                    <option value="lastmod_asc">الأقدم أولا</option>
                    <option value="priority_desc">الأعلى أولوية</option>
                    <option value="priority_asc">الأدنى أولوية</option>
                </select>

                <button id="exportCsv" class="btn" type="button" aria-label="تصدير CSV">تصدير CSV</button>
                <button id="reset" class="btn" type="button" aria-label="إعادة تعيين">إعادة تعيين</button>
            </div>
        </header>

        <div class="grid">
            <main aria-live="polite">
                <?php foreach ($groups as $gKey => $group): ?>
                    <?php if (count($group['items']) === 0) continue; ?>
                    <section class="section" data-section="<?= $escape($gKey) ?>">
                        <h2><?= $escape($group['title']) ?> <span class="badge"><?= count($group['items']) ?></span></h2>
                        <div class="list">
                            <?php foreach ($group['items'] as $i): ?>
                                <?php
                                    $loc = $i['loc'] ?? '#';
                                    $title = $i['title'] ?? ($i['loc'] ?? $loc);
                                    $desc = strip_tags($i['description'] ?? '');
                                    $lastmod = $fmtDate($i['lastmod'] ?? '');
                                    $priority = isset($i['priority']) ? (string)$i['priority'] : '';
                                    $freq = $i['changefreq'] ?? '';
                                    $img = $i['images'][0]['url'] ?? null;
                                ?>
                                <article class="item" role="article"
                                         data-title="<?= $escape(mb_strtolower($title)) ?>"
                                         data-desc="<?= $escape(mb_strtolower($desc)) ?>"
                                         data-lastmod="<?= $escape($lastmod) ?>"
                                         data-priority="<?= $escape($priority) ?>"
                                         data-freq="<?= $escape($freq) ?>">
                                    <?php if ($img): ?>
                                        <img class="thumb" loading="lazy" src="<?= $escape($img) ?>" alt="<?= $escape($i['images'][0]['title'] ?? $title) ?>">
                                    <?php endif; ?>
                                    <div class="col">
                                        <a href="<?= $escape($loc) ?>" target="_blank" rel="noopener noreferrer"><?= $escape($title) ?></a>
                                        <div class="meta-small">
                                            <?php if ($lastmod): ?>آخر تعديل: <?= $escape($lastmod) ?><?php endif; ?>
                                            <?php if ($freq): ?> • تحديث: <?= $escape($freq) ?><?php endif; ?>
                                            <?php if ($priority !== ''): ?> • أولوية: <?= $escape($priority) ?><?php endif; ?>
                                        </div>
                                        <?php if (!empty($desc)): ?>
                                            <div class="meta-small" style="margin-top:0.4rem"><?= $escape(\Illuminate\Support\Str::limit($desc, 180)) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>

                <footer>
                    &copy; <?= date('Y') ?> <?= $escape($appName) ?> — تم الإنشاء آلياً بواسطة sitemap generator.
                </footer>
            </main>

            <aside aria-label="معلومات خريطة الموقع">
                <div class="card-small"><strong>XML</strong> <div style="font-size:0.9rem;"><a href="<?= $escape(url('/sitemap.xml')) ?>"><?= $escape(url('/sitemap.xml')) ?></a></div></div>
                <div style="height:10px"></div>
                <div class="card-small"><strong>مضغوط (gzip)</strong> <div style="font-size:0.9rem;"><a href="<?= $escape(url('/sitemap.xml.gz')) ?>"><?= $escape(url('/sitemap.xml.gz')) ?></a></div></div>
                <div style="height:10px"></div>
                <div class="card-small"><strong>تاريخ التوليد</strong> <div style="font-size:0.9rem;"><?= $escape($generatedDate->toDateTimeString()) ?></div></div>

                <div style="height:10px"></div>
                <div class="card-small">
                    <strong>تعليمات سريعة</strong>
                    <ul style="margin:0.5rem 0 0 0; padding:0; list-style:none; font-size:0.92rem; color:var(--muted)">
                        <li>استخدم روابط XML لمشاركة sitemap لمحركات البحث.</li>
                        <li>تحقق من صحة structured data وlastmod عند نشر تغييرات كبيرة.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context":"https://schema.org",
        "@type":"WebSite",
        "url":"<?= $escape(url('/')) ?>",
        "name":"<?= $escape($appName) ?>",
        "potentialAction":{
            "@type":"SearchAction",
            "target":"<?= $escape(url('/')) ?>/?s={search_term_string}",
            "query-input":"required name=search_term_string"
        }
    }
    </script>

    <script>
    (function(){
        const items = Array.from(document.querySelectorAll('.item'));
        const totalCountEl = document.getElementById('totalCount');
        const searchEl = document.getElementById('search');
        const filterSection = document.getElementById('filterSection');
        const sortBy = document.getElementById('sortBy');
        const resetBtn = document.getElementById('reset');
        const exportBtn = document.getElementById('exportCsv');

        function normalize(v){ return (v||'').toString().trim().toLowerCase(); }

        function apply() {
            const q = normalize(searchEl.value);
            const section = normalize(filterSection.value);
            let visible = items.filter(it => {
                const parentSection = it.closest('section')?.dataset?.section || '';
                const t = normalize(it.dataset.title || '');
                const d = normalize(it.dataset.desc || '');
                const matchesQuery = q === '' || t.includes(q) || d.includes(q) || it.querySelector('a').href.toLowerCase().includes(q);
                const matchesSection = section === '' || parentSection === section;
                return matchesQuery && matchesSection;
            });

            // Sorting
            const s = sortBy.value;
            visible.sort((a,b)=>{
                if (s.startsWith('lastmod')) {
                    const ad = a.dataset.lastmod || ''; const bd = b.dataset.lastmod || '';
                    if(ad === bd) return 0;
                    if(!ad) return 1; if(!bd) return -1;
                    return s === 'lastmod_desc' ? (bd.localeCompare(ad)) : (ad.localeCompare(bd));
                }
                if (s.startsWith('priority')) {
                    const ap = parseFloat(a.dataset.priority||0); const bp = parseFloat(b.dataset.priority||0);
                    return s === 'priority_desc' ? (bp - ap) : (ap - bp);
                }
                return 0;
            });

            // Clear DOM and re-append
            const container = document.querySelector('main');
            visible.forEach(v => {
                const sec = v.closest('section');
                // ensure section visible
                if (sec && !container.contains(sec)) {
                    container.appendChild(sec);
                }
            });

            // Show/hide items
            items.forEach(i => i.style.display = visible.includes(i) ? '' : 'none');

            totalCountEl.textContent = visible.length;
        }

        searchEl.addEventListener('input', apply);
        filterSection.addEventListener('change', apply);
        sortBy.addEventListener('change', apply);
        resetBtn.addEventListener('click', ()=>{
            searchEl.value=''; filterSection.value=''; sortBy.value='default'; apply();
        });

        exportBtn.addEventListener('click', ()=>{
            // Export currently visible items to CSV
            const headers = ['title','url','lastmod','priority','changefreq','description'];
            const rows = [];
            items.forEach(i=>{
                if (i.style.display === 'none') return;
                const a = i.querySelector('a');
                const title = a.textContent.trim();
                const url = a.href;
                const lastmod = i.dataset.lastmod || '';
                const priority = i.dataset.priority || '';
                const changefreq = i.dataset.freq || '';
                const desc = i.dataset.desc || '';
                rows.push([title, url, lastmod, priority, changefreq, desc]);
            });
            let csv = headers.join(',') + '\\n' + rows.map(r => r.map(c => '"' + (String(c||'').replace(/"/g,'""')) + '"').join(',')).join('\\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'sitemap-export-<?= date('Ymd_His') ?>.csv';
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });

        // initial run
        apply();
    })();
    </script>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    // Write file (atomic-ish)
    try {
        File::put($path, $html);
        $this->info("HTML sitemap written to {$path}");
    } catch (\Throwable $e) {
        $this->error("Failed to write HTML sitemap: " . $e->getMessage());
    }
}
}
