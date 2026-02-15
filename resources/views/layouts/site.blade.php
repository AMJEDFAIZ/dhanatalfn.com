<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth overflow-x-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <!--         {{$meta_title ?? null ? $meta_title .' | '. ($settings['site_name'] ?? config('app.name', 'أفضل معلم دهانات وديكورات جدة')) : $settings['site_name'] ?? config('app.name', ' أفضل معلم دهانات وديكورات في جدة ') .' | '. ($page_title ?? 'الصفحة الرئيسية')}} --> --}}
    <title>
        {{$meta_title ?? null ? $meta_title .' | '. ($settings['site_name'] ?? config('app.name')) : ($settings['site_name'] ?? config('app.name')) .' | '. ($page_title ?? 'الصفحة الرئيسية')}}
    </title>
    @if (!empty($meta_description ?? null))
    <meta name="description" content="{{Str::limit(strip_tags($meta_description ?? ''), 160)}}"> {{-- تأكد من أن الوصف لا يتجاوز 160 حرفًا --}}
    @endif
    @if (!empty($meta_keywords ?? null))
    <meta name="keywords" content="{{ is_array($meta_keywords) ? implode(', ', $meta_keywords) : $meta_keywords }}">
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
    @endphp

    <meta name="author" content="{{ 'معلم دهانات وديكورات جدة ت: 0532791522' ?? $settings['site_name']  }}">
    {{-- <meta name="robots" content="index, follow"> --}}
    <meta name="robots" content="{{ $robotsContent }}">
    <meta name="theme-color" content="#0A192F">

    {{-- ===== Canonical (مهم جدًا للسيو) ===== --}}
    {{-- <!--<link rel="canonical" href="{{ url()->current() }}">--> --}}
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta name="google-site-verification" content="_lMgioCLkmTGQmIVOxTCzpYviw6IC71fpk3xgCBxvXU" />

    <meta property="og:title" content="{{ $meta_title ?? ($settings['site_name'] ?? config('app.name')) }}">


    @if (!empty($meta_description ?? null))
    <meta property="og:description" content="{{ $meta_description }}">
    @endif


    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="website">

    @if (isset($settings['site_logo']))
    <meta property="og:image" content="{{ asset('storage/' . $settings['site_logo']) }}">
    @else
    <meta property="og:image" content="{{ asset('assets/img/icon.PNG') }}">
    @endif

    <meta property="og:locale" content="ar_AR">
    <meta property="og:site_name" content="{{ $settings['site_name'] ?? config('app.name') }}">
    {{-- ===== Twitter Cards (ضروري لمنصة X) ===== --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta_title ?? ($settings['site_name'] ?? config('app.name')) }}">
    @if (!empty($meta_description ?? null))
    <meta name="twitter:description" content="{{ $meta_description }}">
    @endif

    @if (isset($settings['site_logo']))
    <meta name="twitter:image" content="{{ asset('storage/' . $settings['site_logo']) }}">
    @endif

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
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons --> {{-- https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/fontawesome.min.css">
    <!-- Tailwind CSS -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    <style>
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
    @include('partials.header')

    {{-- <main class="flex-grow"> --}}
    @yield('content')
    {{-- </main> --}}

    @include('partials.footer')
    {{-- </div> --}}





    <!-- ============================= -->
    <!-- زر التواصل العائم الرئيسي -->
    <!-- ============================= -->

    <div class="fixed right-6 bottom-6 z-50 flex flex-col items-end gap-3 contact-wrapper">
        <!-- زر واتساب -->
        @if (isset($settings['whatsapp']))
        <a href="{{ $settings['whatsapp'] }}" target="_blank" rel="noopener noreferrer"
            aria-label="واتساب" title="واتساب"
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
        <!--@if (isset($settings['email']))-->
        <!--    <a href="mailto:{{ $settings['email'] }}" target="_blank" rel="noopener noreferrer"-->
        <!--       aria-label="بريد إلكتروني" title="بريد إلكتروني"-->
        <!--       class="contact-btn pulse pulse-delay-3 flex items-center justify-center bg-sky-600 text-white rounded-full shadow-lg-->
        <!--              w-12 h-12 md:w-14 md:h-14 transition-transform transform hover:scale-105-->
        <!--              focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400">-->
        <!--        <i class="fa-solid fa-envelope text-lg md:text-xl" aria-hidden="true"></i>-->
        <!--    </a>-->
        <!--@endif-->

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
            if (window.scrollY > 300) {
                scrollTopBtn.classList.remove("hidden");
            } else {
                scrollTopBtn.classList.add("hidden");
            }
        });

        scrollTopBtn.addEventListener("click", () => {
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