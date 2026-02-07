@extends('layouts.site')

@section('title', ' المدونة')
@section('meta_title', $settings['site_name'] ?? config('app.name', 'الفـن الـحـديـث'))
@section('meta_description', $settings['site_description'] ?? null)
@section('meta_keywords',
    collect([$settings['site_name'] ?? null, 'المدونة', 'مدونة', 'مشاريعنا', 'الفن الحديث ', 'معلم دهان', 'ديكورات', 'دهانات', 'معلم دهانات',
    'دهانات وديكورات'])->filter()->implode(', '))

@section('content')

    <!-- Page Hero -->
    <section class="relative h-[40vh] min-h-[350px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/img/hero.webp') }}" alt="المدونة" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-primary/70 mix-blend-multiply"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center pt-20">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 animate-fade-in-up text-accent">المدونة</h1>
            <nav
                class="flex justify-center items-center gap-2 text-sm md:text-base text-gray-300 animate-fade-in-up animation-delay-200">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">الرئيسية</a>
                <span>/</span>
                <span
                    class="text-accent/4 transition-colors py-1 relative  after:absolute after:bottom-0 after:right-0 after:w-full after:h-0.5 after:bg-accent after:transition-all">المقالات
                    والأخبار</span>
            </nav>
        </div>
    </section>



    <section id="blog" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12 animate-fade-in-up ">
                <div>
                    <span class="text-accent font-bold uppercase tracking-wider block mb-2">المدونة</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-primary">
                        أحدث المقالات والأخبار
                    </h2>
                </div>

            </div>
            @if (session('error'))
                <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">{{ session('error') }}</div>
            @endif


            <!-- latestPosts Grid -->
            @if ($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in-up">

                    <!-- Article 1 -->
                    @foreach ($posts as $index => $latestPost)
                        <div
                            class="portfolio-item bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all hover:-translate-y-2 group border border-gray-100">
                            <div class="relative h-56 overflow-hidden" data-aos-delay="{{ $index * 100 }}">

                                @if ($latestPost->image_path)
                                    <img src="{{ asset('storage/' . $latestPost->image_path) }}"
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
                                        style="font-weight: 700">{{ $latestPost->published_at ? $latestPost->published_at->format('Y-m-d') : $latestPost->created_at->format('Y-m-d') }}</span>
                               
                                </div>
                                <h3 class="text-xl font-bold text-primary mb-3 hover:text-accent transition-colors"><a
                                        href="{{ route('blog.show', $latestPost->slug) }}">{{ $latestPost->title }}</a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ Str::limit($latestPost->content, 120) }}</p>
                                <a href="{{ route('blog.show', $latestPost->slug) }}"
                                    class="text-accent font-medium hover:underline inline-flex items-center gap-1">اقرأ
                                    المزيد <i class="fa-solid fa-arrow-left text-sm"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $posts->links('partials.pagination') }}
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <p>لا توجد مقالات متاحة حالياً.</p>
                </div>
            @endif

        </div>
    </section>
@endsection
