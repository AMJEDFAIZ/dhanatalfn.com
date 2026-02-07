@props(['type' => 'submit', 'href' => null, 'color' => 'primary', 'full' => false])

@php
    $colors = [
        'primary' => 'bg-secondary text-primary hover:bg-white',
        'secondary' => 'border-2 border-white text-white hover:bg-white hover:text-primary',
        'outline' => 'border border-primary text-primary hover:bg-primary hover:text-white',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
    ];
    $classes = $colors[$color] ?? $colors['primary'];
    $classes .= $full ? ' w-full block text-center' : '';
    $classes .= ' font-bold px-8 py-3 rounded-md transition inline-block';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
