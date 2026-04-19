@props ([
    'href' => null,
    'click' => null,
    'class' => "relative z-50 px-4 py-2 rounded-lg cursor-pointer shadow hover:shadow-xl transition duration-200",
    'i',
])

@if ($href)
    <a href="{{$href}}" wire:navigate class="{{$class}}">
        <i class="{{$i}}"></i>
    </a>
@else 
    <button {{$attributes->merge(['type' => 'button', 'class' => $class])}}>
        <i class="{{$i}}"></i>
    </button>
@endif
