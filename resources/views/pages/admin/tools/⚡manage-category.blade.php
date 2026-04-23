<?php

use Livewire\Component;
use App\Models\Category;

new class extends Component
{
    public $categories = [];
    public function render() {
        $this->categories = Category::all();

        return view('pages.admin.tools.⚡manage-category');
    }

    public function trash($cId) {
        $this->categories->findOrFail($cId)->delete();
    }
};
?>

<div>
    <x-slot name="searchEngine"></x-slot>
    <div class="flex px-2 max-w-sm">
        <livewire:selection-filter :dataFilters="$categories" id="categoryIndex" />
    </div>
    <x-header>
        <x-header-info title="kelola kategori barang" desc="" />
    </x-header>

    <div class="space-y-3">
        @foreach ($categories as $category)
            <div class="flex items-center justify-between bg-white shadow-sm border rounded-xl px-4 py-3 hover:shadow-md transition">

                <div class="font-semibold text-gray-800">
                    {{ $category->category_name }}
                </div>

                <div class="flex items-center gap-2">

                    <button 
                        class="px-3 py-1.5 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        Lihat Barang
                    </button>

                    <button 
                        class="px-3 py-1.5 text-sm bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 transition">
                        Edit
                    </button>

                    <button 
                        type="button"
                        wire:click="trash({{ $category->id }})"
                        wire:confirm="Barang di kategori ini berjumlah {{$category->tools->count()}} barang, yakin hapus kategori ini?"
                        class="px-3 py-1.5 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        Hapus
                    </button>

                </div>
            </div>
        @endforeach
    </div>
</div>