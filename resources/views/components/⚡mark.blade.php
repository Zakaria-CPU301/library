<?php

use Livewire\Component;
use App\Models\Mark;
use App\Models\Borrow;
use Livewire\Attributes\On;

new class extends Component
{
    public $userId;
    public $marks = [];
    public $carts = [];

    public function mount()
    {
        $this->userId = Auth::id();
        $this->marks = Mark::with('tool')->where('user_id', $this->userId)->get();
    }

    #[On('mark-event')]
    public function mark()
    {
        $this->marks = Mark::with('tool')->where('user_id', $this->userId)->get();
    }

    public function unMark($markId)
    {
        Mark::findOrFail($markId)->delete();
        $this->dispatch('comp-cart', $markId);
    }

    public function cart($toolId)
    {
        Borrow::create([
            'status' => 'draft',
            'user_id' => $this->userId,
            'tool_id' => $toolId,
            'penalty_id' => 1,
        ]);
        $this->dispatch('comp-cart');
    }

    public function unCart($toolId) {
        Borrow::where('user_id', $this->userId)->where('tool_id', $toolId)->where('status', 'draft')->delete();
        $this->dispatch('comp-cart');
    }

    public function viewMore($toolId)
    {
        $this->redirectRoute('tools.view', $toolId, navigate: true);
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
            class="fixed top-0 right-0 h-full w-86 bg-white z-50 shadow-lg flex flex-col"
            x-cloak>

            <div class="p-4 border-b font-semibold flex justify-between items-center">
                <span>Simpan Barang</span>
                <button @click="markOpen=! markOpen; JSON.parse(localStorage.setItem('mark-open', markOpen))">✕</button>
            </div>

            <div class="overflow-y-auto">
                @forelse ($marks as $mark)
                {{-- @dump($mark->tool_id, $mark->user_id) --}}
                    <div class="flex p-4" wire:key="{{__('mark-') . $mark->id}}">
                        <img src="{{asset('storage/' . $mark->tool->cover_path)}}" alt="{{$mark->tool->name_tool}}" class="h-37.5">
                        <div class="flex flex-col ms-5 overflow-y-auto">
                            <h1 class="text-xl font-bold text-heading mb-2">
                                {{ $mark->tool->name_tool }}
                            </h1>
                            <p class="text-sm text-gray-500 mb-4">
                                {{ $mark->tool->category->category_name }}
                            </p>
                            <div class="flex gap-2.5">
                                @php
                                    $isCartProduct = Borrow::where('user_id', $userId)->where('tool_id', $mark->tool_id)->latest('created_at')->first()
                                @endphp
                                @if ($isCartProduct)
                                    @if ($isCartProduct->status == 'draft')
                                        <x-nav-icon-button-load target="unCart({{$mark->tool_id}})" i="bi bi-cart-check-fill text-green-900" />
                                    @else
                                        <x-nav-icon-button-load target="cart({{$mark->tool_id}})" i="bi bi-cart-fill text-green-900" />
                                    @endif
                                @else
                                    <x-nav-icon-button-load target="cart({{$mark->tool_id}})" i="bi bi-cart-fill text-green-900" />
                                @endif

                                <x-nav-icon-button-load target="viewMore({{$mark->tool->id}})" i="bi bi-eye-fill text-yellow-500" />
                                <x-nav-icon-button-load target="unMark({{$mark->id}})" i="bi bi-bookmark-fill text-blue-500" />
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5">
                        <x-indicator-information-ping bgIndicator="bg-black">Barang belum ada yang di simpan</x-indicator-information-ping>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>