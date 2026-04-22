<?php

use Livewire\Component;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Mark;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

new class extends Component
{
    public $tool;
    public $categories = [];
    public $status = [];
    public $perPage = [0 => 8];
    public $idCurrentPreview  = 0;
    public $authId;
    public $isMark = false;

    public $activeCategory = 0;

    public function mount()
    {
        $this->authId = Auth::id();
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

    #[On('comp-cart')]
    public function compMark() {} // terima dari mark (render trigger -> update ulang component)


    public function render()
    {
        $this->dispatch('mark-event'); // awal kirim ke mark | ketika render update dispatch ini dikirim lagi ke mark
        // $this->dispatch('cart-event');
        $query = Tool::with('category')->whereAny(['name_tool'], 'LIKE', '%' . $this->searchKey . '%');
        if ($this->activeCategory != 0) $query->where('category_id', $this->activeCategory);
        $tools = $query->paginate($this->perPage[$this->activeCategory]);
        $cover = Tool::find($this->idCurrentPreview);

        return view('pages.user.tools.⚡index', ['tools' => $tools, 'coverPreview' => $cover]);
    }

    public $isActive = '';
    public function filterUser($toggleId)
    {
        $this->isActive = $toggleId;
    }

    public function viewMore($idTool) {
        $this->redirectRoute('tools.view', $idTool, true, true);
    }

    public function showModal($idTool) {
        $this->idCurrentPreview = $idTool;
    }

    public function mark($toolId) {
        $mark = Mark::where(['user_id' => $this->authId, 'tool_id' => $toolId]);
        if ($mark->exists()) $mark->delete();
        else Mark::create(['user_id' => $this->authId, 'tool_id' => $toolId]);
    }
};
?>
<div class="z-40">
    <x-slot name="searchEngine"></x-slot>
    <livewire:slide-filter :toggleButton="$categories" wire:model.live="activeCategory" />

    <div class="flex flex-col items-center px-10 mt-5" x-data="{openModal: false}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full">
            @forelse ($tools as $tool)
            <div wire:loading wire:target="activeCategory" class="animate-pulse space-y-4">
                <div class="w-full h-48 bg-gray-300 rounded-xl"></div>

                <div class="space-y-2 p-4">
                    <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-300 rounded w-1/2"></div>
                </div>
            </div>
            <div wire:loading.remove wire:target="activeCategory" class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col transition hover:shadow-md">
                <div class="w-full p-3 h-56">
                    <div class="w-full h-full rounded-lg overflow-hidden">
                        <img
                        src="{{ asset('storage/' . $tool->cover_path) }}"
                        alt="{{ $tool->title }}"
                        wire:click="showModal({{$tool->id}})"
                        @click="openModal= true" 
                        class="w-full h-full object-cover cursor-pointer hover:scale-110 transition duration-300" />
                    </div>
                </div>

                <div class="px-4 pb-4 flex flex-col flex-1">
                    <h5 class="text-base font-semibold text-gray-800 line-clamp-2">
                        {{ $tool->name_tool }}
                    </h5>

                    <p class="text-sm text-gray-500 mt-1 mb-4">
                        {{ $tool->category->category_name }}
                    </p>

                    {{-- Button --}}
                    <div class="mt-auto flex space-x-2">
                        <button wire:click="viewMore({{$tool->id}})" class="w-full cursor-pointer flex items-center justify-center gap-2 whitespace-nowrap text-sm bg-gray-100 border border-gray-300 hover:bg-gray-200 rounded-lg px-4 py-2 transition">
                            Lebih Banyak
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-width="2" d="M5 12h14m0 0-4 4m4-4-4-4" />
                            </svg>
                        </button>
                        <button 
                            wire:click="mark({{$tool->id}})"
                            class="relative z-50 text-blue-800 px-4 py-2 rounded-lg cursor-pointer shadow hover:shadow-xl transition duration-200">
                            <div class="" wire:loading.remove wire:target="mark({{$tool->id}})">
                                @forelse ($tool->toolmarks as $mark)
                                <i class="bi bi-bookmark-fill""></i>
                                @empty
                                <i class="bi bi-bookmark""></i>
                                @endforelse
                            </div>
                            <x-loading-state-session class="h-4 w-4" wire:loading wire:target="mark({{$tool->id}})" />
                        </button>
                    </div>
                </div>
            </div>
            
            @empty
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                <div class="flex flex-col items-center justify-center gap-3">
                    <div class="text-4xl text-gray-400">
                        📚
                    </div>

                    <h2 class="text-base font-semibold text-gray-700">
                        Data barang tidak ditemukan
                    </h2>
                    
                    <p class="text-sm text-gray-500">
                        @if($searchKey)
                            Tidak ada hasil untuk
                            <span class="font-medium text-gray-700">"{{ $searchKey }}"</span>
                        @endif
                        @if($activeCategory)
                            di categori
                            <span class="font-medium text-gray-700">
                                {{ $categories->firstWhere('id', $activeCategory)['category_name'] }}
                            </span>
                        @else
                            di semua koleksi
                        @endif
                    </p>
                </div>
            </div>
            @endforelse
        </div>

        @isset($coverPreview)
            <x-modal-detail :labelModal="$coverPreview->title">
                <div class="flex flex-col w-full bg-white rounded-2xl shadow p-3">
                    <img 
                        src="{{ asset('storage/' . $coverPreview->cover_path) }}"
                        class="max-h-[70vh] w-full object-contain rounded-t-2xl"
                    />

                    <div class="p-6 text-center space-y-4">
                        <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded bg-gray-100">
                            {{$coverPreview->category->category_name}}
                        </span>

                        <h5 class="text-xl font-semibold text-gray-800">
                            {{$coverPreview->name_tool}}
                        </h5>

                        <button wire:click="viewMore({{$coverPreview->id}})" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-black rounded-lg hover:bg-gray-800 transition">
                            Read more
                        </button>
                    </div>
                </div>
            </x-modal-detail>
        @endisset

        <x-loading-indicator target="activeCategory" />

        <x-has-more-page target="activeCategory" :datas="$tools" />
    </div>
</div>