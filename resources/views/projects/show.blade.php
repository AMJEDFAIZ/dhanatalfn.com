@extends('layouts.site')

@section('meta_title', $project->meta_title ?: $project->title)
@section('meta_description', $meta_description ?: Str::limit(strip_tags($project->description ?? ''), 160))
@section('meta_keywords', $meta_keywords)
<!--$project->title-->

@section('content')



<!-- Page Hero -->
<section class="relative h-[40vh] min-h-[350px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        @if ($project->main_image)
        <img src="{{ asset('storage/' . $project->main_image) }}" alt="{{ $project->title }}"
            class="w-full h-full object-cover" loading="eager" fetchpriority="high" decoding="async">
        @else
        <img src="{{ asset('assets/img/hero.webp') }}"
            srcset="{{ asset('assets/img/hero11.webp') }} 640w, {{ asset('assets/img/hero.webp') }} 1600w"
            sizes="100vw" alt="معلم بوية جدة" class="w-full h-full object-cover" loading="eager" fetchpriority="high"
            decoding="async">
        @endif
        <div class="absolute inset-0 bg-primary/60 mix-blend-multiply"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 text-center pt-20">
        <h1 class="text-3xl md:text-3xl lg:text-6xl font-bold mb-4 animate-fade-in-up text-accent">{{ $project->title }}
        </h1>
        <nav
            class="flex justify-center items-center gap-2 text-sm md:text-base text-gray-300 animate-fade-in-up animation-delay-200">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('projects.index') }}" class="hover:text-white transition-colors">أعمالنا</a>
            <span>/</span>
            <span
                class="text-accent/4 transition-colors py-1 relative  after:absolute after:bottom-0 after:right-0 after:w-full after:h-0.5 after:bg-accent after:transition-all">{{ $project->title }}
            </span>
        </nav>
    </div>
</section>




<section class="py-20 bg-white">
    <div class="container mx-auto px-4">

        @if ($project->main_image)
        <div class="mb-12 reveal">
            <div class="relative rounded-2xl overflow-hidden shadow-2xl h-[400px] lg:h-[600px] group">
                <img src="{{ asset('storage/' . $project->main_image) }}" alt="{{ $project->title }}"
                    class="w-full h-full transition-transform duration-700 group-hover:scale-105" loading="lazy" decoding="async">
                <!--object-cover -->
                {{-- <div class="absolute bottom-0 right-0 w-full bg-gradient-to-t from-black/80 to-transparent p-8"> --}}
                <div class="absolute bottom-0 right-0 bg-primary text-white text-xs px-5 py-3 rounded-tl-lg">
                    <h1 class="text-2xl lg:text-4xl font-bold text-accent">{{ $project->title }}</h1>

                    {{-- </div> --}}
                </div>
            </div>
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-12">

            <div class="w-full lg:w-1/3 order-2 lg:order-1 reveal">
                <div class="sticky top-8 space-y-8">
                    <div class="bg-gray-50 p-8 rounded-xl border border-gray-100 shadow-sm  top-8">
                        <h3
                            class="text-xl font-bold text-primary mb-6 relative after:content-[''] after:absolute after:-bottom-2 after:right-0 after:w-12 after:h-1 after:bg-accent">
                            بطاقة المشروع
                        </h3>

                        <ul class="space-y-6">
                            {{-- العميل --}}
                            <li class="flex justify-between border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <span class="font-bold text-gray-700">العميل:</span>
                                <span class="text-gray-500">{{ $project->client ?? 'عميل خاص' }}</span>
                            </li>

                            {{-- الموقع --}}
                            @if ($project->location)
                            <li class="flex justify-between border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <span class="font-bold text-gray-700">الموقع:</span>
                                <span class="text-gray-500">{{ $project->location }}</span>
                            </li>
                            @endif

                            {{-- النطاق --}}
                            @if ($project->scope)
                            <li class="flex justify-between border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <span class="font-bold text-gray-700">النطاق:</span>
                                <span class="text-gray-500">{{ $project->scope }}</span>
                            </li>
                            @endif
                            @if ($project->duration)
                            <li class="flex justify-between border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <span class="font-bold text-gray-700">المدة الزمنية:</span>
                                <span class="text-gray-500">{{ $project->duration }}</span>
                            </li>
                            @endif


                            {{-- السنة --}}
                            <li class="flex justify-between border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <span class="font-bold text-gray-700">تاريخ النشر :</span>
                                <span class="text-gray-500">{{ $project->created_at->format('Y') }}</span>
                            </li>
                        </ul>


                    </div>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-100 reveal delay-100">
                        <h3
                            class="text-xl font-bold text-primary mb-4 relative after:content-[''] after:absolute after:-bottom-2 after:right-0 after:w-12 after:h-1 after:bg-accent">
                            مشاريع أخرى
                        </h3>

                        <ul class="space-y-3">
                            @foreach ($sidebarProjects as $projectItem)
                            <li>
                                {{-- التحقق مما إذا كان هذا الرابط هو الصفحة الحالية لتمييزه --}}
                                @php
                                // هذا الشرط يعمل إذا كنا في صفحة خدمة ونقارن الـ Slug
                                $isActive = request()->route('slug') == $projectItem->slug;
                                @endphp

                                <a href="{{ route('projects.show', $projectItem->slug) }}"
                                    class="group flex items-center gap-3 p-3 rounded-lg shadow-sm transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/40
                                  {{ $isActive ? 'bg-white text-accent border-r-4 border-accent pointer-events-none' : 'bg-white text-gray-700 hover:bg-accent hover:text-white' }}">
                                    <div class="shrink-0 w-14 h-14 rounded-lg overflow-hidden bg-gray-100 ring-1 ring-gray-200">
                                        @if (!empty($projectItem->main_image))
                                        <img src="{{ asset('storage/' . $projectItem->main_image) }}"
                                            alt="{{ $projectItem->title }}" loading="lazy" decoding="async"
                                            class="w-full h-full object-cover">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <span class="block font-semibold leading-6 line-clamp-2 break-words">
                                            {{ $projectItem->title }}
                                        </span>
                                    </div>

                                    <div
                                        class="shrink-0 text-xs opacity-60 group-hover:opacity-100 transition-opacity">
                                        <i class="fa-solid fa-chevron-left"></i>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-8  bg-primary text-white p-8 rounded-xl shadow-lg reveal delay-100">
                        <h3 class="text-xl font-bold mb-4">هل تريد عمل مماثل؟</h3>
                        <p class="text-gray-300 mb-6 text-sm">تواصل معنا اليوم للحصول على استشارة مجانية لعملك.</p>
                        <a href="{{ route('contact') }}"
                            class="block text-center bg-accent text-white py-3 rounded-lg font-bold hover:bg-white hover:text-primary transition-colors">اطلب
                            عرض سعر</a>
                    </div>
                    <!-- Sidebar -->
                    {{-- <div class="w-full lg:w-1/3 space-y-8"> --}}
                    <!-- Services Menu -->


                    <!-- Contact Widget -->
                    {{-- <div class="bg-primary text-white p-8 rounded-xl shadow-lg relative overflow-hidden reveal delay-200">
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
                            <span dir="ltr">{{ $settings['phone'] ?? '+966 5 3279 1522' }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center text-accent">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <span> {{ $settings['email'] ?? 'info@example.com' }}</span>
                    </li>
                    </ul>
                    <a href="{{ route('contact') }}"
                        class="block text-center bg-white text-primary py-3 rounded-lg font-bold hover:bg-accent hover:text-white transition-colors">اتصل
                        بنا</a>
                </div> --}}
                {{-- </div> --}}
            </div>
        </div>

        <div class="w-full lg:w-2/3 order-1 lg:order-2 reveal delay-100">

            <div class="mb-12">
                <h2 class="text-3xl font-bold text-primary mb-6">

                    تفاصيل عن
                    <span class="text-3xl font-bold text-accent mb-6">{{ $project->title }}
                    </span>
                </h2>
                <div
                    class="prose max-w-none text-gray-600 leading-relaxed text-lg text-justify overflow-hidden break-words">
                    {!! nl2br(e($project->description)) !!}
                </div>
            </div>

            {{-- <hr class="border-gray-200 my-10"> --}}
            <div
                class="mt-12 pt-8 border-t  border-gray-200 flex flex-col md:flex-row justify-between items-center gap-6">

                <div class="flex gap-2 flex-wrap">
                    @if (isset($contentKeywords))
                    <span class="text-accent font-bold uppercase tracking-wider block mb-2">كلمات
                        مفتاحية:</span>
                    @include('partials.keyword-tags', ['keywords' => $contentKeywords])
                    @endif
                </div>
                <div class="flex gap-4">
                    <span class="text-gray-500 font-bold ml-2">مشاركة:</span>
                    {{-- روابط مشاركة حقيقية --}}
                    <a href="https://facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-400 hover:text-[#1877F2] text-xl transition-colors"><i
                            class="fa-brands fa-facebook"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $project->title }}"
                        target="_blank" rel="noopener noreferrer"
                        class="text-gray-400 hover:text-[#1DA1F2] text-xl transition-colors"><i
                            class="fa-brands fa-twitter"></i></a>
                    <a href="https://wa.me/?text={{ $project->title }} {{ url()->current() }}" target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-400 hover:text-[#25D366] text-xl transition-colors"><i
                            class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>


            @if ($projectImages && $projectImages->count() > 0)
            <div id="gallery-section">
                <h3 class="text-2xl font-bold text-primary mb-6 flex items-center gap-2">
                    معرض الصور
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                    @foreach ($projectImages as $image)
                    <div
                        class="gallery-item group relative h-64 rounded-xl overflow-hidden shadow-md cursor-zoom-in">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            alt="{{ $image->title }} - صورة {{ $loop->iteration }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" decoding="async">

                        <div
                            class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass-plus text-white text-3xl"></i>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-center">

                    {{ $projectImages->links('partials.pagination') }}

                </div>
            </div>
            @endif

        </div>
    </div>
    </div>
</section>


@endsection