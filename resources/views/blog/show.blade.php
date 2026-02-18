@extends('layouts.site')

@section('content')
<!-- Page Hero -->
<section class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
            class="w-full h-full object-cover" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-primary/90 to-primary/40"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 text-white pt-20">
        <div class="max-w-4xl mx-auto text-center">
            <div class="flex items-center justify-center gap-4 mb-4 text-sm animate-fade-in-up">
                {{-- <span class="bg-accent px-3 py-1 rounded-full font-bold">نصائح هندسية</span> --}}
                <span
                    class="text-gray-300">{{ $post->published_at ? $post->published_at->format('Y-m-d') : $post->created_at->format('Y-m-d') }}</span>
            </div>
            <h1
                class="text-3xl text-accent md:text-4xl lg:text-5xl font-bold mb-6  animate-fade-in-up animation-delay-200">
                {{ $post->title }}
            </h1>
            <div class="flex items-center justify-center gap-3 animate-fade-in-up animation-delay-400">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="text-right">
                    <p class="font-bold text-sm">كتب بواسطة: الادمن</p>
                    <p class="text-xs text-gray-300">فريق التحرير</p>
                </div>
            </div>
        </div>
    </div>
</section>






<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-12">

            <div class="w-full lg:w-2/3">

                <article
                    class="prose prose-lg max-w-none prose-headings:font-bold prose-headings:text-primary prose-a:text-accent prose-p:text-gray-600 prose-img:rounded-xl">

                    @if ($post->image_path)
                    <div class="mb-8 rounded-xl overflow-hidden shadow-lg">
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
                            class="w-full h-auto object-cover transform hover:scale-105 transition duration-700" loading="lazy" decoding="async">
                    </div>
                    @endif
                    <h2 class="text-3xl font-bold text-accent mb-6">{{ $post->title }}</h2>
                    <div class="lead text-xl text-gray-700 font-medium mb-8 overflow-hidden break-words">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    {{-- FAQs Section --}}
                    @if ($post->faqs->count() > 0)
                    <div class="mt-12 border-t border-gray-100 pt-8">
                        <h3 class="text-2xl font-bold text-accent mb-6">الأسئلة الشائعة</h3>
                        <div class="space-y-4">
                            @foreach ($post->faqs as $faq)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-4 bg-gray-200 font-bold text-gray-800 border-b border-gray-100">
                                    {{ $faq->question }}
                                </div>
                                <div
                                    class="p-4 text-gray-600 leading-relaxed break-words overflow-hidden font-bold">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif


                </article>

                <div
                    class="mt-12 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">

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
                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $post->title }}"
                            target="_blank" rel="noopener noreferrer"
                            class="text-gray-400 hover:text-[#1DA1F2] text-xl transition-colors"><i
                                class="fa-brands fa-twitter"></i></a>
                        <a href="https://wa.me/?text={{ $post->title }} {{ url()->current() }}" target="_blank"
                            rel="noopener noreferrer"
                            class="text-gray-400 hover:text-[#25D366] text-xl transition-colors"><i
                                class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">

                    @if ($prevPost)
                    <a href="{{ route('blog.show', $prevPost->slug) }}"
                        class="group flex items-center gap-4 p-4 border border-gray-100 rounded-xl hover:shadow-md transition-all">
                        <div
                            class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 group-hover:bg-accent group-hover:text-white transition-colors shrink-0">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-500 mb-1">المقال السابق</span>
                            <h4
                                class="font-bold text-primary group-hover:text-accent transition-colors line-clamp-1">
                                {{ $prevPost->title }}
                            </h4>
                        </div>
                    </a>
                    @else
                    <div></div>
                    @endif

                    @if ($nextPost)
                    <a href="{{ route('blog.show', $nextPost->slug) }}"
                        class="group flex items-center justify-end gap-4 p-4 border border-gray-100 rounded-xl hover:shadow-md transition-all text-left">
                        <div>
                            <span class="block text-xs text-gray-500 mb-1">المقال التالي</span>
                            <h4
                                class="font-bold text-primary group-hover:text-accent transition-colors line-clamp-1">
                                {{ $nextPost->title }}
                            </h4>
                        </div>
                        <div
                            class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 group-hover:bg-accent group-hover:text-white transition-colors shrink-0">
                            <i class="fa-solid fa-chevron-left"></i>
                        </div>
                    </a>
                    @endif

                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="sticky top-8 space-y-8">

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3
                            class="text-xl font-bold text-primary mb-4 relative after:content-[''] after:absolute after:-bottom-2 after:right-0 after:w-12 after:h-1 after:bg-accent">
                            البحث
                        </h3>
                        <form action="{{ route('blog.index') }}" method="GET" class="relative">
                            <input type="text" name="search" placeholder="ابحث في المقالات..."
                                class="w-full pr-4 pl-10 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-accent focus:bg-white transition-colors">
                            <button type="submit"
                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-accent">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3
                            class="text-xl font-bold text-primary mb-6 relative after:content-[''] after:absolute after:-bottom-2 after:right-0 after:w-12 after:h-1 after:bg-accent">
                            آخر المقالات
                        </h3>

                        <div class="space-y-6">

                            @foreach ($recentPosts as $recent)
                            <a href="{{ route('blog.show', $recent->slug) }}" class="flex gap-4 group">
                                <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0">
                                    @if ($recent->image_path)
                                    <img src="{{ asset('storage/' . $recent->image_path) }}"
                                        alt="{{ $recent->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy" decoding="async">
                                    @else
                                    <div
                                        class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h4
                                        class="font-bold text-gray-800 text-sm mb-1 group-hover:text-accent transition-colors line-clamp-2">
                                        {{ $recent->title }}
                                    </h4>
                                    <span class="text-xs text-gray-500">
                                        <i class="fa-regular fa-calendar ml-1"></i>
                                        {{ $recent->created_at->format('Y-m-d') }}
                                    </span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-primary text-white p-8 rounded-xl shadow-lg relative overflow-hidden">

                        <h3 class="text-xl font-bold mb-4">هل تحتاج استشارة؟</h3>
                        <p class="text-gray-300 text-sm mb-6">تواصل مع خبرائنا اليوم لمناقشة مشروعك القادم.</p>
                        <a href="{{ route('contact') }}"
                            class="block w-full text-center bg-white text-primary font-bold py-3 rounded-lg hover:bg-accent hover:text-white transition-colors">
                            تواصل معنا
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
{{--
    <section class="py-16">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="bg-white rounded-lg shadow-sm p-8">
                @if ($post->image_path)
                    <div class="mb-8 rounded-lg overflow-hidden h-[400px]">
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
class="w-full h-full object-cover">
</div>
@endif

<div class="flex items-center text-sm text-gray-500 mb-6 border-b pb-4">
    <div class="flex items-center ml-6">
        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
            </path>
        </svg>
        {{ $post->published_at ? $post->published_at->format('Y-m-d') : $post->created_at->format('Y-m-d') }}
    </div>
</div>

<div class="prose max-w-none text-gray-700 leading-relaxed">
    {!! nl2br(e($post->content)) !!}
</div>
</div>
</div>
</section> --}}
@endsection