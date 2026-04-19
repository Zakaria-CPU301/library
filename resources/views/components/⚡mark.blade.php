<?php

use Livewire\Component;
use App\Models\Mark;
use App\Models\Borrow;
use Livewire\Attributes\On;

new class extends Component
{
    public $authId;
    public $marks = [];
    public $carts = [];

    public function mount()
    {
        $this->authId = Auth::id();
        $this->marks = Mark::with('tool')->where('user_id', $this->authId)->get();
    }

    #[On('mark-event')]
    public function mark()
    {
        $this->marks = Mark::with('tool')->where('user_id', $this->authId)->get();
    }

    public function unMark($markId)
    {
        Mark::findOrFail($markId)->delete();
        $this->dispatch('comp-mark', $markId);
    }

    // public function cart($toolId)
    // {
    //     $this->carts = Borrow::createOrFirst([
    //         'status' => 'draft',
    //         'user_id' => $this->authId,
    //         'tool_id' => $toolId,
    //         'penalty_id' => 1,
    //     ]);
    //     $this->dispatch('comp-cart');
    // }

    public function viewMore($toolId)
    {
        $this->redirectRoute('tools.view', $toolId, true, true);
    }
};
?>

<div>
    <div class="relative">
        <div
            x-show="markOpen"
            x-transition.opacity
            @click="markOpen=! markOpen; localStorage.setItem('mark-open', markOpen)"
            class="fixed inset-0 bg-black/40 z-40"
            x-cloak>
        </div>

        <div
            x-show="markOpen"
            x-transition:enter="transition transform duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition transform duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed top-0 right-0 h-full w-80 bg-white z-50 shadow-lg flex flex-col"
            x-cloak>

            <div class="p-4 border-b font-semibold flex justify-between items-center">
                <span>Simpan Barang</span>
                <button @click="markOpen=! markOpen; JSON.parse(localStorage.setItem('mark-open', markOpen))">✕</button>
            </div>

            <div class="overflow-y-auto">
                @forelse ($marks as $mark)
                    <div class="flex p-4" wire:key="{{__('mark-') . $mark->id}}">
                        <img src="{{asset('storage/' . $mark->tool->cover_path)}}" alt="{{$mark->tool->name_tool}}" class="h-37.5">
                        <div class="flex flex-col ms-5 overflow-y-auto">
                            <h1 class="text-xl font-bold text-heading mb-2">
                                {{ $mark->tool->name_tool }}
                            </h1>
                            <p class="text-sm text-gray-500 mb-4">
                                {{ $mark->tool->category->category_name }}
                            </p>
                            <div class="flex">
                                <button
                                    wire:click="viewMore({{$mark->tool->id}})"
                                    class="relative z-50 text-blue-800 px-4 py-2 rounded-lg cursor-pointer shadow hover:shadow-xl transition duration-200">

                                    <i class="bi bi-eye"></i>
                                </button>
                                <button
                                    wire:click="unMark({{$mark->id}})"
                                    class="relative z-50 text-blue-800 px-4 py-2 rounded-lg cursor-pointer shadow hover:shadow-xl transition duration-200">
                                    <div class="" wire:loading.remove wire:target="unMark({{$mark->id}})">
                                        <i class="bi bi-bookmark-check-fill"></i>
                                    </div>
                                    <x-loading-state-session
                                        class="h-4 w-4"
                                        wire:loading wire:target="unMark({{$mark->id}})" />
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5">
                        <x-indicator-information-ping>Barang belum ada yang di simpan</x-indicator-information-ping>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>