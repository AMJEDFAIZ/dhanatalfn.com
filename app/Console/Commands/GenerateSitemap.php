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
        $title = $model->meta_title ?? $model->title ?? '';
        $description = $model->meta_description ?? $model->description ?? '';

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
    protected function generateHtmlSitemap(string $path)
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
    }
}
