<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Category;
use App\Models\Book;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;

new #[Layout('layouts.form')] class extends Component
{
    public $categories = [];
    public $currentYear = 0;

    public function mount() {
        $this->currentYear = (int) date('Y');
        $this->categories = Category::all();
        $this->dispatch('currently-page', current: request()->segment(1));
    }

    public $fileRealPath;
    #[On('file-upload')]
    public function cover($path) {
        $this->fileRealPath = $path;
        $this->validateOnly('fileRealPath');
    }

    public function rules() {
        return [
            'year_published' => ['required', 'numeric', 'min:1500', 'max:' . $this->currentYear],
        ];
    }
    
    #[Validate(['required', 'max:255'])]
    public $title = '';
    #[Validate(['required', 'max:255'])]
    public $author = '';
    public $year_published = 1500;
    #[Validate(['required', 'numeric', 'min:1'])]
    public $qty = '';
    #[Validate(['required', 'in:indonesian,english'])]
    public $lang = '';
    #[Validate(['required'])]
    public $category_id = '';

    public function updated($property) {
        $this->validateOnly($property, array_merge($this->getRules(), $this->rules()));
    }

    public function Book() {
        $category = is_numeric($this->category_id) 
        ? Category::findOrFail($this->category_id) 
        : Category::firstOrCreate(['category_name' => $this->category_id]);
        $this->category_id = $category->id;
        Book::create([...$this->validate(), 'cover_path' => $this->fileRealPath]);
        
        session()->flash('success', 'Added a book successfully');
        $this->redirectRoute('books.index');
    }

    public function save() {
        $this->validate(array_merge($this->getRules(), $this->rules()));
        $this->Book();
    }
};
?>

<div>
    <x-header>
        <x-header-info title="Buku Baru" desc="masukkan data buku baru ke dalam sistem"/>
    </x-header>

    <x-main-form>
        <form wire:submit="save" class="w-full">
            @csrf
            <div class="">
                <livewire:file-input label="Cover Buku" acceptExtention="image/png, image/jpeg, image/jpg" />
                <x-input-error :messages="$errors->get('fileRealPath')" />
            </div>
            
            <div class="mt-3">
                <x-input-label for="title">Judul Buku</x-input-label>
                <x-text-input type="text" wire:model.live="title" id="title" placeholder="Masukkan judul buku"/>
                <x-input-error :messages="$errors->get('title')" />
            </div>

            <div class="mt-3">
                <x-input-label for="author">Nama Penulis</x-input-label>
                <x-text-input type="text" wire:model.live="author" id="author" placeholder="Masukkan pengarang buku ini"/>
                <x-input-error :messages="$errors->get('author')" />
            </div>

            <div class="mt-3">
                <x-input-label for="year">Tahun Terbit</x-input-label>
                <x-text-input type="number" wire:model.live="year_published" id="year" placeholder="Masukkan tahun terbit buku ini"/>
                <x-input-error :messages="$errors->get('year_published')"/>
            </div>

            <div class="mt-3">
                <x-input-label for="qty">Jumlah Buku</x-input-label>
                <x-text-input type="number" wire:model.live="qty" id="qty" placeholder="Tetapkan jumlah buku yang tersedia"/>
                <x-input-error :messages="$errors->get('qty')"/>
            </div>

            <div class="mt-3" wire:ignore>
                <x-input-label for="lang">Bahasa</x-input-label>
                <x-indicator-information-ping>Apakah bahasa bisa di tambah?</x-indicator-information-ping>
                <livewire:tom-select-selection placeholder="Pilih bahasa buku" wire:model.live="lang" id="lang">
                    <option value="english">Inggris</option>
                    <option value="indonesian">Indonesia</option>
                </livewire:tom-select-selection>
            </div>
            <x-input-error :messages="$errors->get('lang')"/>

            <div class="mt-3" wire:ignore>
                <x-input-label for="category">Kategori</x-input-label>
                <x-indicator-information-ping>Penambahan kategori harus memiliki huruf</x-indicator-information-ping>
                <livewire:tom-select-selection placeholder="Pilih kategori buku" wire:model.live="category_id" id="categoryForm">
                    @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->category_name}}</option>
                    @endforeach
                </livewire:tom-select-selection>
            </div>
            <x-input-error :messages="$errors->get('category_id')"/>

            <div class="flex justify-end mt-4">
                <x-primary-button>
                    {{__('tambah buku')}}
                </x-primary-button>
            </div>
        </form>
    </x-main-form>
</div>