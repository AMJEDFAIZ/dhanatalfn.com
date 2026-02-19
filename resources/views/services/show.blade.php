@extends('layouts.site')


@section('title', $service->title)
@section('meta_title', $service->meta_title ?: $service->title)
@section('meta_description', $service->meta_description ?: Str::limit(strip_tags($service->description ?? ''), 160))
@section('meta_keywords', $service->title)

@section('content')



<!-- Page Hero -->
<section class="relative h-[40vh] min-h-[350px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        @if ($service->image_path)
        <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->title }}"
            class="w-full h-full object-cover" loading="eager" fetchpriority="high" decoding="async">
        @else
        <img src="{{ asset('assets/img/hero.webp') }}"
            srcset="{{ asset('assets/img/hero11.webp') }} 640w, {{ asset('assets/img/hero.webp') }} 1600w"
            sizes="100vw" alt="معلم دهانات وديكورات جدة ت: 0532791522" class="w-full h-full object-cover"
            loading="eager" fetchpriority="high" decoding="async">
        @endif
        <div class="absolute inset-0 bg-primary/60 mix-blend-multiply"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 text-center pt-20">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 animate-fade-in-up text-accent">{{ $service->title }}
        </h1>
        <nav
            class="flex justify-center items-center gap-2 text-sm md:text-base text-gray-300 animate-fade-in-up animation-delay-200">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('services.index') }}" class="hover:text-white transition-colors">خدماتنا</a>
            <span>/</span>
            <span
                class="text-accent/4 transition-colors py-1 relative  after:absolute after:bottom-0 after:right-0 after:w-full after:h-0.5 after:bg-accent after:transition-all">{{ $service->title }}
            </span>
        </nav>
    </div>
</section>



<!-- Service Detail Content -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Main Content -->
            <div class="w-full lg:w-2/3">
                <div class="reveal">
                    @if ($service->image_path)
                    <img src="{{ asset('storage/' . $service->image_path) }}" alt="{{ $service->title }}" title="{{ $service->title }}"
                        class="w-full  object-cover h-[400px] lg:h-[500px] rounded-xl shadow-lg mb-8" loading="lazy" decoding="async">
                    @endif {{-- object-cover --}}
                    @if ($service->title)
                    <h2 class="text-3xl font-bold text-accent mb-6" title="{{ $service->title }}">{{ $service->title }}</h2>
                    @endif

                    <p class="text-gray-600 mb-6 leading-relaxed text-lg">
                        {!! nl2br(e($service->description)) !!}
                    </p>
                    <div
                        class="mt-12 pt-8 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-6">

                        <div class="flex gap-2 flex-wrap">
                            @if (isset($contentKeywords))
                            <span class="text-accent font-bold uppercase tracking-wider block mb-2">كلمات
                                مفتاحية:</span>
                            @include('partials.keyword-tags', ['keywords' => $contentKeywords])
                            @endif
                            {{-- @include('partials.keyword-tags', ['keywords' => $contentKeywords]) --}}
                        </div>
                        <div class="flex gap-4">
                            <span class="text-gray-500 font-bold ml-2">مشاركة:</span>
                            {{-- روابط مشاركة حقيقية --}}
                            <a href="https://facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank"
                                rel="noopener noreferrer"
                                class="text-gray-400 hover:text-[#1877F2] text-xl transition-colors"><i
                                    class="fa-brands fa-facebook"></i></a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $service->title }}"
                                target="_blank" rel="noopener noreferrer"
                                class="text-gray-400 hover:text-[#1DA1F2] text-xl transition-colors"><i
                                    class="fa-brands fa-twitter"></i></a>
                            <a href="https://wa.me/?text={{ $service->title }} {{ url()->current() }}" target="_blank"
                                rel="noopener noreferrer"
                                class="text-gray-400 hover:text-[#25D366] text-xl transition-colors"><i
                                    class="fa-brands fa-whatsapp"></i></a>
                        </div>
                    </div>

                    @if ($projects->count() > 0)
                    <div class="mt-12">
                        <h3 class="text-2xl font-bold text-accent mb-6">أعمالنا في {{ $service->title }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($projects as $project)
                            <div
                                class="group relative overflow-hidden rounded-xl shadow-md bg-white border border-gray-100 hover:shadow-xl transition-all duration-300">
                                <div class="relative h-48 overflow-hidden">
                                    @if ($project->main_image)
                                    <img src="{{ asset('storage/' . $project->main_image) }}"
                                        alt="{{ $project->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" loading="lazy" decoding="async">
                                    @else
                                    <div
                                        class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                        <i class="fa-regular fa-image text-4xl"></i>
                                    </div>
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <a href="{{ route('projects.show', $project->slug) }}" title="{{ $project->title }}"
                                            class="bg-primary text-white px-4 py-2 rounded-full text-sm font-bold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                            عرض التفاصيل
                                        </a>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h4
                                        class="font-bold text-lg mb-2 text-gray-800 group-hover:text-primary transition-colors">
                                        <a
                                            href="{{ route('projects.show', $project->slug) }}" title="{{ $project->title }}">
                                            {{ $project->title }}
                                        </a>
                                    </h4>
                                    <p class="text-gray-600 text-sm line-clamp-2">
                                        {{ Str::limit(strip_tags($project->description), 100) }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-8">
                            {{ $projects->links('partials.pagination') }}
                        </div>
                    </div>
                    @endif



                    <hr class="border-gray-200 my-10">
                    <h3 class="text-2xl font-bold text-primary mb-4">ما يميزنا</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                            <span class="text-4xl font-bold text-accent/20 block mb-2">01</span>
                            <h4 class="text-xl font-bold text-primary mb-2">الخبرة</h4>
                            <p class="text-gray-600 text-sm">خبرة تزيد عن 9 سنوات.</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                            <span class="text-4xl font-bold text-accent/20 block mb-2">02</span>
                            <h4 class="text-xl font-bold text-primary mb-2">فريقنا</h4>
                            <p class="text-gray-600 text-sm">
                                فريق من أفضل فنيين الديكور.
                            </p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                            <span class="text-4xl font-bold text-accent/20 block mb-2">03</span>
                            <h4 class="text-xl font-bold text-primary mb-2">تصاميمنا </h4>
                            <p class="text-gray-600 text-sm">
                                نقدم تصاميم حديثة ومبتكرة.
                            </p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                            <span class="text-4xl font-bold text-accent/20 block mb-2">04</span>
                            <h4 class="text-xl font-bold text-primary mb-2">اسعارنا</h4>
                            <p class="text-gray-600 text-sm">
                                أسعار تناسب الجميع.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-1/3 space-y-8">
                <!-- Services Menu -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-100 reveal delay-100">
                    <h3
                        class="text-xl font-bold text-primary mb-4 relative after:content-[''] after:absolute after:-bottom-2 after:right-0 after:w-12 after:h-1 after:bg-accent">
                        خدمات أخرى
                    </h3>

                    <ul class="space-y-3">
                        @foreach ($sidebarServices as $serviceItem)
                        <li>
                            {{-- التحقق مما إذا كان هذا الرابط هو الصفحة الحالية لتمييزه --}}
                            @php
                            // هذا الشرط يعمل إذا كنا في صفحة خدمة ونقارن الـ Slug
                            $isActive = request()->route('slug') == $serviceItem->slug;
                            @endphp

                            <a href="{{ route('services.show', $serviceItem->slug) }}" title="{{ $serviceItem->title }}"
                                class="group flex items-center gap-3 p-3 rounded-lg shadow-sm transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/40
                                  {{ $isActive ? 'bg-white text-accent border-r-4 border-accent pointer-events-none' : 'bg-white text-gray-700 hover:bg-accent hover:text-white' }}">
                                <div class="shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-gray-100 ring-1 ring-gray-200">
                                    @if (!empty($serviceItem->image_path))
                                    <img src="{{ asset('storage/' . $serviceItem->image_path) }}"
                                        alt="{{ $serviceItem->title }}" loading="lazy" decoding="async" title="{{ $serviceItem->title }}"
                                        class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <span class="block font-semibold leading-6 line-clamp-2 break-words">
                                        {{ $serviceItem->title }}
                                    </span>
                                </div>

                                <div class="shrink-0 text-xs opacity-60 group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Contact Widget -->
                <div class="bg-primary text-white p-8 rounded-xl shadow-lg relative overflow-hidden reveal delay-200">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-accent opacity-10 rounded-full blur-2xl transform translate-x-1/2 -translate-y-1/2">
                    </div>
                    <h3 class="text-xl font-bold mb-4">هل تحتاج مساعدة؟</h3>
                    <p class="text-gray-300 mb-6 text-sm">تواصل معنا الآن لمناقشة مشروعك والحصول على استشارة مجانية.</p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center text-accent">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <span dir="ltr"><a
                                    href="tel:{{ $settings['phone'] ?? '+966 5 3279 1522' }}">{{ $settings['phone'] ?? '+966 5 3279 1522' }}</a></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center text-accent">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <span> <a href="{{ $settings['whatsapp'] ?? '+966532791522' }}" target="_blank"
                                    rel="noopener noreferrer"
                                    class="hover:text-accent">{{ $settings['phone'] ?? '+966 5 3279 1522' }}</a></span>
                        </li>
                    </ul>
                    <a href="{{ route('contact') }}"
                        class="block text-center bg-white text-primary py-3 rounded-lg font-bold hover:bg-accent hover:text-white transition-colors">اتصل
                        بنا</a>
                </div>
            </div>

        </div>
    </div>
</section>


@endsection