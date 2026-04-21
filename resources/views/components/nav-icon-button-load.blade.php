@props([
    'confirm' => null,
    'target',
    'i',
    'background' => null,
])

<div {{$attributes->class('w-10 h-9 flex justify-center items-center')}}>
    <button
        wire:click="{{$target}}" type="button"
        @if ($confirm)
            wire:confirm="{{$confirm}}" 
        @endif
        wire:loading.remove wire:target="{{$target}}" 
        class="{{$background ? $background : 'bg-white'}} relative z-50 px-3 py-1.5 shadow rounded-lg cursor-pointer hover:scale-110 transition duration-150">
        <i class="{{$i}}"></i>
    </button>
    <x-loading-state-session class="h-5 w-5" wire:loading wire:target="{{$target}}" /> 
</div>