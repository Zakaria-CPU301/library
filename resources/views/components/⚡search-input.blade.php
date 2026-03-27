<?php

use Livewire\Component;
use Livewire\Attributes\Validate;

new class extends Component
{
    #[Validate('required')]
    public $search = '';

    public function updated() {
        $this->dispatch('search-key', trim($this->search, ' '));
    }
};
?>

<form wire:submit.prevent="save" class="w-full self-center max-w-sm">
    <label for="search" class="sr-only">Search</label>

    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex ps-3 pe-px">
            <x-loading-state-session class="w-4 h-4 self-center" wire:loading wire:target="search"/>
            <svg class="w-4 h-4 text-body self-center" wire:loading.remove wire:target="search" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
            </svg>
        </div>

        <input 
            type="search"
            id="search"
            wire:model.live="search"
            placeholder="Search"
            class="block w-full rounded-base border border-default-medium bg-neutral-secondary-medium 
                p-3 pl-9 text-sm text-heading placeholder:text-body shadow-xs 
                focus:border-brand focus:ring-brand"
        />
    </div>
</form>