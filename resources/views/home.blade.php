@extends('layouts.site')

@section('meta_description', $settings['site_description'] ?? null)
@section('meta_keywords',
collect([$settings['site_name'] ?? null, 'دهانات', 'ديكورات', 'الفن الحديث', ' معلم دهانات وديكورات', 'معلم دهانات جدة',
'معلم دهانات'])->filter()->implode(', '))

@section('content')
<!-- Hero Section -->
<section id="home" class="relative min-h-[max(100svh,600px)] pt-24 flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/img/hero.webp') }}"
            srcset="{{ asset('assets/img/hero11.webp') }} 640w, {{ asset('assets/img/hero.webp') }} 1600w"
            sizes="100vw" loading="eager" fetchpriority="high" decoding="async" alt="خلفية ديكور"
            class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/80 to-primary/40 mix-blend-multiply"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 text-center text-white">
        <span
            class="inline-block py-1 px-3 rounded-full bg-accent/20 border border-accent/50 text-accent text-lg font-semibold mb-4  animate-fade-in-up">
            أفضل معلم دهانات وديكورات في جدة</span>
        <h1
            class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight animate-fade-in-up animation-delay-200">
            معلم دهانات<br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-white">وديكورات
                جدة</span>
        </h1>
        <p class="text-lg font-bold md:text-xl text-white max-w-2xl mx-auto mb-10 animate-fade-in-up animation-delay-400">
            <span class="text-accent font-bold">أفضل معلم دهانات وديكورات جدة </span>المتخصص في تقديم أفضل خدمات
            الدهانات والتشطيبات والديكور في <span class="font-bold">جدة – حي الروضة </span>والمناطق المجاورة. نحن نقدم
            حلولاً شاملة لتجديد المنازل والفلل والمكاتب، من دهانات داخلية وخارجية عالية الجودة إلى تصميمات جبس بورد
            العصرية وديكورات بديل الخشب والرخام وورق الجدران.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-600">
            <a href="{{ route('projects.index') }}"
                class="bg-accent hover:bg-yellow-600 text-white px-8 py-4 rounded-lg font-bold transition-all shadow-lg hover:shadow-accent/50 transform hover:-translate-y-1 flex items-center justify-center gap-3">
                <i class="fa-solid fa-paint-roller bold "></i>
                أعمالنا
            </a>
            <a href="{{ route('contact') }}"
                class="bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/30 text-white px-8 py-4 rounded-lg font-bold transition-all hover:shadow-lg transform hover:-translate-y-1 flex items-center justify-center gap-2">
                <i class="fa-solid fa-phone"></i>
                تواصل معنا
            </a>
        </div>

        <!-- السهم المتحرك أسفل الأزرار -->
        <div class="mt-8 animate-bounce">
            <a href="#about" class="text-white/70 hover:text-white transition-colors" aria-label="النزول إلى قسم من نحن">
                <i class="fa-solid fa-chevron-down text-2xl"></i>
            </a>
        </div>

    </div>
</section>



<!-- About Section -->
<section id="about" class="py-20 bg-gray-50 scroll-mt-28">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 relative reveal">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl border-r-4 border-accent2">
                    <img src="{{ asset('assets/img/about.webp') }}" loading="lazy"
                        alt="معلم دهانات وديكورات جدة ت: 0532791522"
                        class="w-full h-auto object-cover transform hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-primary/20 hover:bg-transparent transition-colors duration-300">
                    </div>
                </div>

                <div class="absolute -bottom-6 -left-6  w-24 h-24 bg-dots-pattern opacity-70 "></div>
            </div>
            <div class="w-full lg:w-1/2 reveal">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-12 h-1 bg-accent2 rounded-full bold"></div>
                    <span class="text-accent font-bold uppercase tracking-wider">من نحن</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-6">شريكك الموثوق في الدهان والديكور</h2>
                <p class="text-gray-600 leading-relaxed mb-6 text-lg">
                    <!--<span class="text-accent font-bold">معلم دهانات وديكورات جدة </span>-->
                    <!--<span class="font-bold">حي الروضة – جدة </span>-->

                    <span class="text-accent font-bold">معلم دهانات وديكورات جدة </span>المتخصص في تقديم أفضل خدمات
                    الدهانات والتشطيبات والديكور في <span class="font-bold">حي الروضة – جدة </span>والمناطق المجاورة.
                    نحن نقدم حلولاً شاملة لتجديد المنازل والفلل والمكاتب، من دهانات داخلية وخارجية عالية الجودة إلى
                    تصميمات جبس بورد العصرية وديكورات بديل الخشب والرخام وورق الجدران. نقوم باستخدام أفضل أنواع الدهانات
                    المقاومة للرطوبة سهلة التنظيف تحت إشراف فنيين محترفين يضمنون نتائج دقيقة ولون موحد وملمس ناعم.
                    يسعدنا أن نساعدكم في تحويل منازلكم إلى مساحات أنيقة وعصرية تعكس شخصيتكم، وذلك عبر خبرتنا الطويلة
                    وجودة العمل الممتازة.

                </p>


                <div class="border-r-4 border-accent pr-6 mb-8 bg-gray-50 p-4 rounded-r-lg">
                    <p class="text-primary font-bold italic text-lg">
                        "معلم دهانات وديكورات حي الروضه بجدة ، لأعمال الدهان والديكور."
                    </p>
                    <span class="block mt-2 text-sm text-gray-500">- المعلم احمد</span>
                </div>

                <ul class="space-y-4 mb-8">
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-check-circle text-accent text-xl"></i>
                        <span class="text-gray-700 font-medium">خبرة تزيد عن 8 سنوات.</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-check-circle text-accent text-xl"></i>
                        <span class="text-gray-700 font-medium">فريق من أفضل فنيين الدهانات الديكور.</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-check-circle text-accent text-xl"></i>
                        <span class="text-gray-700 font-medium">نقدم تصاميم حديثة ومبتكرة.</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-check-circle text-accent text-xl"></i>
                        <span class="text-gray-700 font-medium">أسعار تناسب الجميع.</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-check-circle text-accent text-xl"></i>
                        <span class="text-gray-700 font-medium">نهتم بجميع التفاصيل والجودة.</span>
                    </li>
                </ul>

                <a href="{{ route('about') }}"
                    class="inline-flex items-center gap-2 text-primary font-bold hover:text-accent transition-colors group">
                    اعرف المزيد عنا
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-20 bg-white animate-fade-in-up">
    <div class="container mx-auto px-4 ">
        <div class="text-center mb-16 reveal">
            <span class="text-accent font-bold uppercase tracking-wider block mb-2">ماذا نقدم</span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary">خدماتنا المتميزة</h2>
            <div class="w-20 h-1 bg-accent mx-auto mt-4 rounded-full"></div>
        </div>

        @if ($services->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in-up md-10">
            @foreach ($services as $index => $service)
            <div class="portfolio-item group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 reveal border border-gray-100 delay-100"
                data-aos-delay="{{ $index * 100 }}">

                <div class="relative h-64 overflow-hidden">
                    <!--bg-primary/40 group-hover:bg-primary/20-->

                    <div class="absolute inset-0 transition-all z-10 pointer-events-none">
                    </div>

                    @if ($service->image_path)
                    <img src="{{ asset('storage/' . $service->image_path) }}" loading="lazy"
                        alt="{{ $service->title }}"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    @else
                    <div
                        class="absolute bottom-4 right-4 z-20 bg-accent p-3 rounded-full shadow-md text-white">
                        <i class="fa-solid {{ $service->iconClass() ?? 'fa-paint-roller' }} text-2xl"></i>
                    </div>
                    @endif

                    @if ($service->icon)
                    <div
                        class="absolute bottom-4 right-4 z-20 bg-accent p-3 rounded-full shadow-md text-white">
                        <i class="fa-solid {{ $service->iconClass() ?? 'fa-paint-roller' }} text-2xl"></i>
                    </div>
                    @else
                    <div
                        class="absolute bottom-4 right-4 z-20 bg-accent p-3 rounded-full shadow-md text-white">
                        <i class="fa-solid fa-paint-roller text-2xl"></i>
                    </div>
                    @endif
                </div>

                <div class="p-8">
                    <h3 class="text-2xl font-bold text-primary mb-3 group-hover:text-accent transition-colors">
                        <a href="{{ route('services.show', $service->slug) }}" title="{{ $service->title }}">
                            {{ $service->title }}
                        </a>
                    </h3>

                    <p class="text-gray-600 mb-6 line-clamp-3">
                        {{ Str::limit($service->description, 100) }}
                    </p>

                    <a href="{{ route('services.show', $service->slug) }}" title="{{ $service->title }}"
                        class="inline-flex items-center text-primary font-bold hover:text-accent transition-colors">
                        اقرأ المزيد
                        <i
                            class="fa-solid fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center reveal mt-7 relative">
            <a href="{{ route('services.index') }}"
                class="inline-flex items-center gap-2 text-primary font-bold hover:text-accent transition-colors border border-primary/20 hover:border-accent px-6 py-3 rounded-full hover:bg-white hover:shadow-md">
                جميع الخدمات
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>
        @else
        <div class="text-center text-gray-500 py-8">
            <p>لا توجد خدمات متاحة حالياً.</p>
        </div>
        @endif
    </div>
</section>
<!-- Projects Section -->
<section id="portfolio" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 reveal">
            <span class="text-accent font-bold uppercase tracking-wider block mb-2">أعمالنا</span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary">
                مشاريع نفخر بها
            </h2>
            <div class="w-20 h-1 bg-accent mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Portfolio Grid -->
        @if ($projects->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up">

            @foreach ($projects as $index => $project)
            <div class="portfolio-item group relative overflow-hidden rounded-xl shadow-lg bg-white
                                h-96 sm:h-90 md:h-96 lg:h-[400px]"
                data-aos-delay="{{ $index * 100 }}">

                <!-- Image Container (ملء كامل البطاقة) -->
                <div class="w-full h-full relative">
                    @if ($project->main_image)
                    <img src="{{ asset('storage/' . $project->main_image) }}"
                        alt="{{ $project->title }}" loading="lazy"
                        class="w-full h-full object-cover absolute inset-0 transition-transform duration-700 md:group-hover:scale-110">
                    @else
                    <div
                        class="absolute inset-0 w-full h-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">لا توجد صورة</span>
                    </div>
                    @endif
                </div>

                <!-- Overlay (ثابت أسفل البطاقة) -->
                <div
                    class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent opacity-90
                                    group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5 pointer-events-none">

                    <div
                        class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-white text-lg font-bold mb-2">
                            <a href="{{ route('projects.show', $project->slug) }}" title="{{ $project->title }}"
                                class="hover:text-accent transition-colors block truncate pointer-events-auto">
                                {{ $project->title }}
                            </a>
                        </h3>

                        <!-- وصف محدود الارتفاع لمنع كسر التصميم -->
                        <p class="text-gray-200 text-sm mb-4 leading-relaxed max-h-[4.5rem] overflow-hidden">
                            {{ $project->description }}
                        </p>

                        <a href="{{ route('projects.show', $project->slug) }}" title="{{ $project->title }}"
                            class="inline-flex items-center gap-2 bg-accent text-primary text-xs md:text-sm font-bold px-4 py-2 rounded-lg hover:bg-white transition-all w-fit pointer-events-auto">
                            التفاصيل
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>

                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('projects.index') }}"
                class="inline-block border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold py-3 px-10 rounded-full transition-all duration-300 shadow-sm hover:shadow-lg">
                عرض كل الاعمال
            </a>
        </div>
        @else
        <div class="text-center text-gray-500 py-8">
            <p>لا توجد اعمال متاحة حالياً.</p>
        </div>
        {{-- <div class="text-center text-gray-500 py-20 bg-white rounded-xl shadow-inner">
                <i class="fa-regular fa-folder-open text-5xl mb-4 text-gray-300"></i>
                <p class="text-xl">لا توجد اعمال متاحة حالياً.</p>
            </div> --}}
        @endif

    </div>
</section>



<!-- Experience & Skills Section -->
<section id="skills" class="py-20 bg-primary text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('assets/img/cubes.png')] opacity-10"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="reveal">
                <span class="text-accent font-bold uppercase tracking-wider block mb-2">خبراتنا</span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    لماذا تختار الفـن الـحـديـث؟
                </h2>
                <p class="text-gray-300 mb-8 leading-relaxed">
                    نمتلك سجلاً حافلاً من الإنجازات والخبرات المتراكمة التي تجعلنا
                    الخيار الأمثل لمشروعك القادم. نجمع بين الدقة الهندسية والإبداع
                    المعماري.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 mb-8">
                    <a href="{{ route('about') }}"
                        class="inline-flex items-center gap-2 text-accent font-bold hover:text-white transition-colors group">
                        اكتشف المزيد من مهاراتنا
                        <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    </a>

                </div>

                <!-- Skills Progress -->
                <div class="space-y-6">
                    @foreach ($skills as $skill)
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-bold">{{ $skill->name }}</span>
                            <span class="text-accent">{{ $skill->percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2.5">
                            <div class="progress-bar bg-accent h-2.5 rounded-full transition-all duration-1000 ease-out"
                                style="width: {{ trim($skill->percentage) }}%" data-width="95"></div>
                        </div>
                    </div>
                    @endforeach


                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 reveal counter-container">
                <div
                    class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 text-center hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-diagram-project text-4xl text-accent mb-4"></i>
                    <div class="text-4xl font-bold mb-2 counter" data-goal="{{ $totalprojects }}" data-suffix="+">
                        {{ $totalprojects }}
                    </div>
                    <div class="text-gray-300">اعمال مكتملة</div>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 text-center hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-users text-4xl text-accent mb-4"></i>
                    <div class="text-4xl font-bold mb-2 counter" data-goal="24" data-suffix="+">
                        0
                    </div>
                    <div class="text-gray-300">معلمين متميزين</div>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 text-center hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-award text-4xl text-accent mb-4"></i>
                    <div class="text-4xl font-bold mb-2 counter" data-goal="8">
                        0
                    </div>
                    <div class="text-gray-300">سـنـوات الـخـبـرة</div>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/20 text-center hover:bg-white/20 transition-colors">
                    <i class="fa-solid fa-smile text-4xl text-accent mb-4"></i>
                    <div class="text-4xl font-bold mb-2 counter" data-goal="100" data-suffix="%">
                        0
                    </div>
                    <div class="text-gray-300">رضا العملاء</div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Testimonials Section -->
<section id="testimonials" class="py-20 bg-white">
    <div class="container mx-auto px-4">


        <div class="text-center mb-16 reveal">
            <span class="text-accent font-bold uppercase tracking-wider block mb-2">شهادات نعتز بها</span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary">ثقة عملائنا هي سر نجاحنا</h2>
            <div class="w-20 h-1 bg-accent mx-auto mt-4 rounded-full"></div>
        </div>

        @if ($testimonials->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach ($testimonials as $index => $testimonial)
            <div class="bg-gray-50 p-8 rounded-xl shadow-lg relative reveal" data-aos="fade-up"
                data-aos-delay="{{ $index * 100 }}">

                <div
                    class="absolute -top-4 -right-4 w-10 h-10 bg-accent rounded-full flex items-center justify-center text-white">
                    <i class="fa-solid fa-quote-right"></i>
                </div>

                <p class="text-gray-600 mb-6 leading-relaxed italic break-all max-w-full">
                    " {{ Str::limit($testimonial->content, 100) }}"
                </p>

                <div class="flex items-center gap-4">
                    @if ($testimonial->image_path)
                    <img src="{{ asset('storage/' . $testimonial->image_path) }}" loading="lazy"
                        alt="{{ $testimonial->client_name }}"
                        class="w-14 h-14 rounded-full object-cover shadow-sm">
                    @else
                    <div
                        class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center ml-4 text-gray-500 font-bold text-lg">
                        {{ Str::substr($testimonial->client_name, 0, 1) }}
                    </div>
                    @endif

                    <div>
                        <h4 class="font-bold text-primary">{{ $testimonial->client_name }}</h4>
                        <p class="text-sm text-gray-500">{{ $testimonial->position }}</p>
                    </div>
                </div>

                <!-- Stars Rating -->
                <div class="mt-4 flex text-accent text-sm gap-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <=$testimonial->rating)
                        <i class="fa-solid fa-star"></i>
                        @else
                        <i class="fa-regular fa-star"></i>
                        @endif
                        @endfor
                </div>

            </div>
            @endforeach

        </div>

        <div class="mt-8">
            {{ $testimonials->links('partials.pagination') }}
        </div>
        @else
        <div class="text-center text-gray-500 py-8">
            <p>لا توجد اّراء متاحة حالياً.</p>
        </div>
        @endif

    </div>


</section>


<section id="blog" class="py-20 bg-gray-50">

    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12 reveal">
            <div>
                <span class="text-accent font-bold uppercase tracking-wider block mb-2">المدونة</span>
                <h2 class="text-3xl md:text-4xl font-bold text-primary">
                    أحدث المقالات والأخبار
                </h2>
            </div>
            <a href="{{ route('blog.index') }}"
                class="hidden md:inline-flex items-center gap-2 text-accent font-bold hover:text-primary transition-colors">
                عرض جميع المقالات
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        <!-- latestPosts Grid -->
        @if ($latestPosts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal">

            <!-- Article 1 -->
            @foreach ($latestPosts as $index => $latestPost)
            <div
                class="portfolio-item bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all hover:-translate-y-2 group border border-gray-100">
                <div class="relative h-56 overflow-hidden" data-aos-delay="{{ $index * 100 }}">

                    @if ($latestPost->image_path)
                    <img src="{{ asset('storage/' . $latestPost->image_path) }}" loading="lazy"
                        alt="{{ $latestPost->title }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">لا توجد صورة</span>
                    </div>
                    @endif
                    {{--
                                 <div class="absolute top-4 right-4 bg-accent text-white text-xs font-bold px-3 py-1 rounded-full">
                                </div>
                            <div
                                class="absolute bottom-0 right-0 bg-primary text-white text-xs px-3 py-1 rounded-tl-lg">
                                اعمالنا</div> --}}
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-gray-500 text-xs mb-3 ">
                        <i class="fas fa-calendar-day"></i>
                        <span class="text-bold items-center "
                            style="font-weight: 700">{{ $latestPost->published_at->format('Y-m-d') }}</span>
                        {{-- <span class="mx-1">•</span> --}}
                        {{-- <i class="fa-solid fa-comments"></i> <span>3 تعليقات</span> --}}
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-3 hover:text-accent transition-colors"><a
                            href="{{ route('blog.show', $latestPost->slug) }}" title="{{ $latestPost->title }}">{{ $latestPost->title }}</a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        {{ Str::limit($latestPost->content, 100) }}
                    </p>
                    <a href="{{ route('blog.show', $latestPost->slug) }}" title="{{ $latestPost->title }}"
                        class="text-accent font-medium hover:underline inline-flex items-center gap-1">اقرأ
                        المزيد <i class="fa-solid fa-arrow-left text-sm"></i></a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('blog.index') }}"
                class="inline-block border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold py-3 px-8 rounded-full transition-all">عرض
                كل المقالات</a>
        </div>
        @else
        <div class="text-center text-gray-500 py-8">
            <p>لا توجد مقالات متاحة حالياً.</p>
        </div>
        @endif
        @if (isset($pageContentKeywords))
        <section class="py-6 bg-white mt-12 rounded-lg border-t-4 border-accent">
            <div>
                <span class="text-accent font-bold uppercase tracking-wider block mb-2  text-center">
                    كلمات مفتاحية

                </span>

            </div>
            <div class="container mx-auto px-4">
                @include('partials.keyword-tags', ['keywords' => $pageContentKeywords])
            </div>
        </section>
        @endif

    </div>
</section>



<!-- Call to Action -->
<section id="contact" class="py-20 bg-white">
    <div class="container mx-auto px-4">

        <div class="flex flex-col lg:flex-row bg-white rounded-2xl shadow-xl overflow-hidden reveal mb-12">

            <div class="w-full lg:w-5/12 bg-primary text-white p-12 relative overflow-hidden">

                <h3 class="text-3xl font-bold mb-8">معلومات التواصل</h3>
                <p class="text-gray-300 mb-10 leading-relaxed">
                    نحن هنا للإجابة على جميع استفساراتكم. لا تترددوا في التواصل معنا لمناقشة مشاريعكم القادمة.
                </p>

                <div class="space-y-8">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-accent shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1">موقعنا</h4>
                            <p class="text-gray-300 text-sm hover:text-accent font-sans">
                                {{ $settings['address'] ?? 'المملكة العربية السعودية، جدة' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-accent shrink-0">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1">واتساب</h4>
                            <p class="text-gray-300 text-sm font-sans hover:text-accent" dir="ltr">
                                <a href="{{ $settings['whatsapp'] ?? '+966 5 3279 1522' }}" target="_blank"
                                    rel="noopener noreferrer">+966 5
                                    3279 1522</a>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-accent shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1">رقم الهاتف</h4>
                            <p class="text-gray-300 text-sm font-sans hover:text-accent" dir="ltr">

                                <a
                                    href="tel:{{ $settings['phone'] ?? '+966 5 3279 1522' }}">{{ $settings['phone'] ?? '+966 5 3279 1522' }}</a>

                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-white/10">
                    <h4 class="font-bold mb-4">تابعنا على</h4>
                    <div class="flex gap-4">

                        @if (isset($settings['whatsapp']))
                        <a href="{{ $settings['whatsapp'] }}"
                            class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                            target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>
                        @endif
                        @if (isset($settings['tiktok']))
                        <a href="{{ $settings['tiktok'] }}"
                            class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                            target="_blank" rel="noopener noreferrer"><i class="fab fa-tiktok"></i></a>
                        @endif
                        @if (isset($settings['facebook']))
                        <a href="{{ $settings['facebook'] }}"
                            class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                            target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if (isset($settings['twitter']))
                        <a href="{{ $settings['twitter'] }}"
                            class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                            target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-twitter"></i></a>
                        @endif
                        @if (isset($settings['instagram']))
                        <a href="{{ $settings['instagram'] }}"
                            class=" w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors "
                            target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if (isset($settings['linkedin']))
                        <a href="{{ $settings['linkedin'] }}"
                            class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors"
                            target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-7/12 p-8 lg:p-12 bg-white">
                <h3 class="text-2xl font-bold text-primary mb-6">أرسل لنا رسالة</h3>

                <div id="contact-alert" class="hidden mb-4 p-4 rounded-lg text-sm font-medium"></div>

                <form id="contact-form" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">الاسم
                                الكامل</label>
                            <input type="text" id="name"
                                class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-accent focus:bg-white focus:ring-1 focus:ring-accent outline-none transition-colors"
                                placeholder="الاسم الكامل">
                        </div>

                        <div>
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد
                                الإلكتروني</label>
                            <input type="email" id="email"
                                class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-accent focus:bg-white focus:ring-1 focus:ring-accent outline-none transition-colors"
                                placeholder="example@email.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم
                                الهاتف</label>
                            <input type="tel" id="phone"
                                class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-accent focus:bg-white focus:ring-1 focus:ring-accent outline-none transition-colors"
                                placeholder="05xxxxxxxx">
                        </div>

                        <div>
                            <label for="subject" class="block text-gray-700 text-sm font-bold mb-2">الموضوع</label>
                            <select id="subject"
                                class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-accent focus:bg-white focus:ring-1 focus:ring-accent outline-none transition-colors appearance-none">
                                <option value="">اختر الموضوع</option>
                                <option value="استشارة">استشارة</option>
                                <option value="عرض سعر">عرض سعر</option>
                                <option value="دعم">دعم فني</option>
                                <option value="أخرى">أخرى</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-gray-700 text-sm font-bold mb-2">الرسالة</label>
                        <textarea id="message" rows="5"
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-accent focus:bg-white focus:ring-1 focus:ring-accent outline-none transition-colors"
                            placeholder="تفاصيل استفسارك..."></textarea>
                        <div class="text-xs text-gray-400 mt-2 text-left" dir="ltr">
                            Chars: <span id="char-count">0</span>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn"
                        class="bg-primary text-white font-bold py-3 px-8 rounded-lg hover:bg-accent transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 w-full md:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                        إرسال الرسالة
                    </button>
                </form>
            </div>
        </div>



    </div>
</section>

@endsection

@push('scripts')
<script>
    const form = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const alertBox = document.getElementById('contact-alert');
    const emailInput = document.getElementById('email');
    const nameInput = document.getElementById('name');
    const subjectSelect = document.getElementById('subject');
    const messageInput = document.getElementById('message');
    const phoneInput = document.getElementById('phone');
    const charCount = document.getElementById('char-count');

    function showAlert(text, type) {
        alertBox.textContent = text;

        alertBox.className = 'mb-4 p-4 rounded-lg text-sm font-bold border ' + (type === 'success' ?
            'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200');
        alertBox.classList.remove('hidden');
    }

    function hideAlert() {
        alertBox.classList.add('hidden');
    }

    function validEmail(v) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
    }

    if (messageInput) {
        messageInput.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }

    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            hideAlert();

            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const subject = subjectSelect.value.trim();
            const message = messageInput.value.trim();
            const phone = phoneInput.value.trim();

            if (!name || !email || !subject || !message) {
                showAlert('يرجى ملء جميع الحقول المطلوبة', 'error');
                return;
            }

            if (!validEmail(email)) {
                showAlert('صيغة البريد الإلكتروني غير صحيحة', 'error');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin ml-2"></i> جاري الإرسال...';

            try {
                // تأكد من وجود الميتا تاج الخاص بـ csrf في الهيدر
                const tokenElement = document.querySelector('meta[name="csrf-token"]');
                const token = tokenElement ? tokenElement.getAttribute('content') : '';

                const res = await fetch("{{ route('contact.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        email,
                        subject,
                        message,
                        phone
                    })
                });

                const data = await res.json();

                if (res.ok && data.status === 'ok') {
                    showAlert('تم إرسال رسالتك بنجاح، شكراً لتواصلك معنا.', 'success');
                    form.reset();
                    charCount.textContent = '0';
                } else {
                    showAlert('تعذر إرسال الرسالة، حاول لاحقاً', 'error');
                }
            } catch (err) {
                showAlert('حدث خطأ غير متوقع', 'error');
                console.error(err);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'إرسال الرسالة';
            }
        });
    }
</script>
@endpush