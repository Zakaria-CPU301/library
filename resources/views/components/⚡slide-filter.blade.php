<?php
use Livewire\Component;
use Livewire\Attributes\Modelable;

new class extends Component
{
    public $toggleButton = [];
    public $value;

    public function filterUser($toggleId) {
        $this->value = $toggleId;
        $this->dispatch('slide-filter', $toggleId);
    }
};
?>

<div wire:ignore.self class="rounded-lg pr-5">
    <div class="flex gap-2 overflow-x-scroll text-sm pl-5">
        <button type="button" wire:click="filterUser(0)" class="{{$value == 0 ? 'bg-gray-900 text-white' : 'bg-gray-500'}} px-4 py-2 rounded-lg inline-flex font-bold capitalize cursor-pointer hover:bg-gray-400 text-white duration-100">{{ __('semua') }}</button>
        @foreach ($toggleButton as $toggle)
            <button type="button"  wire:click="filterUser({{$toggle->id}})" class="button-dynamic {{$value == $toggle->id ? 'bg-gray-900 text-white' : 'bg-slate-500'}} whitespace-nowrap px-4 py-2 rounded-lg inline-flex font-bold capitalize cursor-pointer hover:bg-gray-400 text-white duration-200 ">
                {{ $toggle->category_name ?? $toggle->collection_name }}
            </button>
        @endforeach
    </div>
</div>