@props(['active' => false, 'href'])

@php
$base = 'relative flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all duration-200';

$activeClass = 'bg-blue-50 text-blue-600 font-medium';

$inactiveClass = 'text-gray-500 hover:bg-gray-100 hover:text-gray-700';

$classes = $active 
    ? "$base $activeClass" 
    : "$base $inactiveClass";
@endphp

<a 
    wire:navigate 
    href="{{ $href }}" 
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</a>