<?php

use Livewire\Component;
use App\Models\Tool;
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
        $query = Tool::with('category')->whereAny(['name_tool'], 'LIKE', "%{$this->searchKey}%");
        if($this->activeCategory) $query->where('category_id', $this->activeCategory);
        $tool = $query->paginate($this->perPage[$this->activeCategory]);
        return view('pages.admin.tools.⚡index', ['tools' => $tool]);
    }

    #[On('search-key')]
    public function search($searchKey) {
        $this->searchKey = $searchKey;
    }

    public function loadMore() {
        $this->perPage[$this->activeCategory] += 1;
    }

    public function editTool($toolId) {
        $this->redirectRoute('tools.edit', ['toolId' => $toolId], false, true);
    }
};
?>

<div>
    <x-slot name="searchEngine"></x-slot>
    <div class="flex px-2 max-w-sm">
        <livewire:selection-filter :dataFilters="$categories" id="categoryIndex" />
    </div>

    <x-header class="border-b">
        <x-header-info title="manajemen barang" desc="Kelola seluruh data barang perpustakaan" />
        <x-add-navigate i="bi bi-plus" label="tambah barang" :href="route('tools.create')"/>
    </x-header>

    <div class="overflow-x-auto mt-5">
        <table class="w-full border border-gray-200 border-collapse text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border text-left">Cover</th>
                    <th class="px-4 py-2 border text-left">Judul barang</th>
                    <th class="px-4 py-2 border text-left">Kategori</th>
                    <th class="px-4 py-2 border text-center">Stok Tersedia</th>
                    <th class="px-4 py-2 border text-center">Total Stok</th>
                    <th class="px-4 py-2 border text-center" colspan="2">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($tools as $tool)
                    <tr class="hover:bg-gray-50 transition">
                        
                        <td class="px-4 py-2 border w-24">
                            <img 
                                src="{{ asset('storage/' . $tool->cover_path) }}" 
                                alt="{{ $tool->title }}"
                                class="w-16 h-20 object-cover rounded shadow"
                            >
                        </td>

                        <td class="px-4 py-2 border font-medium">
                            {{ $tool->name_tool }}
                        </td>

                        <td class="px-4 py-2 border">
                            {{ $tool->category->category_name }}
                        </td>

                        <td class="px-4 py-2 border text-center">
                            {{ $tool->qty }}
                        </td>

                        <td class="px-4 py-2 border text-center">
                            {{ $tool->qty }}
                        </td>

                        <td class="px-4 py-2 border text-center">
                            <button wire:click="editTool({{$tool->id}})" class="inline-flex bg-blue-500 px-4 py-2 text-white rounded-md cursor-pointer">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <button wire:click="destroyTool({{$tool->id}})" wire:confirm="apakah kamu yakin ingin menghapus user ini?" class="inline-flex bg-red-500 px-4 py-2 text-white rounded-md cursor-pointer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                
                                <div class="text-4xl text-gray-400">📚</div>

                                <h2 class="text-base font-semibold text-gray-700">
                                    Data barang tidak ditemukan
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
        <x-has-more-page :datas="$tools" :target="$activeCategory" />
    </div>
</div>