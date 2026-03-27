<?php

use Livewire\Component;
use App\Models\Book;
use App\Models\Category;
use Livewire\Attributes\On;

new class extends Component
{
    public $book;
    public $categories = [];
    public $perPage = [0 => 8];
    public $activeCategory = 0;

    public function mount()
    {
        $this->categories = Category::all();
        foreach ($this->categories as $c) {
            $this->perPage[$c->id] = 8;
        }
    }

    public function loadMore()
    {
        $this->perPage[$this->activeCategory] += 8;
    }

    public $searchKey = '';
    #[On('search-key')]
    public function search($key)
    {
        $this->searchKey = $key;
    }

    public function render()
    {
        $query = Book::with('category')->whereAny(['title', 'author'], 'LIKE', '%' . $this->searchKey . '%');
        if ($this->activeCategory != 0) $query->where('category_id', $this->activeCategory);
        $books = $query->paginate($this->perPage[$this->activeCategory]);

        return view('pages.admin.books.⚡index', ['books' => $books]);
    }

    public $isActive = '';
    public function filterUser($toggleId)
    {
        $this->isActive = $toggleId;
    }
};
?>
<div class="px-10">
    <div class="flex justify-center py-5">
        <livewire:search-input />
    </div>

    <livewire:nav-slide-filter :toggleButton="$categories" wire:model.live="activeCategory" />

    <x-session-success />
    <div class="flex flex-col items-center mt-5 px-6">

        {{-- GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full">

            @forelse ($books as $book)

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col transition hover:shadow-md">

                {{-- Image --}}
                <div class="w-full h-56 p-3">
                    <img
                        src="{{ asset('storage/' . $book->cover_path) }}"
                        alt="{{ $book->title }}"
                        class="w-full h-full object-cover rounded-lg" />
                </div>

                {{-- Content --}}
                <div class="px-4 pb-4 flex flex-col flex-1">

                    <h5 class="text-base font-semibold text-gray-800 line-clamp-2">
                        {{ $book->title }}
                    </h5>

                    <p class="text-sm text-gray-500 mt-1 mb-4">
                        {{ $book->author }}
                    </p>

                    {{-- Button --}}
                    <div class="mt-auto">
                        <button class="w-full flex items-center justify-center gap-2 text-sm bg-gray-100 border border-gray-300 hover:bg-gray-200 rounded-lg px-4 py-2 transition">
                            Pinjam
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-width="2" d="M5 12h14m0 0-4 4m4-4-4-4" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                <div class="text-gray-400 text-5xl mb-4">
                    📚
                </div>

                <h2 class="text-lg font-semibold text-gray-700">
                    Data 
                    @if($activeCategory != 0)
                        buku dengan kategori {{$categories->find($activeCategory)['category_name']}}
                    @else 
                        semua buka
                    @endif
                    tidak ditemukan
                </h2>

                @if(trim($searchKey, ' ') != '')
                    <p class="text-sm text-gray-500 mt-1">
                        Tidak ada hasil untuk
                        <span class="font-medium text-gray-700">
                            "{{$this->searchKey}}"
                        </span>
                    </p>
                @endif
            </div>
            @endforelse
        </div>

        <x-loading-indicator target="activeCategory" />
        
        <div
            wire:loading.remove
            wire:target="activeCategory"
            class="w-full flex justify-center py-6"
            id="spinner-load-data">
            @if ($books->hasMorePages())

            <div class="flex items-center justify-center">
                <button type="button" wire:click="loadMore" id="loadClick" hidden></button>

                <x-loading-state-session
                    class="w-8 h-8"
                    wire:loading
                    wire:target="loadMore" />
            </div>

            @else
                <div class="text-sm font-medium text-gray-500">
                    Sudah di ujung halaman
                </div>
            @endif
        </div>

    </div>
</div>