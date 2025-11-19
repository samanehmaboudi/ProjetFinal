@props([
    'label' => 'Click Me',
    'type' => 'button',     // button | submit | href
    'route' => null,
    'rounded' => 'lg'
])

@php
    $classes = "bg-primary text-white font-bold py-2 px-4 rounded-{$rounded} 
                hover:bg-primary-hover transition-colors duration-300 block text-center";

    // Si type = href → calcul du lien
    $href = $route ? route($route) : '#';
@endphp

@if ($type === 'href')
    {{-- Génère un <a> --}}
    <a href="{{ $href }}"
       {{ $attributes->merge(['class' => $classes]) }}>
        {{ $label }}
    </a>

@else
    {{-- Génère un <button> --}}
    <button type="{{ $type }}"
        {{ $attributes->merge(['class' => $classes]) }}>
        {{ $label }}
    </button>
@endif
