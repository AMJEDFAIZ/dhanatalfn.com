@php
/*
|--------------------------------------------------------------------------
| بيانات أساسية مشتركة (Global Data)
|--------------------------------------------------------------------------
*/
$logo = isset($settings['site_logo']) && $settings['site_logo']
? asset('storage/' . $settings['site_logo'])
: asset('assets/img/logo.png');

$phone = $settings['phone'] ?? null;
$siteName = $settings['site_name'] ?? config('app.name');
$siteUrl = url('/');
$siteDescription = !empty($meta_description ?? null) ? $meta_description : ($settings['site_description'] ?? '');

// 1. Organization Schema
$organization = [
"@type" => "HomeAndConstructionBusiness", // نوع محدد لنشاط المقاولات والدهانات
"@id" => $siteUrl.'/#organization',
"name" => $siteName,
"url" => $siteUrl,
"logo" => [
"@type" => "ImageObject",
"url" => $logo,
"width" => 512, // أبعاد افتراضية للشعار
"height" => 512
],
"image" => $logo,
"description" => $siteDescription,
];

if ($phone) {
$organization["telephone"] = $phone;
}

if (!empty($settings['email'])) {
$organization["email"] = $settings['email'];
}

$organization["priceRange"] = "SAR"; // العملة

// Address
if (!empty($settings['address'])) {
$organization["address"] = [
"@type" => "PostalAddress",
"streetAddress" => $settings['address'],
"addressLocality" => 'جدة',
"addressRegion" => 'مكة المكرمة',
"addressCountry" => 'SA',
];
}

// Geo
// Always expose GeoCoordinates even if lat/lng are missing (defaults will be used)
$organization["geo"] = [
"@type" => "GeoCoordinates",
"latitude" => isset($settings['latitude']) && $settings['latitude'] !== '' ? (float)$settings['latitude'] : 21.567355,
"longitude" => isset($settings['longitude']) && $settings['longitude'] !== '' ? (float)$settings['longitude'] : 39.1925
];

// Opening Hours
if (!empty($settings['opens']) && !empty($settings['closes'])) {
$organization["openingHoursSpecification"] = [
[
"@type" => "OpeningHoursSpecification",
"dayOfWeek" => ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
"opens" => $settings['opens'],
"closes" => $settings['closes'],
]
];
}

// SameAs
$socials = array_values(array_filter([
$settings['whatsapp'] ?? null,
$settings['facebook'] ?? null,
$settings['instagram'] ?? null,
$settings['twitter'] ?? null,
$settings['youtube'] ?? null,
$settings['tiktok'] ?? null,
$settings['snapchat'] ?? null,
$settings['linkedin'] ?? null,
]));

if (!empty($socials)) {
$organization["sameAs"] = $socials;
}

// Contact Point
if ($phone) {
$organization["contactPoint"] = [
[
"@type" => "ContactPoint",
"telephone" => $phone,
"contactType" => "customer service",
"areaServed" => "Jeddah",
"availableLanguage" => ["Arabic"]
]
];
}

/*
|--------------------------------------------------------------------------
| تهيئة المخطط (Schema Initialization)
|--------------------------------------------------------------------------
*/
$schema = [
"@context" => "https://schema.org",
"@graph" => []
];

// دالة مساعدة لإضافة العناصر إلى المخطط
$addToGraph = function($item) use (&$schema) {
$schema['@graph'][] = $item;
};

// إضافة المؤسسة كعنصر أساسي في كل الصفحات
$addToGraph($organization);

// 2. BreadcrumbList Schema (مسار التنقل - يضاف ديناميكياً)
$breadcrumbs = [
[
"@type" => "ListItem",
"position" => 1,
"name" => "الرئيسية",
"item" => route('home')
]
];

$addBreadcrumb = function($name, $url) use (&$breadcrumbs) {
$breadcrumbs[] = [
"@type" => "ListItem",
"position" => count($breadcrumbs) + 1,
"name" => $name,
"item" => $url
];
};

/*
|--------------------------------------------------------------------------
| منطق الصفحات (Page Logic)
|--------------------------------------------------------------------------
*/

// --- الصفحة الرئيسية (Home) ---
if (request()->routeIs('home') || request()->is('/')) {

// WebSite Schema (مهم لتعريف الموقع لمحركات البحث)
$website = [
"@type" => "WebSite",
"@id" => $siteUrl.'/#website',
"url" => $siteUrl,
"name" => $siteName,
"description" => $siteDescription,
"publisher" => [
"@id" => $siteUrl.'/#organization'
],
"inLanguage" => "ar-SA"
];
$addToGraph($website);

// Reviews & AggregateRating (التقييمات)
if (isset($testimonials) && count($testimonials) > 0) {
$reviews = [];
$totalRating = 0;
foreach ($testimonials as $t) {
$reviews[] = [
"@type" => "Review",
"author" => [
"@type" => "Person",
"name" => $t->client_name
],
"datePublished" => $t->created_at->format('Y-m-d'),
"reviewRating" => [
"@type" => "Rating",
"ratingValue" => $t->rating,
"bestRating" => "5"
],
"reviewBody" => strip_tags($t->content)
];
$totalRating += $t->rating;
}

$avgRating = count($testimonials) > 0 ? $totalRating / count($testimonials) : 5;

// تحديث عنصر المؤسسة في المخطط لإضافة التقييمات
// بما أن المؤسسة هي العنصر الأول (index 0)
$schema['@graph'][0]['aggregateRating'] = [
"@type" => "AggregateRating",
"ratingValue" => number_format($avgRating, 1),
"reviewCount" => count($testimonials)
];
$schema['@graph'][0]['review'] = $reviews;
}
}

// --- صفحة الخدمات (Services List) ---
elseif (request()->routeIs('services.index')) {
$addBreadcrumb($settings['services_meta_title'] ?? "الخدمات", route('services.index'));

$servicesList = [];
if (isset($services)) {
$pos = 1;
foreach ($services as $svc) {
if (is_object($svc) && isset($svc->title)) {
$url = isset($svc->slug) ? route('services.show', $svc->slug) : url('/services/' . $svc->id);
$servicesList[] = [
"@type" => "Service",
"name" => $svc->title,
"url" => $url,
"position" => $pos++
];
}
}
}

$addToGraph([
"@type" => "CollectionPage",
"name" => ($settings['services_meta_title'] ?? "خدماتنا") . ' - ' . $siteName,
"url" => url()->current(),
"description" => $settings['services_meta_description'] ?? "تصفح جميع خدمات الدهانات والديكورات التي نقدمها في جدة",
"hasPart" => $servicesList
]);
}

// --- صفحة خدمة فردية (Service Detail) ---
elseif (request()->routeIs('services.show') && isset($service) && is_object($service)) {
$addBreadcrumb($settings['services_meta_title'] ?? "الخدمات", route('services.index'));
$addBreadcrumb($service->title, url()->current());

$addToGraph([
"@type" => "Service",
"name" => ($service->meta_title ?: $service->title) . ' - ' . $siteName,
"url" => url()->current(),
"description" => $service->meta_description ?: Str::limit(strip_tags($service->description ?? ''), 160),
"provider" => ["@id" => $siteUrl.'/#organization'],
"image" => !empty($service->image_path) ? asset('storage/' . $service->image_path) : $logo,
"areaServed" => [
"@type" => "City",
"name" => "Jeddah",
"sameAs" => "https://en.wikipedia.org/wiki/Jeddah"
],
"serviceType" => "Home Improvement"
]);
}

// --- صفحة المشاريع (Projects List) ---
elseif (request()->routeIs('projects.index')) {
$addBreadcrumb($settings['projects_meta_title'] ?? "المشاريع", route('projects.index'));

$projectsList = [];
if (isset($projects)) {
$pos = 1;
foreach ($projects as $prj) {
if (is_object($prj) && isset($prj->title)) {
$url = isset($prj->slug) ? route('projects.show', $prj->slug) : url('/projects/' . $prj->id);
$projectsList[] = [
"@type" => "CreativeWork",
"name" => $prj->title,
"url" => $url,
"position" => $pos++
];
}
}
}

$addToGraph([
"@type" => "CollectionPage",
"name" => ($settings['projects_meta_title'] ?? "مشاريعنا") . ' - ' . $siteName,
"url" => url()->current(),
"description" => $settings['projects_meta_description'] ?? "معرض أعمالنا ومشاريعنا السابقة في الدهانات والديكور",
"hasPart" => $projectsList
]);
}

// --- صفحة مشروع فردي (Project Detail) ---
elseif (request()->routeIs('projects.show') && isset($project) && is_object($project)) {
$addBreadcrumb($settings['projects_meta_title'] ?? "المشاريع", route('projects.index'));
$addBreadcrumb($project->title, url()->current());

$addToGraph([
"@type" => "CreativeWork",
"name" => ($project->meta_title ?: $project->title) . ' - ' . $siteName,
"url" => url()->current(),
"description" => $project->meta_description ?: Str::limit(strip_tags($project->description ?? ''), 160),
"creator" => ["@id" => $siteUrl.'/#organization'],
"image" => !empty($project->main_image) ? asset('storage/' . $project->main_image) : $logo,
"dateCreated" => $project->created_at->toIso8601String(),
"locationCreated" => [
"@type" => "Place",
"name" => "Jeddah"
]
]);
}

// --- المدونة (Blog List) ---
elseif (request()->routeIs('blog.index')) {
$addBreadcrumb($settings['blog_meta_title'] ?? "المدونة", route('blog.index'));

$blogList = [];
if (isset($posts)) {
foreach ($posts as $p) {
$blogList[] = [
"@type" => "BlogPosting",
"headline" => $p->title,
"url" => route('blog.show', $p->slug ?? $p->id),
"datePublished" => $p->published_at ? $p->published_at->toIso8601String() : $p->created_at->toIso8601String()
];
}
}

$addToGraph([
"@type" => "Blog",
"name" => ($settings['blog_meta_title'] ?? "المدونة") . ' - ' . $siteName,
"url" => url()->current(),
"description" => $settings['blog_meta_description'] ?? "اقرأ أحدث المقالات والنصائح في مجال المقاولات والبناء",
"blogPost" => $blogList
]);
}

// --- مقال فردي (Blog Post) ---
elseif (request()->routeIs('blog.show') && isset($post) && is_object($post)) {
$addBreadcrumb($settings['blog_meta_title'] ?? "المدونة", route('blog.index'));
$addBreadcrumb($post->title, url()->current());

$addToGraph([
"@type" => "BlogPosting",
"headline" => ($post->meta_title ?: $post->title) . ' - ' . $siteName,
"url" => url()->current(),
"datePublished" => $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String(),
"dateModified" => $post->updated_at->toIso8601String(),
"image" => !empty($post->image_path) ? asset('storage/' . $post->image_path) : $logo,
"author" => ["@id" => $siteUrl.'/#organization'],
"publisher" => ["@id" => $siteUrl.'/#organization'],
"description" => $post->meta_description ?: Str::limit(strip_tags($post->content ?? ''), 160),
"articleBody" => strip_tags($post->content ?? ''),
"mainEntityOfPage" => [
"@type" => "WebPage",
"@id" => url()->current()
]
]);

// FAQ Schema
if ($post->faqs && $post->faqs->count() > 0) {
$faqItems = [];
foreach ($post->faqs as $faq) {
$faqItems[] = [
"@type" => "Question",
"name" => $faq->question,
"acceptedAnswer" => [
"@type" => "Answer",
"text" => $faq->answer
]
];
}

$addToGraph([
"@type" => "FAQPage",
"mainEntity" => $faqItems
]);
}
}

elseif (request()->routeIs('keywords.show') && isset($keyword) && is_object($keyword)) {
$addBreadcrumb($keyword->name, url()->current());

$related = [];

if (isset($items) && is_object($items) && method_exists($items, 'items')) {
foreach ($items->items() as $it) {
if (is_object($it)) {
if (isset($it->image_path)) {
$related[] = [
"@type" => "Service",
"name" => $it->title ?? '',
"url" => isset($it->slug) ? route('services.show', $it->slug) : url()->current()
];
} elseif (isset($it->main_image)) {
$related[] = [
"@type" => "CreativeWork",
"name" => $it->title ?? '',
"url" => isset($it->slug) ? route('projects.show', $it->slug) : url()->current()
];
} else {
$related[] = [
"@type" => "BlogPosting",
"headline" => $it->title ?? '',
"url" => isset($it->slug) ? route('blog.show', $it->slug) : url()->current()
];
}
}
}
} else {
if (isset($services)) {
foreach ($services as $svc) {
if (is_object($svc) && isset($svc->slug)) {
$related[] = ["@type" => "Service", "name" => $svc->title ?? '', "url" => route('services.show', $svc->slug)];
}
}
}
if (isset($projects)) {
foreach ($projects as $prj) {
if (is_object($prj) && isset($prj->slug)) {
$related[] = ["@type" => "CreativeWork", "name" => $prj->title ?? '', "url" => route('projects.show', $prj->slug)];
}
}
}
if (isset($posts)) {
foreach ($posts as $bp) {
if (is_object($bp) && isset($bp->slug)) {
$related[] = ["@type" => "BlogPosting", "headline" => $bp->title ?? '', "url" => route('blog.show', $bp->slug)];
}
}
}
}

$addToGraph([
"@type" => "CollectionPage",
"name" => $keyword->name . ' - ' . $siteName,
"url" => url()->current(),
"description" => !empty($keyword->description) ? strip_tags((string) $keyword->description) : ('كل ما يتعلق بـ ' . $keyword->name . ' من خدمات ومشاريع ومقالات.'),
"about" => ["@type" => "Thing", "name" => $keyword->name],
"hasPart" => $related
]);
}

// --- صفحات ثابتة (Static Pages) ---
elseif (request()->routeIs('about')) {
$addBreadcrumb($settings['about_meta_title'] ?? "من نحن", route('about'));
$addToGraph([
"@type" => "AboutPage",
"name" => ($settings['about_meta_title'] ?? "من نحن") . ' - ' . $siteName,
"url" => url()->current(),
"description" => $settings['about_meta_description'] ?? "تعرف على أفضل معلم دهانات وديكورات في جدة",
"mainEntity" => ["@id" => $siteUrl.'/#organization']
]);
}
elseif (request()->routeIs('contact')) {
$addBreadcrumb($settings['contact_meta_title'] ?? "اتصل بنا", route('contact'));
$addToGraph([
"@type" => "ContactPage",
"name" => ($settings['contact_meta_title'] ?? "اتصل بنا") . ' - ' . $siteName,
"url" => url()->current(),
"description" => $settings['contact_meta_description'] ?? "تواصل معنا لطلب خدمات الدهانات والديكور",
"mainEntity" => [
"@type" => "ContactPoint",
"telephone" => $phone,
"contactType" => "customer service"
]
]);
}

// 3. إضافة BreadcrumbList إلى المخطط النهائي
if (count($breadcrumbs) > 1) {
$addToGraph([
"@type" => "BreadcrumbList",
"itemListElement" => $breadcrumbs
]);
}
@endphp

<script type="application/ld+json">
    {!!json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)!!}
</script>