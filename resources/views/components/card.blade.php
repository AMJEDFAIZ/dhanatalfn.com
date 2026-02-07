@props(['image' => null, 'title', 'description' => null, 'link' => null, 'linkText' => 'المزيد', 'aos' => 'fade-up', 'delay' => 0])

<div class="bg-white rounded-lg shadow-sm hover:shadow-md transition group h-full flex flex-col" data-aos="{{ $aos }}" data-aos-delay="{{ $delay }}">
    @if($image)
        <div class="h-48 overflow-hidden rounded-t-lg">
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
        </div>
    @endif
    
    <div class="p-6 flex-1 flex flex-col">
        <h3 class="text-xl font-bold mb-3 group-hover:text-blue-600 transition">{{ $title }}</h3>
        
        @if($description)
            <div class="text-gray-600 mb-4 flex-1">
                {{ Str::limit(strip_tags($description), 100) }}
            </div>
        @endif
        
        @if($link)
            <div class="mt-auto">
                <a href="{{ $link }}" class="text-blue-600 font-semibold hover:text-blue-700 inline-flex items-center">
                    {{ $linkText }}
                    <svg class="w-4 h-4 mr-1 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>
