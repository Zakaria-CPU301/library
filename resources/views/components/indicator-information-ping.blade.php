@props([
    'bgIndicator',
])

<div {{$attributes->class('flex h-10 items-center py-2 ms-2')}}>
    <div class="p-1.5 me-2 self-center {{$bgIndicator}} rounded-full animate-ping"></div>
    <span class="text-xs">{{$slot}}</span>
</div>