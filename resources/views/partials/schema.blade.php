@php
/*
|--------------------------------------------------------------------------
| Schema.org JSON-LD - نسخة احترافية محسّنة
|--------------------------------------------------------------------------
| يغطي جميع صفحات الموقع الثابتة والديناميكية بشكل كامل ومتوافق
| مع متطلبات Google Rich Results ومعايير Schema.org الرسمية.
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Str;

// ─── دوال مساعدة داخلية ─────────────────────────────────────

/**
 * تنظيف نص وإزالة HTML وتطبيع المسافات.
 */
$cleanText = fn(?string $v): string => $v !== null
    ? trim(preg_replace('/\s+/u', ' ', strip_tags($v)))
    : '';

/**
 * قصّ النص على حد 160 حرفاً بعد التنظيف.
 */
$safeDesc = fn(?string $v): string => Str::limit(
    trim(preg_replace('/\s+/u', ' ', strip_tags((string) $v))),
    160
);

// ─── بيانات الموقع الأساسية ─────────────────────────────────

$siteUrl        = rtrim(url('/'), '/');
$currentUrl     = url()->current();
$webPageId      = $currentUrl;
$siteName       = $cleanText($settings['site_name'] ?? config('app.name'));
// وصف الموقع الثابت (للـ Organization وWebSite - لا يتغير بين الصفحات)
$siteDescription = $cleanText((string) ($settings['site_description'] ?? ''));
// وصف الصفحة الحالية (للـ WebPage meta)
$pageDescription = $cleanText(!empty($meta_description ?? null)
    ? (string) $meta_description
    : $siteDescription);
$phone          = !empty($settings['phone']) ? (string) $settings['phone'] : null;

// ─── اللوجو ──────────────────────────────────────────────────

$defaultLogoUrl       = asset('assets/img/logo.png');
$defaultLogoLocalPath = public_path('assets/img/logo.png');
$logoUrl              = !empty($settings['site_logo'])
    ? asset('storage/' . $settings['site_logo'])
    : $defaultLogoUrl;
$logoLocalPath = null;
if (!empty($settings['site_logo'])) {
    try {
        $p = \Illuminate\Support\Facades\Storage::disk('public')->path($settings['site_logo']);
        $logoLocalPath = (is_string($p) && $p !== '' && file_exists($p)) ? $p : null;
    } catch (\Throwable) {
        $logoLocalPath = null;
    }
} elseif (is_string($defaultLogoLocalPath) && $defaultLogoLocalPath !== '' && file_exists($defaultLogoLocalPath)) {
    $logoLocalPath = $defaultLogoLocalPath;
}

// ─── الكلمات المفتاحية ───────────────────────────────────────

// دمج جميع مصادر الكلمات المفتاحية بالأولوية
$_rawKwSources = [];

// 1. من meta_keywords المُمرَّر من الـ Controller
if (isset($meta_keywords)) {
    if (is_array($meta_keywords)) {
        $_rawKwSources = $meta_keywords;
    } elseif (is_string($meta_keywords) && $meta_keywords !== '') {
        $_rawKwSources = preg_split('/[,،\s]+/u', $meta_keywords, -1, PREG_SPLIT_NO_EMPTY);
    }
}

// 2. من contentKeywords (علاقة المودل hasKeywords)
if (!empty($contentKeywords) && method_exists($contentKeywords, 'pluck')) {
    $_rawKwSources = array_merge($_rawKwSources, $contentKeywords->pluck('name')->filter()->values()->all());
}

// 3. من pageContentKeywords (صفحات SEO المُدارة من لوحة التحكم)
if (!empty($pageContentKeywords) && method_exists($pageContentKeywords, 'pluck')) {
    $_rawKwSources = array_merge($_rawKwSources, $pageContentKeywords->pluck('name')->filter()->values()->all());
}

// 4. إضافة اسم الـ keyword نفسه في صفحات الكلمات المفتاحية
if (empty($_rawKwSources) && request()->routeIs('keywords.show') && isset($keyword) && is_object($keyword)) {
    $_rawKwSources[] = $keyword->name ?? '';
}

// بناء المصفوفة النهائية: تنظيف، فريد، بدون فراغات
$keywordsArray = array_values(array_unique(array_filter(
    array_map(fn ($v) => $cleanText((string) $v), $_rawKwSources)
)));

// نسخة نصية للأنواع التي تقبل string فقط
$schemaKeywords = !empty($keywordsArray) ? implode(', ', $keywordsArray) : null;
unset($_rawKwSources);

// ─── الشبكات الاجتماعية ──────────────────────────────────────

$_normSocial = function (?string $value): ?string {
    if (!is_string($value)) return null;
    $value = trim($value);
    if ($value === '') return null;
    if (preg_match('/^https?:\/\//i', $value)) return $value;
    if (preg_match('/^[a-z0-9.-]+\.[a-z]{2,}(\/|$)/i', $value)) return 'https://' . $value;
    $digits = preg_replace('/\D+/', '', $value);
    if ($digits !== '' && strlen($digits) >= 7) return 'https://wa.me/' . $digits;
    return null;
};

$socials = array_values(array_filter(array_map($_normSocial, [
    $settings['facebook']  ?? null,
    $settings['twitter']   ?? null,
    $settings['instagram'] ?? null,
    $settings['youtube']   ?? null,
    $settings['linkedin']  ?? null,
    $settings['tiktok']    ?? null,
    $settings['snapchat']  ?? null,
])));

$whatsappUrl = $_normSocial($settings['whatsapp'] ?? null);
if (!empty($whatsappUrl)) {
    $socials[] = $whatsappUrl;
    $socials = array_values(array_unique($socials));
}
unset($_normSocial);

// ─── دالة بناء ImageObject (مشتركة) ─────────────────────────

$makeImageObject = function (
    string $id,
    string $url,
    ?string $localPath = null,
    ?string $caption = null
) use ($siteName): array {
    $img = [
        '@type'      => 'ImageObject',
        '@id'        => $id,
        'inLanguage' => 'ar-SA',
        'url'        => $url,
        'contentUrl' => $url,
    ];
    if (!empty($caption)) {
        $img['caption'] = $caption;
    }
    if ($localPath && is_string($localPath) && $localPath !== '' && file_exists($localPath)) {
        $size = @getimagesize($localPath);
        if (is_array($size) && isset($size[0], $size[1]) && $size[0] > 0 && $size[1] > 0) {
            $img['width']  = (int) $size[0];
            $img['height'] = (int) $size[1];
        }
    }
    return $img;
};

// ─── هيكل JSON-LD الرئيسي ────────────────────────────────────

$schema = [
    '@context' => 'https://schema.org',
    '@graph'   => [],
];

// الأنواع التي لا تحتاج keywords
$_kwExclude = ['BreadcrumbList', 'ImageObject', 'WebSite', 'WebPage', 'ItemPage',
               'AboutPage', 'ContactPage', 'CollectionPage', 'Person',
               'Organization', 'HomeAndConstructionBusiness'];

/**
 * إضافة عنصر للـ @graph، مع إلحاق الكلمات المفتاحية إن طُلب ذلك.
 */
$addToGraph = function (array $item, bool $withKeywords = false) use (&$schema, &$keywordsArray, &$schemaKeywords, &$_kwExclude): void {
    if ($withKeywords && !array_key_exists('keywords', $item)) {
        $type  = $item['@type'] ?? null;
        $types = is_array($type) ? $type : (is_string($type) ? [$type] : []);
        $excluded = false;
        foreach ($types as $t) {
            if (in_array($t, $_kwExclude, true)) { $excluded = true; break; }
        }
        if (!$excluded) {
            if (!empty($keywordsArray)) {
                $item['keywords'] = $keywordsArray;
            } elseif (!empty($schemaKeywords)) {
                $item['keywords'] = $schemaKeywords;
            }
        }
    }
    $schema['@graph'][] = $item;
};

// ──────────────────────────────────────────────────────────────
// 1. LOGO ImageObject
// ──────────────────────────────────────────────────────────────

$logoId = $siteUrl . '/#logo';
$addToGraph($makeImageObject($logoId, $logoUrl, $logoLocalPath, $siteName));

// ──────────────────────────────────────────────────────────────
// 2. Organization (HomeAndConstructionBusiness)
// ──────────────────────────────────────────────────────────────

$orgId = $siteUrl . '/#organization';
$organization = [
    '@type'              => 'HomeAndConstructionBusiness',
    '@id'                => $orgId,
    'name'               => $siteName,
    'url'                => $siteUrl,
    'logo'               => ['@id' => $logoId],
    'image'              => ['@id' => $logoId],
    'description'        => $siteDescription,
    'priceRange'         => $settings['price_range'] ?? '$$',
    'currenciesAccepted' => 'SAR',
    'paymentAccepted'    => 'Cash, Bank Transfer',
    'geo'                => [
        '@type'     => 'GeoCoordinates',
        'latitude'  => (isset($settings['latitude']) && $settings['latitude'] !== '')
                         ? (float) $settings['latitude'] : 21.567355,
        'longitude' => (isset($settings['longitude']) && $settings['longitude'] !== '')
                         ? (float) $settings['longitude'] : 39.1925,
    ],
    'areaServed' => [
        '@type'  => 'City',
        'name'   => 'Jeddah',
        'sameAs' => 'https://www.wikidata.org/wiki/Q78441',
    ],
    'hasMap' => 'https://maps.google.com/maps?q=' .
        ((isset($settings['latitude']) && $settings['latitude'] !== '') ? (float)$settings['latitude'] : 21.567355) .
        ',' .
        ((isset($settings['longitude']) && $settings['longitude'] !== '') ? (float)$settings['longitude'] : 39.1925),
];

if ($phone) {
    $organization['telephone'] = $phone;
}
if (!empty($settings['email'])) {
    $organization['email'] = (string) $settings['email'];
}
if (!empty($settings['address'])) {
    $organization['address'] = [
        '@type'           => 'PostalAddress',
        'streetAddress'   => $cleanText((string) $settings['address']),
        'addressLocality' => 'جدة',
        'addressRegion'   => 'المنطقة الغربية',
        'addressCountry'  => 'SA',
        'postalCode'      => $settings['postal_code'] ?? '',
    ];
}
if (!empty($settings['opens']) && !empty($settings['closes'])) {
    $organization['openingHoursSpecification'] = [[
        '@type'      => 'OpeningHoursSpecification',
        'dayOfWeek'  => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        'opens'      => (string) $settings['opens'],
        'closes'     => (string) $settings['closes'],
    ]];
}
if (!empty($socials)) {
    $organization['sameAs'] = $socials;
}
// contactPoint: هاتف + واتساب
$_contactPoints = [];
if ($phone) {
    $_contactPoints[] = [
        '@type'             => 'ContactPoint',
        'telephone'         => $phone,
        'contactType'       => 'customer service',
        'areaServed'        => 'SA',
        'availableLanguage' => ['Arabic'],
        'contactOption'     => 'TollFree',
    ];
}
if (!empty($whatsappUrl)) {
    $_contactPoints[] = [
        '@type'             => 'ContactPoint',
        'url'               => $whatsappUrl,
        'contactType'       => 'customer service',
        'areaServed'        => 'SA',
        'availableLanguage' => ['Arabic'],
    ];
}
if (!empty($_contactPoints)) {
    $organization['contactPoint'] = $_contactPoints;
}
unset($_contactPoints);

$addToGraph($organization);

// ──────────────────────────────────────────────────────────────
// 3. WebSite (مع SearchAction)
// ──────────────────────────────────────────────────────────────

$websiteId = $siteUrl . '/#website';
$addToGraph([
    '@type'           => 'WebSite',
    '@id'             => $websiteId,
    'url'             => $siteUrl,
    'name'            => $siteName,
    'description'     => $siteDescription,
    'publisher'       => ['@id' => $orgId],
    'inLanguage'      => 'ar-SA',
    'potentialAction' => [[
        '@type'       => 'SearchAction',
        'target'      => [
            '@type'       => 'EntryPoint',
            'urlTemplate' => url('/blog') . '?search={search_term_string}',
        ],
        'query-input' => [
            '@type'         => 'PropertyValueSpecification',
            'valueRequired' => true,
            'valueName'     => 'search_term_string',
        ],
    ]],
]);

// عنصر التنقل الرئيسي (SiteNavigationElement) لتعزيز الفهم البنيوي
$navId = $siteUrl . '/#sitenav';
$navUrls = array_values(array_filter([
    function_exists('route') ? route('home') : $siteUrl,
    function_exists('route') ? route('services.index') : ($siteUrl . '/services'),
    function_exists('route') ? route('projects.index') : ($siteUrl . '/projects'),
    function_exists('route') ? route('blog.index') : ($siteUrl . '/blog'),
    function_exists('route') ? route('about') : ($siteUrl . '/about'),
    function_exists('route') ? route('contact') : ($siteUrl . '/contact'),
]));
$addToGraph([
    '@type'      => 'SiteNavigationElement',
    '@id'        => $navId,
    'name'       => 'القائمة الرئيسية',
    'inLanguage' => 'ar-SA',
    'url'        => $navUrls,
], false);
// ربط عنصر التنقل بالموقع
foreach ($schema['@graph'] as &$_node) {
    if (($_node['@id'] ?? null) === $websiteId) {
        $_node['hasPart'] = array_merge($_node['hasPart'] ?? [], [['@id' => $navId]]);
        break;
    }
}
unset($_node);

// ──────────────────────────────────────────────────────────────
// 4. Person (المؤلف / صاحب الموقع)
// ──────────────────────────────────────────────────────────────

$authorName         = $cleanText($settings['author_name'] ?? null) ?: $siteName;
$authorUrl          = !empty($settings['author_url'] ?? null) ? (string) $settings['author_url'] : $siteUrl;
$authorId           = $siteUrl . '/#/schema/person/' . md5($authorName);
$authorImageUrl     = null;
$authorImageLocalPath = null;

if (!empty($settings['author_image'] ?? null) && is_string($settings['author_image'])) {
    $raw = trim($settings['author_image']);
    if (preg_match('/^https?:\/\//i', $raw)) {
        $authorImageUrl = $raw;
    } else {
        $authorImageUrl = asset('storage/' . ltrim($raw, '/'));
        try {
            $p = \Illuminate\Support\Facades\Storage::disk('public')->path(ltrim($raw, '/'));
            $authorImageLocalPath = (is_string($p) && $p !== '' && file_exists($p)) ? $p : null;
        } catch (\Throwable) {
            $authorImageLocalPath = null;
        }
    }
} elseif (!empty($settings['email'] ?? null) && is_string($settings['email'])) {
    $authorImageUrl = 'https://secure.gravatar.com/avatar/' . md5(strtolower(trim($settings['email']))) . '?s=96&d=mm&r=g';
}

$authorImageId = $siteUrl . '/#/schema/person/image';
if (!empty($authorImageUrl)) {
    $addToGraph($makeImageObject($authorImageId, $authorImageUrl, $authorImageLocalPath, $authorName));
}

$person = [
    '@type' => 'Person',
    '@id'   => $authorId,
    'name'  => $authorName,
    'url'   => $authorUrl,
];
if (!empty($authorImageUrl)) {
    $person['image'] = ['@id' => $authorImageId];
}
// sameAs للشخص: الشبكات الاجتماعية فقط (بدون URL الموقع لتجنب التعارض مع Organization)
$_personSameAs = array_values(array_unique(array_filter($socials)));
if (!empty($_personSameAs)) {
    $person['sameAs'] = $_personSameAs;
}
unset($_personSameAs);
$addToGraph($person);

// ──────────────────────────────────────────────────────────────
// 5. Breadcrumbs (تُبنى ديناميكياً)
// ──────────────────────────────────────────────────────────────

$breadcrumbs = [[
    '@type'    => 'ListItem',
    'position' => 1,
    'name'     => 'الرئيسية',
    'item'     => route('home'),
]];

$addBreadcrumb = function (string $name, string $url) use (&$breadcrumbs): void {
    $breadcrumbs[] = [
        '@type'    => 'ListItem',
        'position' => count($breadcrumbs) + 1,
        'name'     => $name,
        'item'     => $url,
    ];
};

// ──────────────────────────────────────────────────────────────
// 6. دالة مساعدة: أقصى تاريخ تعديل من مجموعة من النماذج
// ──────────────────────────────────────────────────────────────

$maxIsoFromModels = function ($models): ?string {
    $max = null;
    if (!is_iterable($models)) return null;
    foreach ($models as $m) {
        if (!is_object($m)) continue;
        $dt = $m->updated_at ?? $m->published_at ?? $m->created_at ?? null;
        if (!is_object($dt) || !method_exists($dt, 'toIso8601String')) continue;
        $iso = $dt->toIso8601String();
        if (!is_string($iso) || $iso === '') continue;
        if ($max === null || strcmp($iso, $max) > 0) $max = $iso;
    }
    return $max;
};

// ──────────────────────────────────────────────────────────────
// 7. منطق تحديد نوع الصفحة وبياناتها الخاصة
// ──────────────────────────────────────────────────────────────

$webPageTypes         = ['WebPage'];
$webPageMainEntity    = null;
$webPageDatePublished = null;
$webPageDateModified  = null;
// تعريف كيان about للصفحة بشكل افتراضي (سيتم تحديثه ديناميكياً لكل نوع صفحة)
$webPageAbout         = ['@id' => $orgId];
// مؤشر لوجود قسم أسئلة شائعة في الصفحة الحالية، لإضافة نوع FAQPage إلى WebPage عند الحاجة
$hasFaq               = false;
// أجزاء الصفحة (hasPart) - نستخدمها خصوصاً في الصفحة الرئيسية لعرض القوائم المميّزة
$webPageHasPartIds    = [];

// ┌──────────────────────────────────────────────────────────┐
// │ 7.1 الصفحة الرئيسية                                     │
// └──────────────────────────────────────────────────────────┘
if (request()->routeIs('home') || request()->is('/')) {
    $webPageTypes = ['WebPage'];
    // تحديد dateModified من أحدث محتوى
    if (!empty($latestPosts) && is_iterable($latestPosts)) {
        $webPageDateModified = $maxIsoFromModels($latestPosts);
    }
    // تعيين الكيان الرئيسي للصفحة الرئيسية إلى المؤسسة
    $webPageMainEntity = ['@id' => $orgId];

    // AggregateRating + Reviews على Organization
    $testimonialsList = [];
    if (isset($testimonials) && is_iterable($testimonials)) {
        $testimonialsList = method_exists($testimonials, 'items')
            ? $testimonials->items()
            : (is_array($testimonials) ? $testimonials : iterator_to_array($testimonials, false));
    }
    if (!empty($testimonialsList)) {
        $reviews      = [];
        $totalRating  = 0;
        foreach ($testimonialsList as $t) {
            if (!is_object($t)) continue;
            $reviews[] = [
                '@type'        => 'Review',
                'author'       => ['@type' => 'Person', 'name' => $cleanText($t->client_name ?? '')],
                'datePublished'=> $t->created_at?->format('Y-m-d'),
                'reviewRating' => [
                    '@type'       => 'Rating',
                    'ratingValue' => (string) ($t->rating ?? 5),
                    'bestRating'  => '5',
                    'worstRating' => '1',
                ],
                'reviewBody'   => $cleanText($t->content ?? ''),
                'publisher'    => ['@id' => $orgId],
            ];
            $totalRating += (float) ($t->rating ?? 5);
        }
        $reviewCount = count($reviews);
        if ($reviewCount > 0) {
            $avgRating = $totalRating / $reviewCount;
            // نبحث عن Organization في @graph ونضيف عليها التقييمات
            foreach ($schema['@graph'] as &$_graphNode) {
                if (($_graphNode['@id'] ?? null) === $orgId) {
                    $_graphNode['aggregateRating'] = [
                        '@type'       => 'AggregateRating',
                        'ratingValue' => number_format($avgRating, 1),
                        'reviewCount' => $reviewCount,
                        'bestRating'  => '5',
                        'worstRating' => '1',
                    ];
                    $_graphNode['review'] = $reviews;
                    break;
                }
            }
            unset($_graphNode);
        }
    }

    // قوائم غنية على الصفحة الرئيسية (إن توفرت بيانات): خدمات، مشاريع، مقالات
    // قائمة الخدمات المميزة
    if (!empty($services) && is_iterable($services)) {
        $pos = 1;
        $items = [];
        foreach ($services as $svc) {
            if (!is_object($svc) || empty($svc->title)) continue;
            $url = isset($svc->slug) ? route('services.show', $svc->slug) : url('/services/' . ($svc->id ?? ''));
            $item = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => $cleanText($svc->title),
                'url'      => $url,
                'item'     => [
                    '@type'    => 'Service',
                    'name'     => $cleanText($svc->title),
                    'url'      => $url,
                    'provider' => ['@id' => $orgId],
                    'image'    => !empty($svc->image_path) ? asset('storage/' . $svc->image_path) : $logoUrl,
                ],
            ];
            // عرض سعر اختياري إذا تم ضبطه في الإعدادات
            if (!empty($settings['service_price_from']) && is_numeric($settings['service_price_from'])) {
                $item['item']['offers'] = [
                    '@type'         => 'Offer',
                    'priceCurrency' => 'SAR',
                    'price'         => (string) $settings['service_price_from'],
                    'availability'  => 'https://schema.org/InStock',
                    'seller'        => ['@id' => $orgId],
                ];
            }
            $items[] = $item;
            if ($pos > 8) break;
        }
        if (!empty($items)) {
            $listId = $currentUrl . '#home-services';
            $addToGraph([
                '@type'           => 'ItemList',
                '@id'             => $listId,
                'name'            => 'الخدمات المميزة - ' . $siteName,
                'numberOfItems'   => count($items),
                'itemListElement' => $items,
                'itemListOrder'   => 'https://schema.org/ItemListOrderAscending',
            ], true);
            $webPageHasPartIds[] = $listId;
        }
    }
    // قائمة المشاريع المميزة
    if (!empty($projects) && is_iterable($projects)) {
        $pos = 1;
        $items = [];
        foreach ($projects as $prj) {
            if (!is_object($prj) || empty($prj->title)) continue;
            $url = isset($prj->slug) ? route('projects.show', $prj->slug) : url('/projects/' . ($prj->id ?? ''));
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => $cleanText($prj->title),
                'url'      => $url,
                'item'     => [
                    '@type'   => 'CreativeWork',
                    'name'    => $cleanText($prj->title),
                    'url'     => $url,
                    'creator' => ['@id' => $orgId],
                    'image'   => !empty($prj->main_image) ? asset('storage/' . $prj->main_image) : $logoUrl,
                ],
            ];
            if ($pos > 8) break;
        }
        if (!empty($items)) {
            $listId = $currentUrl . '#home-projects';
            $addToGraph([
                '@type'           => 'ItemList',
                '@id'             => $listId,
                'name'            => 'المشاريع المميزة - ' . $siteName,
                'numberOfItems'   => count($items),
                'itemListElement' => $items,
                'itemListOrder'   => 'https://schema.org/ItemListOrderDescending',
            ], true);
            $webPageHasPartIds[] = $listId;
        }
    }
    // قائمة أحدث المقالات
    if (!empty($latestPosts) && is_iterable($latestPosts)) {
        $pos = 1;
        $items = [];
        foreach ($latestPosts as $p) {
            if (!is_object($p)) continue;
            $url       = route('blog.show', $p->slug ?? $p->id);
            $published = $p->published_at?->toIso8601String() ?? $p->created_at?->toIso8601String();
            $modified  = $p->updated_at?->toIso8601String() ?? $published;
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'url'      => $url,
                'name'     => $cleanText($p->title ?? ''),
                'item'     => [
                    '@type'         => 'BlogPosting',
                    'headline'      => $cleanText($p->title ?? ''),
                    'url'           => $url,
                    'datePublished' => $published,
                    'dateModified'  => $modified,
                    'author'        => ['@id' => $authorId],
                    'publisher'     => ['@id' => $orgId],
                    'image'         => !empty($p->image_path) ? asset('storage/' . $p->image_path) : $logoUrl,
                ],
            ];
            if ($pos > 8) break;
        }
        if (!empty($items)) {
            $listId = $currentUrl . '#home-posts';
            $addToGraph([
                '@type'           => 'ItemList',
                '@id'             => $listId,
                'name'            => 'أحدث المقالات - ' . $siteName,
                'numberOfItems'   => count($items),
                'itemListElement' => $items,
                'itemListOrder'   => 'https://schema.org/ItemListOrderDescending',
            ], true);
            $webPageHasPartIds[] = $listId;
        }
    }

// ┌──────────────────────────────────────────────────────────┐
// │ 7.2 قائمة الخدمات                                       │
// └──────────────────────────────────────────────────────────┘
} elseif (request()->routeIs('services.index')) {
    $webPageTypes = ['CollectionPage'];
    $addBreadcrumb('الخدمات', route('services.index'));
    $webPageDateModified = $maxIsoFromModels($services ?? null);

    $servicesListItems = [];
    if (isset($services) && is_iterable($services)) {
        $pos = 1;
        foreach ($services as $svc) {
            if (!is_object($svc) || empty($svc->title)) continue;
            $url = isset($svc->slug) ? route('services.show', $svc->slug) : url('/services/' . $svc->id);
            $listItem = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => $cleanText($svc->title),
                'url'      => $url,
                'item'     => [
                    '@type'    => 'Service',
                    'name'     => $cleanText($svc->title),
                    'url'      => $url,
                    'provider' => ['@id' => $orgId],
                    'image'    => !empty($svc->image_path) ? asset('storage/' . $svc->image_path) : $logoUrl,
                ],
            ];
            // عرض سعر اختياري إذا تم ضبطه في الإعدادات
            if (!empty($settings['service_price_from']) && is_numeric($settings['service_price_from'])) {
                $listItem['item']['offers'] = [
                    '@type'         => 'Offer',
                    'priceCurrency' => 'SAR',
                    'price'         => (string) $settings['service_price_from'],
                    'availability'  => 'https://schema.org/InStock',
                    'seller'        => ['@id' => $orgId],
                ];
            }
            $servicesListItems[] = $listItem;
        }
    }

    $itemListId = $currentUrl . '#itemlist';
    $addToGraph([
        '@type'           => 'ItemList',
        '@id'             => $itemListId,
        'name'            => ($settings['services_meta_title'] ?? 'خدماتنا') . ' - ' . $siteName,
        'description'     => $cleanText($settings['services_meta_description'] ?? 'جميع خدمات الدهانات والديكور في جدة'),
        'numberOfItems'   => count($servicesListItems),
        'itemListElement' => $servicesListItems,
        'itemListOrder'   => 'https://schema.org/ItemListOrderAscending',
    ], true);

    $collectionId = $currentUrl . '#collection';
    $addToGraph([
        '@type'       => 'CollectionPage',
        '@id'         => $collectionId,
        'name'        => ($settings['services_meta_title'] ?? 'خدماتنا') . ' - ' . $siteName,
        'url'         => $currentUrl,
        'description' => $cleanText($settings['services_meta_description'] ?? 'تصفح جميع خدمات الدهانات والديكورات في جدة'),
        'mainEntity'  => ['@id' => $itemListId],
        'isPartOf'    => ['@id' => $websiteId],
        'about'       => ['@id' => $orgId],
        'publisher'   => ['@id' => $orgId],
        'inLanguage'  => 'ar-SA',
    ]);
    $webPageMainEntity = ['@id' => $collectionId];

// ┌──────────────────────────────────────────────────────────┐
// │ 7.3 صفحة خدمة مفردة                                     │
// └──────────────────────────────────────────────────────────┘
} elseif (request()->routeIs('services.show') && isset($service) && is_object($service)) {
    $webPageTypes        = ['ItemPage'];
    $webPageDatePublished = $service->created_at?->toIso8601String();
    $webPageDateModified  = $service->updated_at?->toIso8601String();
    $addBreadcrumb('الخدمات', route('services.index'));
    $addBreadcrumb($cleanText($service->title), $currentUrl);

    $serviceId         = $currentUrl . '#service';
    $webPageMainEntity = ['@id' => $serviceId];
    $svcThumb          = !empty($service->image_path) ? asset('storage/' . $service->image_path) : $logoUrl;
    $svcBodyText       = $cleanText((string) ($service->description ?? ''));
    $svcWordCount      = count(array_filter(preg_split('/\s+/u', $svcBodyText) ?: [], fn ($w) => $w !== ''));
    $svcSection        = [];
    if (!empty($contentKeywords) && method_exists($contentKeywords, 'pluck')) {
        $firstSec = $contentKeywords->pluck('name')->filter()->first();
        if (is_string($firstSec) && $firstSec !== '') {
            $svcSection = [$cleanText($firstSec)];
        }
    }

    // تجهيز عرض السعر (Offer) إن توفر سعر أساسي في الإعدادات لتفادي تحذيرات Google
    $svcOffer = null;
    if (!empty($settings['service_price_from']) && is_numeric($settings['service_price_from'])) {
        $svcOffer = [
            '@type'         => 'Offer',
            'priceCurrency' => 'SAR',
            'price'         => (string) $settings['service_price_from'],
            'availability'  => 'https://schema.org/InStock',
            'seller'        => ['@id' => $orgId],
        ];
    }

    $svcNode = [
        '@type'            => ['Service', 'Article'],
        '@id'              => $serviceId,
        'isPartOf'         => ['@id' => $webPageId],
        'author'           => ['@id' => $authorId],
        'publisher'        => ['@id' => $orgId],
        'headline'         => Str::limit($cleanText($service->meta_title ?: $service->title), 110),
        'name'             => $cleanText($service->meta_title ?: $service->title),
        'dateCreated'      => $webPageDatePublished,
        'datePublished'    => $webPageDatePublished,
        'dateModified'     => $webPageDateModified,
        'mainEntityOfPage' => ['@id' => $webPageId],
        'image'            => ['@id' => $currentUrl . '#primaryimage'],
        'thumbnailUrl'     => $svcThumb,
        'inLanguage'       => 'ar-SA',
        'description'      => $safeDesc($service->meta_description ?: $service->description ?? ''),
        'articleBody'      => $svcBodyText,
        'url'              => $currentUrl,
        'provider'         => ['@id' => $orgId],
        'areaServed'       => [
            '@type'  => 'City',
            'name'   => 'Jeddah',
            'sameAs' => 'https://www.wikidata.org/wiki/Q78441',
        ],
        'serviceType'      => $cleanText($service->title) ?: 'Home Improvement',
        'category'         => 'Home Improvement',
    ];
    // إضافة العرض فقط عند وجود سعر لتجنّب التحذيرات
    if ($svcOffer) {
        $svcNode['offers'] = $svcOffer;
    }
    if (!empty($keywordsArray))    $svcNode['keywords']       = $keywordsArray;
    if (!empty($svcSection))       $svcNode['articleSection'] = $svcSection;
    if ($svcWordCount > 0)         $svcNode['wordCount']      = $svcWordCount;
    if (!empty($service->created_at)) {
        $svcNode['copyrightYear'] = $service->created_at->format('Y');
    }
    $addToGraph($svcNode, true);

    // خدمات ذات صلة من الشريط الجانبي (بنفس نمط المقالات)
    if (!empty($sidebarServices) && is_iterable($sidebarServices)) {
        $relSvcs = [];
        foreach ($sidebarServices as $_s) {
            if (!is_object($_s) || empty($_s->slug) || $_s->slug === $service->slug) continue;
            $relSvcs[] = [
                '@type'    => ['Service', 'Article'],
                'headline' => $cleanText($_s->title ?? ''),
                'name'     => $cleanText($_s->title ?? ''),
                'url'      => route('services.show', $_s->slug),
                'provider' => ['@id' => $orgId],
            ];
            if (count($relSvcs) >= 5) break;
        }
        if (!empty($relSvcs)) {
            foreach ($schema['@graph'] as &$_svcRef) {
                if (($_svcRef['@id'] ?? null) === $serviceId) {
                    $_svcRef['isRelatedTo'] = $relSvcs;
                    break;
                }
            }
            unset($_svcRef);
        }
    }

    // FAQ Schema للخدمات
    if (!empty($service->faqs) && $service->faqs->count() > 0) {
        $faqItems = [];
        foreach ($service->faqs as $faq) {
            if (!is_object($faq)) continue;
            $faqItems[] = [
                '@type'          => 'Question',
                'name'           => $cleanText($faq->question ?? ''),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $cleanText($faq->answer ?? ''),
                ],
            ];
        }
        if (!empty($faqItems)) {
            $addToGraph([
                '@type'      => 'FAQPage',
                '@id'        => $currentUrl . '#faq',
                'mainEntity' => $faqItems,
                'isPartOf'   => ['@id' => $webPageId],
            ], true);
        }
    }

// ┌──────────────────────────────────────────────────────────┐
// │ 7.4 قائمة المشاريع                                      │
// └──────────────────────────────────────────────────────────┘
} elseif (request()->routeIs('projects.index')) {
    $webPageTypes = ['CollectionPage'];
    $addBreadcrumb('المشاريع', route('projects.index'));
    $webPageDateModified = $maxIsoFromModels($projects ?? null);

    $projectsListItems = [];
    if (isset($projects) && is_iterable($projects)) {
        $pos = 1;
        foreach ($projects as $prj) {
            if (!is_object($prj) || empty($prj->title)) continue;
            $url = isset($prj->slug) ? route('projects.show', $prj->slug) : url('/projects/' . $prj->id);
            $projectsListItems[] = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => $cleanText($prj->title),
                'url'      => $url,
                'item'     => [
                    '@type'   => 'CreativeWork',
                    'name'    => $cleanText($prj->title),
                    'url'     => $url,
                    'creator' => ['@id' => $orgId],
                    'image'   => !empty($prj->main_image) ? asset('storage/' . $prj->main_image) : $logoUrl,
                ],
            ];
        }
    }

    $itemListId = $currentUrl . '#itemlist';
    $addToGraph([
        '@type'           => 'ItemList',
        '@id'             => $itemListId,
        'name'            => ($settings['projects_meta_title'] ?? 'مشاريعنا') . ' - ' . $siteName,
        'description'     => $cleanText($settings['projects_meta_description'] ?? 'معرض أعمالنا في الدهانات والديكور'),
        'numberOfItems'   => count($projectsListItems),
        'itemListElement' => $projectsListItems,
        'itemListOrder'   => 'https://schema.org/ItemListOrderDescending',
    ], true);

    $collectionId = $currentUrl . '#collection';
    $addToGraph([
        '@type'       => 'CollectionPage',
        '@id'         => $collectionId,
        'name'        => ($settings['projects_meta_title'] ?? 'مشاريعنا') . ' - ' . $siteName,
        'url'         => $currentUrl,
        'description' => $cleanText($settings['projects_meta_description'] ?? 'معرض أعمالنا ومشاريعنا السابقة في الدهانات والديكور'),
        'mainEntity'  => ['@id' => $itemListId],
        'isPartOf'    => ['@id' => $websiteId],
        'about'       => ['@id' => $orgId],
        'publisher'   => ['@id' => $orgId],
        'inLanguage'  => 'ar-SA',
    ]);
    $webPageMainEntity = ['@id' => $collectionId];

// ┌──────────────────────────────────────────────────────────┐
// │ 7.5 صفحة مشروع مفردة                                    │
// └──────────────────────────────────────────────────────────┘
} elseif (request()->routeIs('projects.show') && isset($project) && is_object($project)) {
    $webPageTypes         = ['ItemPage'];
    $webPageDatePublished = $project->created_at?->toIso8601String();
    $webPageDateModified  = $project->updated_at?->toIso8601String();
    $addBreadcrumb('المشاريع', route('projects.index'));
    // إضافة breadcrumb لـ الخدمة إن وُجدت
    if (!empty($project->service_id)) {
        $prjSvc = $project->service ?? null;
        if ($prjSvc && !empty($prjSvc->slug)) {
            $addBreadcrumb($cleanText($prjSvc->title ?? 'الخدمة'), route('services.show', $prjSvc->slug));
        }
    }
    $addBreadcrumb($cleanText($project->title), $currentUrl);

    $projectId         = $currentUrl . '#project';
    $webPageMainEntity = ['@id' => $projectId];
    $prjThumb          = !empty($project->main_image) ? asset('storage/' . $project->main_image) : $logoUrl;
    $prjBodyText       = $cleanText((string) ($project->description ?? ''));
    $prjWordCount      = count(array_filter(preg_split('/\s+/u', $prjBodyText) ?: [], fn ($w) => $w !== ''));
    $prjSection        = [];
    if (!empty($contentKeywords) && method_exists($contentKeywords, 'pluck')) {
        $firstSec = $contentKeywords->pluck('name')->filter()->first();
        if (is_string($firstSec) && $firstSec !== '') {
            $prjSection = [$cleanText($firstSec)];
        }
    }

    // جمع صور المشروع
    $prjImages = [];
    if (!empty($projectImages) && is_iterable($projectImages)) {
        foreach ($projectImages as $_img) {
            if (!is_object($_img) || empty($_img->image_path)) continue;
            $prjImages[] = asset('storage/' . $_img->image_path);
            if (count($prjImages) >= 10) break;
        }
    }

    $prjNode = [
        '@type'            => ['CreativeWork', 'Article'],
        '@id'              => $projectId,
        'isPartOf'         => ['@id' => $webPageId],
        'author'           => ['@id' => $authorId],
        'publisher'        => ['@id' => $orgId],
        'headline'         => Str::limit($cleanText($project->meta_title ?: $project->title), 110),
        'name'             => $cleanText($project->meta_title ?: $project->title),
        'dateCreated'      => $webPageDatePublished,
        'datePublished'    => $webPageDatePublished,
        'dateModified'     => $webPageDateModified,
        'mainEntityOfPage' => ['@id' => $webPageId],
        'image'            => !empty($prjImages) ? $prjImages : ['@id' => $currentUrl . '#primaryimage'],
        'thumbnailUrl'     => $prjThumb,
        'inLanguage'       => 'ar-SA',
        'description'      => $safeDesc($project->meta_description ?: $project->description ?? ''),
        'articleBody'      => $prjBodyText,
        'url'              => $currentUrl,
        'creator'         => ['@id' => $orgId],
        'locationCreated' => ['@type' => 'Place', 'name' => 'Jeddah, SA'],
    ];
    if (!empty($keywordsArray))    $prjNode['keywords']       = $keywordsArray;
    if (!empty($prjSection))       $prjNode['articleSection'] = $prjSection;
    if ($prjWordCount > 0)         $prjNode['wordCount']      = $prjWordCount;
    if (!empty($project->created_at)) {
        $prjNode['copyrightYear'] = $project->created_at->format('Y');
    }
    
    // إضافة بيانات الخدمة المرتبطة
    if (!empty($project->service_id) && !empty($project->service) && is_object($project->service)) {
        $prjNode['about'] = [
            '@type' => 'Service',
            'name'  => $cleanText($project->service->title ?? ''),
            'url'   => !empty($project->service->slug) ? route('services.show', $project->service->slug) : $siteUrl,
        ];
    }
    // location, scope, duration
    if (!empty($project->location)) {
        $prjNode['locationCreated'] = ['@type' => 'Place', 'name' => $cleanText($project->location)];
    }
    $addToGraph($prjNode, true);

    // مشاريع ذات صلة (بنفس نمط المقالات)
    if (!empty($sidebarProjects) && is_iterable($sidebarProjects)) {
        $related = [];
        foreach ($sidebarProjects as $_rp) {
            if (!is_object($_rp) || empty($_rp->slug) || $_rp->slug === $project->slug) continue;
            $related[] = [
                '@type'    => ['CreativeWork', 'Article'],
                'headline' => $cleanText($_rp->title ?? ''),
                'name'     => $cleanText($_rp->title ?? ''),
                'url'      => route('projects.show', $_rp->slug),
                'creator'  => ['@id' => $orgId],
            ];
            if (count($related) >= 5) break;
        }
        if (!empty($related)) {
            foreach ($schema['@graph'] as &$_prjRef) {
                if (($_prjRef['@id'] ?? null) === $projectId) {
                    $_prjRef['isRelatedTo'] = $related;
                    break;
                }
            }
            unset($_prjRef);
        }
    }

    // FAQ Schema للمشاريع
    if (!empty($project->faqs) && $project->faqs->count() > 0) {
        $faqItems = [];
        foreach ($project->faqs as $faq) {
            if (!is_object($faq)) continue;
            $faqItems[] = [
                '@type'          => 'Question',
                'name'           => $cleanText($faq->question ?? ''),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $cleanText($faq->answer ?? ''),
                ],
            ];
        }
        if (!empty($faqItems)) {
            $addToGraph([
                '@type'      => 'FAQPage',
                '@id'        => $currentUrl . '#faq',
                'mainEntity' => $faqItems,
                'isPartOf'   => ['@id' => $webPageId],
            ], true);
        }
    }

// ┌──────────────────────────────────────────────────────────┐
// │ 7.6 قائمة المدونة                                       │
// └──────────────────────────────────────────────────────────┘
} elseif (request()->routeIs('blog.index')) {
    $webPageTypes = ['CollectionPage'];
    $addBreadcrumb('المدونة', route('blog.index'));
    $webPageDateModified = $maxIsoFromModels($posts ?? null);

    $blogList      = [];
    $blogListItems = [];
    if (isset($posts) && is_iterable($posts)) {
        $pos = 1;
        foreach ($posts as $p) {
            if (!is_object($p)) continue;
            $url       = route('blog.show', $p->slug ?? $p->id);
            $published = $p->published_at?->toIso8601String() ?? $p->created_at?->toIso8601String();
            $modified  = $p->updated_at?->toIso8601String() ?? $published;

            $blogList[] = [
                '@type'         => 'BlogPosting',
                'headline'      => $cleanText($p->title ?? ''),
                'url'           => $url,
                'datePublished' => $published,
                'dateModified'  => $modified,
                'author'        => ['@id' => $authorId],
                'image'         => !empty($p->image_path) ? asset('storage/' . $p->image_path) : $logoUrl,
                'description'   => $safeDesc($p->meta_description ?: $p->content ?? ''),
            ];
            $blogListItems[] = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'url'      => $url,
                'name'     => $cleanText($p->title ?? ''),
                'item'     => [
                    '@type'         => 'BlogPosting',
                    'headline'      => $cleanText($p->title ?? ''),
                    'url'           => $url,
                    'datePublished' => $published,
                    'dateModified'  => $modified,
                    'author'        => ['@id' => $authorId],
                    'publisher'     => ['@id' => $orgId],
                    'image'         => !empty($p->image_path) ? asset('storage/' . $p->image_path) : $logoUrl,
                ],
            ];
        }
    }

    $itemListId = $currentUrl . '#itemlist';
    $addToGraph([
        '@type'           => 'ItemList',
        '@id'             => $itemListId,
        'name'            => ($settings['blog_meta_title'] ?? 'المدونة') . ' - ' . $siteName,
        'numberOfItems'   => count($blogListItems),
        'itemListElement' => $blogListItems,
        'itemListOrder'   => 'https://schema.org/ItemListOrderDescending',
    ], true);

    $collectionId = $currentUrl . '#collection';
    $addToGraph([
        '@type'       => 'CollectionPage',
        '@id'         => $collectionId,
        'name'        => ($settings['blog_meta_title'] ?? 'المدونة') . ' - ' . $siteName,
        'url'         => $currentUrl,
        'description' => $cleanText($settings['blog_meta_description'] ?? 'أحدث المقالات والنصائح في مجال الدهانات والديكور'),
        'mainEntity'  => ['@id' => $itemListId],
        'isPartOf'    => ['@id' => $websiteId],
        'publisher'   => ['@id' => $orgId],
        'inLanguage'  => 'ar-SA',
    ]);
    $webPageMainEntity = ['@id' => $collectionId];

    $addToGraph([
        '@type'       => 'Blog',
        '@id'         => $currentUrl . '#blog',
        'name'        => ($settings['blog_meta_title'] ?? 'المدونة') . ' - ' . $siteName,
        'url'         => $currentUrl,
        'description' => $cleanText($settings['blog_meta_description'] ?? 'أحدث المقالات والنصائح'),
        'author'      => ['@id' => $authorId],
        'publisher'   => ['@id' => $orgId],
        'inLanguage'  => 'ar-SA',
        'blogPost'    => $blogList,
        'isPartOf'    => ['@id' => $websiteId],
    ], true);

// ┌──────────────────────────────────────────────────────────┐
// │ 7.7 صفحة مقال مفردة                                     │
// └──────────────────────────────────────────────────────────┘
} elseif (request()->routeIs('blog.show') && isset($post) && is_object($post)) {
    $webPageTypes         = ['ItemPage'];
    $webPageDatePublished = $post->published_at?->toIso8601String() ?? $post->created_at?->toIso8601String();
    $webPageDateModified  = $post->updated_at?->toIso8601String() ?? $webPageDatePublished;
    $addBreadcrumb('المدونة', route('blog.index'));
    $addBreadcrumb($cleanText($post->title ?? ''), $currentUrl);

    $articleId         = $currentUrl . '#article';
    $webPageMainEntity = ['@id' => $articleId];

    $imageUrl  = !empty($post->image_path) ? asset('storage/' . $post->image_path) : $logoUrl;
    $bodyText  = $cleanText((string) ($post->content ?? ''));
    $wordCount = count(array_filter(preg_split('/\s+/u', $bodyText) ?: [], fn ($w) => $w !== ''));

    // articleSection من أول keyword
    $articleSection = [];
    if (!empty($contentKeywords) && method_exists($contentKeywords, 'pluck')) {
        $firstSec = $contentKeywords->pluck('name')->filter()->first();
        if (is_string($firstSec) && $firstSec !== '') {
            $articleSection = [$cleanText($firstSec)];
        }
    }

    $article = [
        '@type'            => ['Article', 'BlogPosting'],
        '@id'              => $articleId,
        'isPartOf'         => ['@id' => $webPageId],
        'author'           => ['@id' => $authorId],
        'publisher'        => ['@id' => $orgId],
        'headline'         => Str::limit($cleanText($post->meta_title ?: $post->title), 110),
        'name'             => $cleanText($post->meta_title ?: $post->title),
        'datePublished'    => $webPageDatePublished,
        'dateModified'     => $webPageDateModified,
        'mainEntityOfPage' => ['@id' => $webPageId],
        'image'            => ['@id' => $currentUrl . '#primaryimage'],
        'thumbnailUrl'     => $imageUrl,
        'inLanguage'       => 'ar-SA',
        'description'      => $safeDesc($post->meta_description ?: $bodyText),
        'articleBody'      => $bodyText,
        'url'              => $currentUrl,
    ];
    if (!empty($keywordsArray))    $article['keywords']       = $keywordsArray;
    if (!empty($articleSection))   $article['articleSection'] = $articleSection;
    if ($wordCount > 0)            $article['wordCount']      = $wordCount;
    if (!empty($post->published_at)) {
        $article['copyrightYear'] = $post->published_at->format('Y');
    }
    $addToGraph($article, true);

    // مقالات ذات صلة (recentPosts)
    if (!empty($recentPosts) && is_iterable($recentPosts)) {
        $related = [];
        foreach ($recentPosts as $_rp) {
            if (!is_object($_rp) || empty($_rp->slug)) continue;
            $related[] = [
                '@type'    => 'BlogPosting',
                'headline' => $cleanText($_rp->title ?? ''),
                'url'      => route('blog.show', $_rp->slug),
            ];
            if (count($related) >= 5) break;
        }
        if (!empty($related)) {
            foreach ($schema['@graph'] as &$_artRef) {
                if (($_artRef['@id'] ?? null) === $articleId) {
                    $_artRef['isRelatedTo'] = $related;
                    break;
                }
            }
            unset($_artRef);
        }
    }

    // FAQ Schema
    if (!empty($post->faqs) && $post->faqs->count() > 0) {
        $faqItems = [];
        foreach ($post->faqs as $faq) {
            if (!is_object($faq)) continue;
            $faqItems[] = [
                '@type'          => 'Question',
                'name'           => $cleanText($faq->question ?? ''),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $cleanText($faq->answer ?? ''),
                ],
            ];
        }
        if (!empty($faqItems)) {
            $addToGraph([
                '@type'      => 'FAQPage',
                '@id'        => $currentUrl . '#faq',
                'mainEntity' => $faqItems,
                'isPartOf'   => ['@id' => $webPageId],
            ], true);
        }
    }

// ┌──────────────────────────────────────────────────────────┐
// │ 7.8 صفحة الكلمة المفتاحية (keyword.show)                │
// └──────────────────────────────────────────────────────────┘
} elseif (request()->routeIs('keywords.show') && isset($keyword) && is_object($keyword)) {
    $webPageTypes = ['CollectionPage'];
    $addBreadcrumb($cleanText($keyword->name ?? ''), $currentUrl);

    $relatedItems  = [];
    $resolvedType  = isset($type) ? (string) $type : '';
    $pos           = 1;

    $kwItemsList = null;
    if (isset($items) && is_object($items) && method_exists($items, 'items')) {
        $kwItemsList = $items->items();
    }

    if (!empty($kwItemsList)) {
        foreach ($kwItemsList as $it) {
            if (!is_object($it)) continue;
            if ($resolvedType === 'services') {
                $relatedItems[] = ['@type' => 'ListItem', 'position' => $pos++, 'url' => isset($it->slug) ? route('services.show', $it->slug) : $currentUrl, 'name' => $cleanText($it->title ?? ''), 'item' => ['@type' => 'Service', 'name' => $cleanText($it->title ?? ''), 'url' => isset($it->slug) ? route('services.show', $it->slug) : $currentUrl, 'provider' => ['@id' => $orgId]]];
            } elseif ($resolvedType === 'projects') {
                $relatedItems[] = ['@type' => 'ListItem', 'position' => $pos++, 'url' => isset($it->slug) ? route('projects.show', $it->slug) : $currentUrl, 'name' => $cleanText($it->title ?? ''), 'item' => ['@type' => 'CreativeWork', 'name' => $cleanText($it->title ?? ''), 'url' => isset($it->slug) ? route('projects.show', $it->slug) : $currentUrl]];
            } elseif ($resolvedType === 'blog') {
                $relatedItems[] = ['@type' => 'ListItem', 'position' => $pos++, 'url' => isset($it->slug) ? route('blog.show', $it->slug) : $currentUrl, 'name' => $cleanText($it->title ?? ''), 'item' => ['@type' => 'BlogPosting', 'headline' => $cleanText($it->title ?? ''), 'url' => isset($it->slug) ? route('blog.show', $it->slug) : $currentUrl]];
            }
        }
    } else {
        // عرض كل الأنواع
        foreach (($services ?? []) as $svc) {
            if (!is_object($svc) || empty($svc->slug)) continue;
            $relatedItems[] = ['@type' => 'ListItem', 'position' => $pos++, 'url' => route('services.show', $svc->slug), 'name' => $cleanText($svc->title ?? ''), 'item' => ['@type' => 'Service', 'name' => $cleanText($svc->title ?? ''), 'url' => route('services.show', $svc->slug), 'provider' => ['@id' => $orgId]]];
        }
        foreach (($projects ?? []) as $prj) {
            if (!is_object($prj) || empty($prj->slug)) continue;
            $relatedItems[] = ['@type' => 'ListItem', 'position' => $pos++, 'url' => route('projects.show', $prj->slug), 'name' => $cleanText($prj->title ?? ''), 'item' => ['@type' => 'CreativeWork', 'name' => $cleanText($prj->title ?? ''), 'url' => route('projects.show', $prj->slug)]];
        }
        foreach (($posts ?? []) as $bp) {
            if (!is_object($bp) || empty($bp->slug)) continue;
            $relatedItems[] = ['@type' => 'ListItem', 'position' => $pos++, 'url' => route('blog.show', $bp->slug), 'name' => $cleanText($bp->title ?? ''), 'item' => ['@type' => 'BlogPosting', 'headline' => $cleanText($bp->title ?? ''), 'url' => route('blog.show', $bp->slug)]];
        }
    }

    $webPageDateModified = $maxIsoFromModels(
        !empty($kwItemsList) ? $kwItemsList
            : (is_iterable($services ?? null) ? ($services ?? [])
                : (is_iterable($projects ?? null) ? ($projects ?? []) : ($posts ?? null)))
    );

    $itemListId = $currentUrl . '#itemlist';
    $addToGraph([
        '@type'           => 'ItemList',
        '@id'             => $itemListId,
        'name'            => $cleanText($keyword->name ?? '') . ' - ' . $siteName,
        'numberOfItems'   => count($relatedItems),
        'itemListElement' => $relatedItems,
    ], true);

    $collectionId = $currentUrl . '#collection';
    $addToGraph([
        '@type'       => 'CollectionPage',
        '@id'         => $collectionId,
        'name'        => $cleanText($keyword->name ?? '') . ' - ' . $siteName,
        'url'         => $currentUrl,
        'description' => !empty($keyword->description)
            ? $safeDesc($keyword->description)
            : ('كل ما يتعلق بـ ' . $cleanText($keyword->name ?? '') . ' من خدمات ومشاريع ومقالات.'),
        'about'       => ['@type' => 'Thing', 'name' => $cleanText($keyword->name ?? '')],
        'mainEntity'  => ['@id' => $itemListId],
        'isPartOf'    => ['@id' => $websiteId],
        'publisher'   => ['@id' => $orgId],
        'inLanguage'  => 'ar-SA',
    ]);
    $webPageMainEntity = ['@id' => $collectionId];

// ┌──────────────────────────────────────────────────────────┐
// │ 7.9 صفحة «من نحن»                                       │
// └──────────────────────────────────────────────────────────┘
} elseif (request()->routeIs('about')) {
    $webPageTypes      = ['AboutPage'];
    $webPageMainEntity = ['@id' => $orgId];
    $addBreadcrumb('من نحن', route('about'));
    
    $aboutId = $currentUrl . '#about';
    // توضيح: إزالة التكرارات داخل كتلة «من نحن»
    // - isPartOf يجب أن يشير إلى Website مرة واحدة فقط
    // - publisher و inLanguage يضافان مرة واحدة فقط لعدم التكرار
    $addToGraph([
        '@type'            => ['AboutPage', 'Article'],
        '@id'              => $aboutId,
        'author'           => ['@id' => $authorId],
        'publisher'        => ['@id' => $orgId],
        'headline'         => Str::limit($cleanText($settings['about_meta_title'] ?? 'من نحن'), 110),
        'name'             => $cleanText($settings['about_meta_title'] ?? 'من نحن'),
        'mainEntityOfPage' => ['@id' => $webPageId], // يرتبط بكيان WebPage الحالي
        'image'            => ['@id' => $currentUrl . '#primaryimage'],
        'thumbnailUrl'     => $logoUrl,
        'inLanguage'       => 'ar-SA',
        'description'      => $cleanText($settings['about_meta_description'] ?? 'تعرف على أفضل معلم دهانات وديكورات في جدة'),
        'articleBody'      => $cleanText($settings['site_description'] ?? ''),
        'url'              => $currentUrl,
        'mainEntity'       => ['@id' => $orgId],      // الكيان الرئيسي: المؤسسة
        'isPartOf'         => ['@id' => $websiteId],  // ضمن الموقع ككل (Website)
    ], true);
    
    // FAQ Schema لصفحة من نحن
    if (!empty($settings['about_faqs']) && is_iterable($settings['about_faqs'])) {
        $faqItems = [];
        foreach ($settings['about_faqs'] as $faq) {
            if (!is_array($faq) || empty($faq['question'])) continue;
            $faqItems[] = [
                '@type'          => 'Question',
                'name'           => $cleanText($faq['question'] ?? ''),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $cleanText($faq['answer'] ?? ''),
                ],
            ];
        }
        if (!empty($faqItems)) {
            $addToGraph([
                '@type'      => 'FAQPage',
                '@id'        => $currentUrl . '#faq',
                'mainEntity' => $faqItems,
                'isPartOf'   => ['@id' => $webPageId],
            ], true);
        }
    }

// ┌──────────────────────────────────────────────────────────┐
// │ 7.10 صفحة «اتصل بنا»                                    │
// └──────────────────────────────────────────────────────────┘
} elseif (request()->routeIs('contact')) {
    $webPageTypes = ['ContactPage'];
    $addBreadcrumb('اتصل بنا', route('contact'));

    // بناء ContactPoint ككيان مستقل مع مُعرّف @id وإضافته إلى الرسم البياني
    $contactId = $currentUrl . '#contactpoint';
    $contactPoint = [
        '@type'             => 'ContactPoint',
        '@id'               => $contactId,
        'contactType'       => 'customer service',
        'areaServed'        => 'SA',
        'availableLanguage' => ['Arabic'],
    ];
    if (!empty($phone))        { $contactPoint['telephone'] = $phone; }
    if (!empty($whatsappUrl))  { $contactPoint['url']       = $whatsappUrl; }
    if (!empty($settings['email'])) { $contactPoint['email'] = (string) $settings['email']; }
    $addToGraph($contactPoint); // إضافة الكيان إلى @graph ليسهل ربطه

    // ضبط الكيانات الخاصة بصفحة الاتصال:
    // - mainEntity يشير إلى ContactPoint عبر @id
    $webPageMainEntity = ['@id' => $contactId, '@type' => 'ContactPoint'];
    $contactPageId = $currentUrl . '#contactpage';
    $addToGraph([
        '@type'            => ['ContactPage', 'Article'],
        '@id'              => $contactPageId,
        'author'           => ['@id' => $authorId],
        'publisher'        => ['@id' => $orgId],
        'headline'         => Str::limit($cleanText($settings['contact_meta_title'] ?? 'اتصل بنا'), 110),
        'name'             => $cleanText($settings['contact_meta_title'] ?? 'اتصل بنا'),
        'mainEntityOfPage' => ['@id' => $webPageId],
        'image'            => ['@id' => $currentUrl . '#primaryimage'],
        'thumbnailUrl'     => $logoUrl,
        'inLanguage'       => 'ar-SA',
        'description'      => $cleanText($settings['contact_meta_description'] ?? 'تواصل معنا لطلب خدمات الدهانات والديكور'),
        'articleBody'      => $cleanText($settings['contact_description'] ?? 'تواصل مع أفضل معلم دهانات وديكورات في جدة لطلب خدمات الدهانات والديكور'),
        'url'              => $currentUrl,
        'mainEntity'       => ['@id' => $contactId],
        'isPartOf'         => ['@id' => $websiteId],
    ], true);
    
    // FAQ Schema لصفحة اتصل بنا
    if (!empty($settings['contact_faqs']) && is_iterable($settings['contact_faqs'])) {
        $faqItems = [];
        foreach ($settings['contact_faqs'] as $faq) {
            if (!is_array($faq) || empty($faq['question'])) continue;
            $faqItems[] = [
                '@type'          => 'Question',
                'name'           => $cleanText($faq['question'] ?? ''),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $cleanText($faq['answer'] ?? ''),
                ],
            ];
        }
        if (!empty($faqItems)) {
            $addToGraph([
                '@type'      => 'FAQPage',
                '@id'        => $currentUrl . '#faq',
                'mainEntity' => $faqItems,
                'isPartOf'   => ['@id' => $webPageId],
            ], true);
        }
    }
}

// ──────────────────────────────────────────────────────────────
// 8. الصورة الرئيسية للصفحة (primaryimage)
// ──────────────────────────────────────────────────────────────

$pageImageUrl       = $logoUrl;
$pageImageLocalPath = $logoLocalPath;
$pageImageCaption   = $siteName;

if (request()->routeIs('blog.show') && isset($post) && is_object($post) && !empty($post->image_path)) {
    $pageImageUrl     = asset('storage/' . $post->image_path);
    $pageImageCaption = $cleanText($post->meta_title ?: $post->title ?? '');
    try {
        $p = \Illuminate\Support\Facades\Storage::disk('public')->path($post->image_path);
        $pageImageLocalPath = (is_string($p) && $p !== '' && file_exists($p)) ? $p : null;
    } catch (\Throwable) { $pageImageLocalPath = null; }

} elseif (request()->routeIs('services.show') && isset($service) && is_object($service) && !empty($service->image_path)) {
    $pageImageUrl     = asset('storage/' . $service->image_path);
    $pageImageCaption = $cleanText($service->meta_title ?: $service->title ?? '');
    try {
        $p = \Illuminate\Support\Facades\Storage::disk('public')->path($service->image_path);
        $pageImageLocalPath = (is_string($p) && $p !== '' && file_exists($p)) ? $p : null;
    } catch (\Throwable) { $pageImageLocalPath = null; }

} elseif (request()->routeIs('projects.show') && isset($project) && is_object($project) && !empty($project->main_image)) {
    $pageImageUrl     = asset('storage/' . $project->main_image);
    $pageImageCaption = $cleanText($project->meta_title ?: $project->title ?? '');
    try {
        $p = \Illuminate\Support\Facades\Storage::disk('public')->path($project->main_image);
        $pageImageLocalPath = (is_string($p) && $p !== '' && file_exists($p)) ? $p : null;
    } catch (\Throwable) { $pageImageLocalPath = null; }
}

$pagePrimaryImageId = $currentUrl . '#primaryimage';
$addToGraph($makeImageObject($pagePrimaryImageId, $pageImageUrl, $pageImageLocalPath, $pageImageCaption));

// ──────────────────────────────────────────────────────────────
// 9. WebPage الرئيسي
// ──────────────────────────────────────────────────────────────

// ملاحظة: لا نعيد تعيين about إذا تم ضبطه في أحد الفروع أعلاه
if (!isset($webPageAbout)) {
    $webPageAbout = ['@id' => $orgId]; // القيمة الافتراضية: المؤسسة
}

$webPage = [
    '@type'              => $webPageTypes,
    '@id'                => $webPageId,
    'url'                => $currentUrl,
    'name'               => !empty($meta_title ?? null)
                             ? $cleanText((string) $meta_title)
                             : $siteName,
    'description'        => $pageDescription ?: $siteDescription,
    'isPartOf'           => ['@id' => $websiteId],
    'about'              => $webPageAbout,
    'publisher'          => ['@id' => $orgId],
    'author'             => ['@id' => $authorId],
    'primaryImageOfPage' => ['@id' => $pagePrimaryImageId],
    'image'              => ['@id' => $pagePrimaryImageId],
    'thumbnailUrl'       => $pageImageUrl,
    'inLanguage'         => 'ar-SA',
    'potentialAction'    => [[
        '@type'  => 'ReadAction',
        'target' => [$currentUrl],
    ]],
];

if (!empty($webPageMainEntity))    $webPage['mainEntity']     = $webPageMainEntity;
if (!empty($webPageDatePublished)) $webPage['datePublished']  = $webPageDatePublished;
if (!empty($webPageDateModified))  $webPage['dateModified']   = $webPageDateModified;
if (!empty($webPageHasPartIds)) {
    $webPage['hasPart'] = array_map(fn($id) => ['@id' => $id], $webPageHasPartIds);
}

// ──────────────────────────────────────────────────────────────
// 10. BreadcrumbList
// ──────────────────────────────────────────────────────────────

if (count($breadcrumbs) > 1) {
    $breadcrumbId = $currentUrl . '#breadcrumb';
    $addToGraph([
        '@type'           => 'BreadcrumbList',
        '@id'             => $breadcrumbId,
        'itemListElement' => $breadcrumbs,
    ]);
    $webPage['breadcrumb'] = ['@id' => $breadcrumbId];
}

$addToGraph($webPage);

// تنظيف المتغيرات المؤقتة
unset($_kwExclude, $cleanText, $safeDesc);
@endphp
<script type="application/ld+json" class="yoast-schema-graph">
    <?php echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR); ?>
</script>
