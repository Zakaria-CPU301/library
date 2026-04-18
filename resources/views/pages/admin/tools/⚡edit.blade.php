<?php

use Livewire\Component;
use App\Models\Tool;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

new class extends Component
{
    public $tools = '';
    public $categories;
    public $toolId = 0;

    public function mount($toolId) {
        $this->toolId = $toolId;
        $this->categories = Category::all();
        $this->tools = Tool::findOrFail($toolId);

        $this->name_tool = $this->tools->name_tool;
        $this->qty = $this->tools->qty;
        $this->description_tool = $this->tools->description_tool;
        $this->category_id = $this->tools->category->category_name;
    }

    #[Validate('required')]
    public $fileRealPath;
    #[On('file-upload')]
    public function cover($path) {
        $this->fileRealPath = $path;
        $this->validateOnly('fileRealPath');
    }

    #[Validate(['required', 'max:255'])]
    public $name_tool = '';
    #[Validate(['required', 'numeric', 'min:1'])]
    public $qty = '';
    #[Validate(['required'])]
    public $category_id = '';
    #[Validate('required')]
    public $description_tool = '';

    public function tool() {
        $category = is_numeric($this->category_id)
        ? Category::findOrFail($this->category_id)
        : Category::firstOrCreate(['category_name' => $this->category_id]);
        $this->category_id = $category->id;
        Tool::findOrFail($this->toolId)->update([...$this->validate(), 'cover_path' => $this->fileRealPath]);
        
        session()->flash('success', 'Added a tool successfully');
        $this->redirectRoute('tools.admin', navigate: true);
    }

    public function save() {
        $this->validate();
        $this->tool();
    }
};
?>

<div>
    <x-main-form>
        <form wire:submit="save" class="w-full">
            @csrf
            <div class="">
                <x-input-label for="uploadCover">Gambar barang Sebelumnya</x-input-label>
                <img src="{{asset('storage/' . $tools->cover_path)}}" alt="{{$tools->name_tool}}" class="h-40">
            </div>

            <div class="mt-3">
                <livewire:file-input label="Gambar barang" acceptExtention="image/png, image/jpeg, image/jpg" id="uploadCover" />
                <x-input-error :messages="$errors->get('fileRealPath')" />
            </div>
            
            <div class="mt-3">
                <x-input-label for="title">Judul barang</x-input-label>
                <x-text-input type="text" wire:model.live="name_tool" id="title" placeholder="Masukkan judul barang"/>
                <x-input-error :messages="$errors->get('name_tool')" />
            </div>

            <div class="mt-3">
                <x-input-label for="qty">Jumlah barang</x-input-label>
                <x-text-input type="number" wire:model.live="qty" id="qty" placeholder="Tetapkan jumlah barang yang tersedia"/>
                <x-input-error :messages="$errors->get('qty')"/>
            </div>

            <div class="mt-3" wire:ignore>
                <x-input-label for="category">Kategori</x-input-label>
                <x-indicator-information-ping>Penambahan kategori harus memiliki huruf</x-indicator-information-ping>
                <livewire:tom-select-selection placeholder="{{$tools->category->category_name}}" wire:model.live="category_id" id="categoryForm">
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->category_name}}</option>
                    @endforeach
                </livewire:tom-select-selection>
            </div>
            <x-input-error :messages="$errors->get('category_id')"/>
            <div class="mt-3">
                <x-input-label value="Deskripsi barang" />
                <textarea id="message" rows="4" wire:model.live="description_tool" 
                class="border border-gray-300 text-heading text-sm rounded-base mt-2 focus:border-indigo-500 focus:ring-indigo-500 block w-full px-2 py-2 placeholder:text-sm" 
                placeholder="Masukkan deskripsi untuk barang ini..."></textarea>
                <x-input-error :messages="$errors->get('description_tool')" />
            </div>

            <div class="flex justify-end mt-4">
                <x-primary-button>
                    {{__('tambah barang')}}
                </x-primary-button>
            </div>
        </form>
    </x-main-form>
</div>