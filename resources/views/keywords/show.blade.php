@extends('layouts.site')

@section('title', $keyword->name)
@section('meta_title', $meta_title)
@section('meta_description', $meta_description)
@section('meta_keywords', $meta_keywords)

@section('content')
<section class="relative h-[35vh] min-h-[280px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/img/hero.webp') }}" alt="{{ $keyword->name }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-primary/80 mix-blend-multiply"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 text-center pt-20">
        <h1 class="text-3xl md:text-5xl font-bold mb-3 animate-fade-in-up text-accent">{{ $keyword->name }}</h1>
        <nav class="flex justify-center items-center gap-2 text-sm md:text-base text-gray-300 animate-fade-in-up animation-delay-200">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-accent/4 transition-colors py-1 relative after:absolute after:bottom-0 after:right-0 after:w-full after:h-0.5 after:bg-accent after:transition-all">
                {{ $keyword->name }}
            </span>
        </nav>
    </div>
</section>

<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        @if (!empty($keyword->description))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="text-gray-700 leading-relaxed">{{ $keyword->description }}</div>
        </div>
        @endif

        <div class="flex flex-wrap gap-2 mb-8">
            <a href="{{ route('keywords.show', $keyword->slug) }}"
                class="px-4 py-2 rounded-full text-sm font-bold border transition-colors {{ $type === '' ? 'bg-primary text-white border-primary' : 'bg-white text-primary border-gray-200 hover:bg-gray-100' }}">
                الكل
            </a>
            <a href="{{ route('keywords.show', [$keyword->slug, 'type' => 'services']) }}"
                class="px-4 py-2 rounded-full text-sm font-bold border transition-colors {{ $type === 'services' ? 'bg-primary text-white border-primary' : 'bg-white text-primary border-gray-200 hover:bg-gray-100' }}">
                الخدمات ({{ (int) $keyword->services_count }})
            </a>
            <a href="{{ route('keywords.show', [$keyword->slug, 'type' => 'projects']) }}"
                class="px-4 py-2 rounded-full text-sm font-bold border transition-colors {{ $type === 'projects' ? 'bg-primary text-white border-primary' : 'bg-white text-primary border-gray-200 hover:bg-gray-100' }}">
                المشاريع ({{ (int) $keyword->projects_count }})
            </a>
            <a href="{{ route('keywords.show', [$keyword->slug, 'type' => 'blog']) }}"
                class="px-4 py-2 rounded-full text-sm font-bold border transition-colors {{ $type === 'blog' ? 'bg-primary text-white border-primary' : 'bg-white text-primary border-gray-200 hover:bg-gray-100' }}">
                المدونة ({{ (int) $keyword->blog_posts_count }})
            </a>
        </div>

        @if ($items)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($items as $item)
            @if ($type === 'services')
            <a href="{{ route('services.show', $item->slug) }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
                <div class="relative w-full aspect-[16/10] bg-gray-100 overflow-hidden">
                    @if (!empty($item->image_path))
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" decoding="async">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary text-3xl font-bold">
                        {{ Str::substr($item->title, 0, 1) }}
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="text-primary font-bold text-lg mb-2">{{ $item->title }}</div>
                    <div class="text-gray-600 text-sm line-clamp-3">{{ Str::limit(strip_tags($item->description ?? ''), 140) }}</div>
                </div>
            </a>
            @elseif ($type === 'projects')
            <a href="{{ route('projects.show', $item->slug) }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
                <div class="relative w-full aspect-[16/10] bg-gray-100 overflow-hidden">
                    @if (!empty($item->main_image))
                    <img src="{{ asset('storage/' . $item->main_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" decoding="async">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary text-3xl font-bold">
                        {{ Str::substr($item->title, 0, 1) }}
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="text-primary font-bold text-lg mb-2">{{ $item->title }}</div>
                    <div class="text-gray-600 text-sm line-clamp-3">{{ Str::limit(strip_tags($item->description ?? ''), 140) }}</div>
                </div>
            </a>
            @else
            <a href="{{ route('blog.show', $item->slug) }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
                <div class="relative w-full aspect-[16/10] bg-gray-100 overflow-hidden">
                    @if (!empty($item->image_path))
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" decoding="async">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary text-3xl font-bold">
                        {{ Str::substr($item->title, 0, 1) }}
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="text-primary font-bold text-lg mb-2">{{ $item->title }}</div>
                    <div class="text-gray-600 text-sm line-clamp-3">{{ Str::limit(strip_tags($item->content ?? ''), 140) }}</div>
                </div>
            </a>
            @endif
            @endforeach
        </div>
        <div class="mt-8">
            {{ $items->links('partials.pagination') }}
        </div>
        @else
        @if ($services->count() > 0)
        <div class="mb-10">
            <div class="flex justify-between items-end mb-4">
                <div class="text-xl font-bold text-primary">الخدمات</div>
                <a href="{{ route('keywords.show', [$keyword->slug, 'type' => 'services']) }}" class="text-accent font-bold hover:underline">عرض الكل</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($services as $item)
                <a href="{{ route('services.show', $item->slug) }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
                    <div class="relative w-full aspect-[16/10] bg-gray-100 overflow-hidden">
                        @if (!empty($item->image_path))
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" decoding="async">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary text-3xl font-bold">
                            {{ Str::substr($item->title, 0, 1) }}
                        </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="text-primary font-bold text-lg mb-2">{{ $item->title }}</div>
                        <div class="text-gray-600 text-sm line-clamp-3">{{ Str::limit(strip_tags($item->description ?? ''), 140) }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if ($projects->count() > 0)
        <div class="mb-10">
            <div class="flex justify-between items-end mb-4">
                <div class="text-xl font-bold text-primary">المشاريع</div>
                <a href="{{ route('keywords.show', [$keyword->slug, 'type' => 'projects']) }}" class="text-accent font-bold hover:underline">عرض الكل</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($projects as $item)
                <a href="{{ route('projects.show', $item->slug) }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
                    <div class="relative w-full aspect-[16/10] bg-gray-100 overflow-hidden">
                        @if (!empty($item->main_image))
                        <img src="{{ asset('storage/' . $item->main_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" decoding="async">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary text-3xl font-bold">
                            {{ Str::substr($item->title, 0, 1) }}
                        </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="text-primary font-bold text-lg mb-2">{{ $item->title }}</div>
                        <div class="text-gray-600 text-sm line-clamp-3">{{ Str::limit(strip_tags($item->description ?? ''), 140) }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if ($posts->count() > 0)
        <div class="mb-2">
            <div class="flex justify-between items-end mb-4">
                <div class="text-xl font-bold text-primary">المدونة</div>
                <a href="{{ route('keywords.show', [$keyword->slug, 'type' => 'blog']) }}" class="text-accent font-bold hover:underline">عرض الكل</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts as $item)
                <a href="{{ route('blog.show', $item->slug) }}" class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
                    <div class="relative w-full aspect-[16/10] bg-gray-100 overflow-hidden">
                        @if (!empty($item->image_path))
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" decoding="async">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-primary/10 text-primary text-3xl font-bold">
                            {{ Str::substr($item->title, 0, 1) }}
                        </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="text-primary font-bold text-lg mb-2">{{ $item->title }}</div>
                        <div class="text-gray-600 text-sm line-clamp-3">{{ Str::limit(strip_tags($item->content ?? ''), 140) }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if ($services->count() === 0 && $projects->count() === 0 && $posts->count() === 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
            لا توجد عناصر مرتبطة بهذه الكلمة حتى الآن.
        </div>
        @endif
        @endif
    </div>
</section>
@endsection