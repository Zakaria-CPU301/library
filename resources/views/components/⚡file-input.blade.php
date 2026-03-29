<?php

use Livewire\Component;
use Livewire\Attributes\Modelable;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

new class extends Component
{
    public $label = '';
    public $acceptExtention = '';

    use WithFileUploads;
    public $file;

    public function fileRules() {
        return ['file' => ['required', 'file', 'mimes:' . str_replace(['.', 'image/', ' '], '', $this->acceptExtention)]];
    }

    public function updatedFile(): void {
        $this->validate($this->fileRules());
        $file = $this->file->store('file-upload', 'public');
        $this->dispatch('file-upload', path: $file);
    }
};
?>

<div class="flex flex-col">
    <x-input-label for="file" value="{{$label}}" class="mt-2" />
    <div class="flex items-center border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-2 w-full">
        <input type="file" wire:model="file" accept="{{$acceptExtention}}" class="block px-4 py-2 w-full" />
        <x-loading-state-session class="h-5 w-5" wire:loading wire:target="file" />
    </div>
    <x-input-error :messages="$errors->get('file')"/>
</div>