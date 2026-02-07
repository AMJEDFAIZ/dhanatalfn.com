@props(['image' => null, 'title', 'subtitle' => null, 'link' => null, 'linkText' => 'تفاصيل', 'aos' => 'fade-up', 'delay' => 0])

<div class="group relative overflow-hidden rounded-lg shadow-md h-80" data-aos="{{ $aos }}" data-aos-delay="{{ $delay }}">
    @if($image)
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
    @else
        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
            <span class="text-gray-400">لا توجد صورة</span>
        </div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-primary/90 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
        <h3 class="text-white text-xl font-bold mb-1">{{ $title }}</h3>
        @if($subtitle)
            <p class="text-gray-200 text-sm mb-3">{{ $subtitle }}</p>
        @endif
        @if($link)
            <a href="{{ $link }}" class="inline-block bg-secondary text-primary text-sm font-bold px-4 py-2 rounded hover:bg-white transition w-fit">{{ $linkText }}</a>
        @endif
    </div>
</div>
