@extends('layouts.site')
@section('title', ' مشاريعنا')
@section('meta_title', $settings['site_name'] ?? config('app.name', 'معلم دهانات وديكورات جدة حي الروضة ت: 0532791522'))
@section('meta_description', $settings['site_description'] ?? null)
@section('meta_keywords',
    collect([$settings['site_name'] ?? null, 'مشاريعنا', 'مشاريع', 'المشاريع'])->filter()->implode(', '))





@section('content')


    <!-- Page Hero -->
    <section class="relative h-[40vh] min-h-[350px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/img/hero.webp') }}" alt="معلم دهانات وديكورات جدة حي الروضة" class="w-full h-full object-cover" loading="lazy" decoding="async">
            <div class="absolute inset-0 bg-primary/70 mix-blend-multiply"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center pt-20">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 animate-fade-in-up text-accent">اعمالنا</h1>
            <nav
                class="flex justify-center items-center gap-2 text-sm md:text-base text-gray-300 animate-fade-in-up animation-delay-200">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">الرئيسية</a>
                <span>/</span>
                <span
                    class="text-accent/4 transition-colors py-1 relative  after:absolute after:bottom-0 after:right-0 after:w-full after:h-0.5 after:bg-accent after:transition-all">جميع
                    أعمالنا</span>
            </nav>
        </div>
    </section>




    <section class="py-16">
        <div class="container mx-auto px-4">
            @if (session('error'))
                <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">{{ session('error') }}</div>
            @endif

         


            @if ($projects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in-up">

                    @foreach ($projects as $index => $project)
                        <div class="portfolio-item group relative overflow-hidden rounded-lg shadow-md h-96 sm:h-90 md:h-96 lg:h-[400px]"
                            data-aos-delay="{{ $index * 100 }}">

                            @if ($project->main_image)
                                <img src="{{ asset('storage/' . $project->main_image) }}" alt="{{ $project->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" decoding="async">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">لا توجد صورة</span>
                                </div>
                            @endif


                            <!--للمعاينه -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-primary/90 to-transparent duration-300 flex flex-col justify-end p-6 pointer-events-none">

                                <h3 class="text-white text-xl hover:text-accent font-bold mb-1">
                                    <a href="{{ route('projects.show', $project->slug) }}" class="pointer-events-auto">
                                        {{ $project->title }}
                                    </a>
                                </h3>

                                <p class="text-gray-200 text-sm mb-3 line-clamp-3 overflow-hidden break-words">
                                    {{ Str::limit($project->description, 250) }}
                                </p>

                                <a href="{{ route('projects.show', $project->slug) }}"
                                    class="inline-block bg-accent text-primary text-sm font-bold px-4 py-2 rounded hover:bg-secondary hover:text-accent transition w-fit pointer-events-auto">
                                    المزيد من التفاصيل
                                    <i class="fa-solid fa-arrow-left text-sm"></i>
                                </a>

                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="mt-8">
                    {{ $projects->links('partials.pagination') }}
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <p>لا توجد مشاريع متاحة حالياً.</p>
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
@endsection
