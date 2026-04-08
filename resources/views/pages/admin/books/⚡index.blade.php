<?php

use Livewire\Component;
use App\Models\Book;
use Livewire\Attributes\On;
use App\Models\Category;

new class extends Component
{
    public $searchKey = '';
    public $categories = [];
    public $perPage = [0 => 4];
    
    public $activeCategory = 0;
    #[On('select-filter')]
    public function selectFilter($param) {
        $this->activeCategory = $param;
    }
    
    public function mount() {
        $this->categories = Category::all();
        foreach ($this->categories as $c) {
            $this->perPage[$c->id] = 1;
        }
    }
    
    public function render() {
        $query = Book::with('category')->whereAny(['title', 'author'], 'LIKE', "%{$this->searchKey}%");
        if($this->activeCategory) $query->where('category_id', $this->activeCategory);
        $book = $query->paginate($this->perPage[$this->activeCategory]);
        return view('pages.admin.books.⚡index', ['books' => $book]);
    }

    #[On('search-key')]
    public function search($searchKey) {
        $this->searchKey = $searchKey;
    }

    public function loadMore() {
        $this->perPage[$this->activeCategory] += 1;
    }
};
?>

<div>
    <x-slot name="headerFilter">
        <div class="flex px-2 max-w-sm">
            <livewire:selection-filter :dataFilters="$categories" id="categoryIndex" />
        </div>
    </x-slot>

    <x-header class="border-b">
        <x-header-info title="manajemen buku" desc="Kelola seluruh data buku perpustakaan" />

        <x-add-navigate i="bi bi-plus" label="tambah buku" :href="route('books.create')"/>
    </x-header>

    <div class="overflow-x-auto mt-5">
        <table class="w-full border border-gray-200 border-collapse text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border text-left">Cover</th>
                    <th class="px-4 py-2 border text-left">Judul Buku</th>
                    <th class="px-4 py-2 border text-left">Nama Penulis</th>
                    <th class="px-4 py-2 border text-left">Kategori</th>
                    <th class="px-4 py-2 border text-center">Stok Tersedia</th>
                    <th class="px-4 py-2 border text-center">Total Stok</th>
                    <th class="px-4 py-2 border text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($books as $book)
                    <tr class="hover:bg-gray-50 transition">
                        
                        {{-- Cover --}}
                        <td class="px-4 py-2 border w-24">
                            <img 
                                src="{{ asset('storage/' . $book->cover_path) }}" 
                                alt="{{ $book->title }}"
                                class="w-16 h-20 object-cover rounded shadow"
                            >
                        </td>

                        {{-- Judul --}}
                        <td class="px-4 py-2 border font-medium">
                            {{ $book->title }}
                        </td>

                        {{-- Penulis --}}
                        <td class="px-4 py-2 border">
                            {{ $book->author }}
                        </td>

                        {{-- Kategori --}}
                        <td class="px-4 py-2 border">
                            {{ $book->category->category_name }}
                        </td>

                        {{-- Stok --}}
                        <td class="px-4 py-2 border text-center">
                            {{ $book->qty }}
                        </td>

                        {{-- Total --}}
                        <td class="px-4 py-2 border text-center">
                            {{ $book->qty }}
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-2 border text-center">
                            <button class="text-blue-600 hover:underline">
                                Detail
                            </button>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                
                                <div class="text-4xl text-gray-400">📚</div>

                                <h2 class="text-base font-semibold text-gray-700">
                                    Data Buku tidak ditemukan
                                </h2>

                                <p class="text-sm text-gray-500">
                                    @if($searchKey)
                                        Tidak ada hasil untuk
                                        <span class="font-medium text-gray-700">
                                            "{{ $searchKey }}"
                                        </span>
                                    @endif

                                    @if($activeCategory)
                                        di kategori
                                        <span class="font-medium text-gray-700">
                                            {{ $categories->firstWhere('id', $activeCategory)['category_name'] ?? 'tidak diketahui' }}
                                        </span>
                                    @else
                                        di semua kategori
                                    @endif
                                </p>

                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <x-has-more-page :datas="$books" :target="$activeCategory" />
    </div>
</div>