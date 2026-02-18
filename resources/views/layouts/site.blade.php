<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth overflow-x-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- {{$meta_title ?? null ? $meta_title .' | '. ($settings['site_name'] ?? config('app.name', 'أفضل معلم دهانات وديكورات جدة')) : $settings['site_name'] ?? config('app.name', ' أفضل معلم دهانات وديكورات في جدة ') .' | '. ($page_title ?? 'الصفحة الرئيسية')}} --}}
    @php
    $sectionTitle = trim($__env->yieldContent('title'));
    $sectionMetaTitle = trim($__env->yieldContent('meta_title'));
    $sectionMetaDescription = trim($__env->yieldContent('meta_description'));
    $sectionMetaKeywords = trim($__env->yieldContent('meta_keywords'));
    $sectionMetaImage = trim($__env->yieldContent('meta_image'));

    $resolvedMetaTitle =
    $meta_title ??
    ($sectionMetaTitle !== '' ? $sectionMetaTitle : ($sectionTitle !== '' ? $sectionTitle : null));
    $resolvedPageTitle = $page_title ?? ($sectionTitle !== '' ? $sectionTitle : null);

    $resolvedMetaDescription =
    $meta_description ?? ($sectionMetaDescription !== '' ? $sectionMetaDescription : null);
    $resolvedMetaDescription = !empty($resolvedMetaDescription)
    ? preg_replace('/\s+/u', ' ', strip_tags((string) $resolvedMetaDescription))
    : null;

    if (!empty($meta_keywords ?? null)) {
    $resolvedMetaKeywords = is_array($meta_keywords) ? implode(', ', $meta_keywords) : (string) $meta_keywords;
    } elseif ($sectionMetaKeywords !== '') {
    $resolvedMetaKeywords = (string) $sectionMetaKeywords;
    } else {
    $resolvedMetaKeywords = null;
    }
    $resolvedMetaKeywords = !empty($resolvedMetaKeywords)
    ? preg_replace('/\s+/u', ' ', strip_tags((string) $resolvedMetaKeywords))
    : null;

    $meta_title = $resolvedMetaTitle;
    $meta_description = $resolvedMetaDescription;
    $meta_keywords = $resolvedMetaKeywords;
    @endphp
    <title>
        {{ $resolvedMetaTitle ? $resolvedMetaTitle . ' | ' . ($settings['site_name'] ?? config('app.name')) : ($settings['site_name'] ?? config('app.name')) . ' | ' . ($resolvedPageTitle ?? 'الصفحة الرئيسية') }}
    </title>
    @if (!empty($resolvedMetaDescription))
    <meta name="description" content="{{ Str::limit($resolvedMetaDescription, 160) }}">
    @endif
    @if (!empty($resolvedMetaKeywords))
    <meta name="keywords" content="{{ $resolvedMetaKeywords }}">
    @endif

    @php
    $canonicalUrl = url()->current();
    $query = request()->query();
    $canonicalParams = array_intersect_key($query, array_flip(['page']));
    if (!empty($canonicalParams) && count($canonicalParams) === count($query)) {
    $canonicalUrl = url()->current() . '?' . http_build_query($canonicalParams);
    }

    $robotsContent = 'index,follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large';
    if (request()->routeIs('blog.index') && request()->filled('search')) {
    $robotsContent = 'noindex, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large';
    }
    if (!empty($robots_noindex ?? null)) {
    $robotsContent = 'noindex, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large';
    }
    @endphp

    <meta name="author"
        content="{{ $settings['site_author'] ?? ($settings['site_name'] ?? 'معلم دهانات وديكورات جدة ت: 0532791522') }}">
    {{-- <meta name="robots" content="index, follow"> --}}
    <meta name="robots" content="{{ $robotsContent }}">
    <meta name="googlebot" content="{{ $robotsContent }}">
    <meta name="bingbot" content="{{ $robotsContent }}">
    <meta name="theme-color" content="#0A192F">

    {{-- ===== Canonical (مهم جدًا للسيو) ===== --}}
    {{-- <!--<link rel="canonical" href="{{ url()->current() }}">--> --}}
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="ar-SA" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}">

    <meta name="google-site-verification" content="_lMgioCLkmTGQmIVOxTCzpYviw6IC71fpk3xgCBxvXU" />

    <meta property="og:title" content="{{ $resolvedMetaTitle ?? ($settings['site_name'] ?? config('app.name')) }}">


    @if (!empty($resolvedMetaDescription))
    <meta property="og:description" content="{{ Str::limit($resolvedMetaDescription, 160) }}">
    @endif


    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="{{ request()->routeIs('blog.show') ? 'article' : 'website' }}">

    @if (request()->routeIs('blog.show') && isset($post) && is_object($post))
    @php
    $publishedAt = $post->published_at ?? $post->created_at ?? null;
    $publishedIso = $publishedAt ? $publishedAt->toIso8601String() : null;
    $modifiedIso = ($post->updated_at ?? null) ? $post->updated_at->toIso8601String() : null;
    @endphp
    @if (!empty($publishedIso))
    <meta property="article:published_time" content="{{ $publishedIso }}">
    @endif
    @if (!empty($modifiedIso))
    <meta property="article:modified_time" content="{{ $modifiedIso }}">
    @endif
    @if (!empty($modifiedIso))
    <meta property="og:updated_time" content="{{ $modifiedIso }}">
    @endif
    @if (isset($contentKeywords) && $contentKeywords && method_exists($contentKeywords, 'pluck'))
    @foreach ($contentKeywords->pluck('name')->filter()->unique() as $tag)
    <meta property="article:tag" content="{{ $tag }}">
    @endforeach
    @endif
    @endif

    @php
    $siteLogoImage =
    isset($settings['site_logo']) && $settings['site_logo']
    ? asset('storage/' . $settings['site_logo'])
    : asset('assets/img/logo.png');

    $resolvedMetaImage = $meta_image ?? ($sectionMetaImage !== '' ? $sectionMetaImage : null);
    if (!empty($resolvedMetaImage) && is_string($resolvedMetaImage)) {
    if (Str::startsWith($resolvedMetaImage, ['http://', 'https://'])) {
    $resolvedMetaImage = $resolvedMetaImage;
    } elseif (Str::startsWith($resolvedMetaImage, '/')) {
    $resolvedMetaImage = url($resolvedMetaImage);
    } else {
    $resolvedMetaImage = asset($resolvedMetaImage);
    }
    }

    $primaryOgImage = $siteLogoImage;

    if (!empty($resolvedMetaImage)) {
    $primaryOgImage = $resolvedMetaImage;
    } elseif (request()->routeIs('blog.show') && isset($post) && is_object($post) && !empty($post->image_path ?? null)) {
    $primaryOgImage = asset('storage/' . $post->image_path);
    } elseif (request()->routeIs('services.show') && isset($service) && is_object($service) && !empty($service->image_path ?? null)) {
    $primaryOgImage = asset('storage/' . $service->image_path);
    } elseif (request()->routeIs('projects.show') && isset($project) && is_object($project) && !empty($project->main_image ?? null)) {
    $primaryOgImage = asset('storage/' . $project->main_image);
    }

    $fallbackOgImage = $primaryOgImage !== $siteLogoImage ? $siteLogoImage : null;

    $resolveOgImageType = function (?string $url): ?string {
    if (empty($url)) return null;
    $path = parse_url($url, PHP_URL_PATH) ?: $url;
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    return match ($ext) {
    'webp' => 'image/webp',
    'png' => 'image/png',
    'jpg', 'jpeg' => 'image/jpeg',
    'gif' => 'image/gif',
    default => null,
    };
    };

    $primaryOgImageType = $resolveOgImageType($primaryOgImage);
    $fallbackOgImageType = $resolveOgImageType($fallbackOgImage);
    @endphp
    <meta property="og:image" content="{{ $primaryOgImage }}">
    <meta property="og:image:secure_url" content="{{ $primaryOgImage }}">
    @if (!empty($primaryOgImageType))
    <meta property="og:image:type" content="{{ $primaryOgImageType }}">
    @endif
    <meta property="og:image:alt" content="{{ $resolvedMetaTitle ?? ($settings['site_name'] ?? config('app.name')) }}">
    @if (!empty($fallbackOgImage))
    <meta property="og:image" content="{{ $fallbackOgImage }}">
    <meta property="og:image:secure_url" content="{{ $fallbackOgImage }}">
    @if (!empty($fallbackOgImageType))
    <meta property="og:image:type" content="{{ $fallbackOgImageType }}">
    @endif
    <meta property="og:image:alt" content="{{ $settings['site_name'] ?? config('app.name') }}">
    @endif

    <meta property="og:locale" content="ar_SA">
    <meta property="og:site_name" content="{{ $settings['site_name'] ?? config('app.name') }}">
    {{-- ===== Twitter Cards (ضروري لمنصة X) ===== --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $resolvedMetaTitle ?? ($settings['site_name'] ?? config('app.name')) }}">
    @if (!empty($resolvedMetaDescription))
    <meta name="twitter:description" content="{{ Str::limit($resolvedMetaDescription, 160) }}">
    @endif

    <meta name="twitter:image" content="{{ $primaryOgImage }}">
    <meta name="twitter:image:alt" content="{{ $resolvedMetaTitle ?? ($settings['site_name'] ?? config('app.name')) }}">

    <link rel="icon" type="image/x-icon"
        href="{{ isset($settings['site_favicon']) ? asset('storage/' . $settings['site_favicon']) : asset('assets/img/favicon.ico') }}">


    {{-- ===== Apple (iPhone) ===== --}}
    <link rel="apple-touch-icon" href="{{ asset('assets/img/icon.png') }}">

    {{-- {{ asset('storage/' . $settings['site_favicon']) }} --}}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @if (request()->routeIs('home'))
    <link rel="preload" as="image" href="{{ asset('assets/img/hero.webp') }}"
        imagesrcset="{{ asset('assets/img/hero11.webp') }} 640w, {{ asset('assets/img/hero.webp') }} 1600w"
        imagesizes="100vw" fetchpriority="high" type="image/webp">
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons --> {{-- https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <!-- Tailwind CSS -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    <style>
        html {
            scrollbar-gutter: stable;
        }

        /* تأثير نبض خفيف للزر الرئيسي */
        /* نبض خفيف عاماً (يمكن وضعه في ملف CSS مركزي) */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, .18);
                /* blue-500 with alpha */
            }

            70% {
                box-shadow: 0 0 0 18px rgba(59, 130, 246, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        /* تأخيرات بسيطة لكل زر ليعطي تأثير متدرج */
        .pulse-delay-1 {
            animation-delay: 0s;
        }

        .pulse-delay-2 {
            animation-delay: 0.08s;
        }

        .pulse-delay-3 {
            animation-delay: 0.16s;
        }

        .pulse-delay-4 {
            animation-delay: 0.24s;
        }

        /* خفف ظل التحريك على اللمس */
        @media (hover: none) {
            .contact-btn:hover {
                transform: none;
            }
        }
    </style>
    {{-- ===== تحسين الأداء ===== --}}
    <link rel="preload" href="{{ asset('assets/css/style.css') }}" as="style">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    {{-- Sitemap --}}
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ asset('sitemap.xml') }}">

    @include('partials.schema')
</head>

<body class="bg-gray-50 text-gray-800 font-sans overflow-x-hidden selection:bg-accent selection:text-white">
    {{-- <div class="min-h-screen flex flex-col"> --}}
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:right-4 focus:z-[100] focus:bg-white focus:text-primary focus:px-4 focus:py-2 focus:rounded-lg focus:shadow-lg">
        تخطي إلى المحتوى
    </a>
    @include('partials.header')

    {{-- <main class="flex-grow"> --}}
    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>
    {{-- </main> --}}

    @include('partials.footer')
    {{-- </div> --}}





    <!-- ============================= -->
    <!-- زر التواصل العائم الرئيسي -->
    <!-- ============================= -->

    <div class="fixed right-6 bottom-6 z-50 flex flex-col items-end gap-3 contact-wrapper">
        <!-- زر واتساب -->
        @if (isset($settings['whatsapp']))
        <a href="{{ $settings['whatsapp'] }}" target="_blank" rel="noopener noreferrer" aria-label="واتساب"
            title="واتساب"
            class="contact-btn pulse pulse-delay-1 flex items-center justify-center bg-green-500 text-white rounded-full shadow-lg
                  w-12 h-12 md:w-14 md:h-14 transition-transform transform hover:scale-105
                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400">
            <i class="fa-brands fa-whatsapp text-lg md:text-xl" aria-hidden="true"></i>
        </a>
        @endif

        <!-- زر اتصال هاتفي -->
        @if (isset($settings['phone']))
        <a href="tel:{{ $settings['phone'] }}" aria-label="اتصال هاتفي" title="اتصال"
            class="contact-btn pulse pulse-delay-2 flex items-center justify-center bg-primary text-white rounded-full shadow-lg
                  w-12 h-12 md:w-14 md:h-14 transition-transform transform hover:scale-105
                  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/60">
            <i class="fa-solid fa-phone text-lg md:text-xl" aria-hidden="true"></i>
        </a>
        @endif

        <!-- زر إيميل (اختياري) -->
        <!--@if (isset($settings['email']))
-->
        <!--    <a href="mailto:{{ $settings['email'] }}" target="_blank" rel="noopener noreferrer"-->
        <!--       aria-label="بريد إلكتروني" title="بريد إلكتروني"-->
        <!--       class="contact-btn pulse pulse-delay-3 flex items-center justify-center bg-sky-600 text-white rounded-full shadow-lg-->
        <!--              w-12 h-12 md:w-14 md:h-14 transition-transform transform hover:scale-105-->
        <!--              focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400">-->
        <!--        <i class="fa-solid fa-envelope text-lg md:text-xl" aria-hidden="true"></i>-->
        <!--    </a>-->
        <!--
@endif-->

        <!-- أي أزرار إضافية ضعها هنا مع pulse-delay-4 ... -->
    </div>
    <!-- زر العودة للأعلى (كما كان) -->
    <button id="scrollTopBtn"
        class="hidden fixed bottom-6 left-6 z-50 w-12 h-12 bg-primary hover:bg-accent text-white rounded-full shadow-lg flex items-center justify-center transition transform hover:-translate-y-1"
        aria-label="العودة للأعلى" title="عودة للأعلى">
        <i class="fa-solid fa-arrow-up"></i>
    </button>


    <!-- Lightbox -->
    <div id="lightbox"
        class="fixed inset-0 z-[60] bg-black/95 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center">
        <!-- Controls -->
        <div class="absolute top-6 right-6 flex items-center gap-4 z-50">
            <button id="lightbox-zoom"
                class="text-white text-2xl hover:text-accent transition-colors focus:outline-none"
                title="تكبير/تصغير"><i class="fa-solid fa-magnifying-glass-plus"></i></button>
            <button id="lightbox-close"
                class="text-white text-4xl hover:text-accent transition-colors focus:outline-none" title="إغلاق"><i
                    class="fa-solid fa-times"></i></button>
        </div>

        <button id="lightbox-prev"
            class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-4xl hover:text-accent transition-colors p-4 focus:outline-none bg-black/20 hover:bg-black/40 rounded-full w-12 h-12 flex items-center justify-center z-10"><i
                class="fa-solid fa-chevron-left"></i></button>
        <button id="lightbox-next"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-4xl hover:text-accent transition-colors p-4 focus:outline-none bg-black/20 hover:bg-black/40 rounded-full w-12 h-12 flex items-center justify-center z-10"><i
                class="fa-solid fa-chevron-right"></i></button>

        <!-- Image Container -->
        <div class="relative max-h-[90vh] max-w-[90vw] overflow-hidden flex flex-col items-center">
            <img id="lightbox-img" src="" alt="Full size"
                class="max-h-[85vh] max-w-full object-contain transform scale-95 transition-transform duration-500 select-none cursor-grab">
            <p id="lightbox-caption"
                class="text-white text-center mt-4 text-lg font-medium opacity-0 transition-opacity duration-300 transform translate-y-2">
            </p>
        </div>
    </div>

    <!-- ============================= -->
    <!-- JavaScript -->
    <!-- ============================= -->
    <script>
        // التحكم بقائمة التواصل
        // زر العودة للأعلى
        const scrollTopBtn = document.getElementById("scrollTopBtn");
        window.addEventListener("scroll", () => {
            if (!scrollTopBtn) return;
            if (window.scrollY > 300) {
                scrollTopBtn.classList.remove("hidden");
            } else {
                scrollTopBtn.classList.add("hidden");
            }
        }, {
            passive: true
        });

        if (scrollTopBtn) scrollTopBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // ملاحظة: لا يوجد أي كود لإظهار/إخفاء أزرار التواصل — فهي دائمة الظهور كما طلبت.
    </script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>