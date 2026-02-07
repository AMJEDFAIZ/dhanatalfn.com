@props(['title', 'subtitle' => null, 'centered' => true])

<div class="{{ $centered ? 'text-center' : '' }} mb-12" data-aos="fade-up">
    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
    @if($subtitle)
        <p class="text-xl text-gray-600 max-w-2xl {{ $centered ? 'mx-auto' : '' }}">
            {{ $subtitle }}
        </p>
    @endif
    <div class="w-24 h-1 bg-blue-600 {{ $centered ? 'mx-auto' : '' }} mt-4 rounded-full"></div>
</div>
