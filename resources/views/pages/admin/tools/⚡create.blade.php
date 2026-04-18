<?php
use Livewire\Component;
;
use App\Models\Category;
use App\Models\Tool;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;

new class extends Component
{
    public $categories = [];
    public $currentYear = 0;

    public function mount() {
        $this->currentYear = (int) date('Y');
        $this->categories = Category::all();
        $this->dispatch('currently-page', current: request()->segment(1));
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
        Tool::create([...$this->validate(), 'cover_path' => $this->fileRealPath]);
        
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
    <x-header>
        <x-header-info title="barang Baru" desc="masukkan data barang baru ke dalam sistem"/>
    </x-header>
    <x-main-form>
        <form wire:submit="save" class="w-full">
            @csrf
            <div class="">
                <livewire:file-input label="Cover barang" acceptExtention="image/png, image/jpeg, image/jpg" />
                <x-input-error :messages="$errors->get('fileRealPath')" />
            </div>
            
            <div class="mt-3">
                <x-input-label for="title">Judul barang</x-input-label>
                <x-text-input type="text" wire:model.live="name_tool" id="title" placeholder="Masukkan judul barang"/>
                <x-input-error :messages="$errors->get('name_tool')" />
            </div>
{{-- 
            <div class="mt-3">
                <x-input-label for="author">Nama Penulis</x-input-label>
                <x-text-input type="text" wire:model.live="author" id="author" placeholder="Masukkan pengarang barang ini"/>
                <x-input-error :messages="$errors->get('author')" />
            </div>

            <div class="mt-3">
                <x-input-label for="year">Tahun Terbit</x-input-label>
                <x-text-input type="number" wire:model.live="year_published" id="year" placeholder="Masukkan tahun terbit barang ini"/>
                <x-input-error :messages="$errors->get('year_published')"/>
            </div> --}}

            <div class="mt-3">
                <x-input-label for="qty">Jumlah barang</x-input-label>
                <x-text-input type="number" wire:model.live="qty" id="qty" placeholder="Tetapkan jumlah barang yang tersedia"/>
                <x-input-error :messages="$errors->get('qty')"/>
            </div>

            <div class="mt-3" wire:ignore>
                <x-input-label for="category">Kategori</x-input-label>
                <x-indicator-information-ping>Penambahan kategori harus memiliki huruf</x-indicator-information-ping>
                <livewire:tom-select-selection placeholder="Pilih kategori barang" wire:model.live="category_id" id="categoryForm">
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