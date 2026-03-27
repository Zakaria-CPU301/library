<?php
use Livewire\Component;
use Livewire\Attributes\Modelable;

new class extends Component
{
    public $toggleButton = [];
    
    #[Modelable]
    public $value = 0;

    public function filterUser($toggleId) {
        $this->value = $toggleId;
    }
};
?>

<div id="nav-collection" wire:ignore.self class="mt-1 py-2 px-10 sticky top-5 duration-500 rounded-lg">
    <div class="flex gap-5 overflow-x-scroll py-2">
        <button type="button" wire:click="filterUser(0)" class="{{$value == 0 ? 'bg-gray-900 text-white' : 'bg-[rgb(51,255,255)]'}} px-4 py-2 rounded-lg inline-flex font-bold capitalize cursor-pointer hover:bg-slate-700 hover:text-white duration-100">{{ __('semua') }}</button>
        @foreach ($toggleButton as $toggle)
            <button type="button"  wire:click="filterUser({{$toggle->id}})" class="button-dynamic {{$value == $toggle->id ? 'bg-gray-900 text-white' : 'bg-[rgb(51,255,255)]'}} whitespace-nowrap px-4 py-2 rounded-lg inline-flex font-bold capitalize cursor-pointer hover:bg-slate-700 hover:text-white duration-200 ">
                {{ $toggle->category_name ?? $toggle->collection_name }}
            </button>
        @endforeach
    </div>
</div>