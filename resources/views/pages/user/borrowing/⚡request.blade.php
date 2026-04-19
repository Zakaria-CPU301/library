<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Borrow;
use Livewire\Attributes\On;

new class extends Component
{
    public $userId = 0;
    public $tool = [];
    public $user = [];
    public $carts = [];

    public function mount() {
        $this->userId = Auth::id();
        $this->user = User::findOrFail($this->userId);
    }

    // date range
    #[Validate('required', message: 'Jangka waktu meminjam wajib di isi')]
    public $dateRange;
    public $startDate;
    public $finishDate;

    public function updatedDateRange() {
        $date = explode(' ', $this->dateRange);
        if (count($date) === 1) {
            $this->addError('dateRange', 'Waktu pengembalian barang harus di tentukan');
            return false;
        }

        $this->startDate = $date[0];
        $this->finishDate = $date[2];
        return true;
    }

    // checkbox items
    public $selectAll = false;
    #[Validate('array')]
    #[Validate('min:1', message: 'Minimal pilih 1 barang yang akan dipinjam')]
    public $selectedTools = [];

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedTools = $this->carts->pluck('id')->toArray();
        } else {
            $this->selectedTools = [];
        }

        $this->resetErrorBag('selectedTools');
    }

    public function updatedSelectedTools($value) {
        $this->selectAll = count($this->selectedTools) === $this->carts->count() ? true : false;
    }

    // qty items
    

    #[On('comp-cart')]
    public function compCart() {}

    public function render() {
        $this->dispatch('mark-event');
        $this->carts = Borrow::latest()->where('user_id', $this->userId)->get();
        return view('pages.user.borrowing.⚡request', ['carts' => $this->carts]);
    }

    public function Borrow() {
        dd($this->selectedTools);
    }

    public function unCart($cartId) {
        Borrow::findOrFail($cartId)->delete();
    }

    public function save() {
        $this->validate();
        if ($this->carts->isEmpty()) $this->addError('selectedTools', 'Tidak ada barang yang akan di pinjam');
        if(!$this->updatedDateRange()) return;
        $this->Borrow();
    }

};
?>

<div>
    <x-header>
        <x-header-info title="Pinjam barang" desc="" />
    </x-header>
{{-- @dump($selectedTools)
    @if ($errors->any())
    @foreach ($errors->all() as $err)
        <ul><li>{{$err}}</li></ul>
    @endforeach
    @endif --}}

    <div class="container mx-auto px-6">
        <form wire:submit="save">
            <div class="grid grid-cols-4 gap-6">
                <div class="col-span-1 bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold text-lg mb-4">Data Peminjam</h2>

                    <div>
                        <x-input-label value="Fullname" />
                        <x-text-input :value="$user->fullname" disabled />
                    </div>

                    <div class="mt-3">
                        <x-input-label value="Email address" />
                        <x-text-input :value="$user->email" disabled />
                    </div>

                    <div class="mt-3">
                        <x-input-label value="Borrow range date" />
                        <div wire:ignore>
                            <x-text-input wire:model.live="dateRange" id="dateFlatpickr" placeholder="Tentukan waktu peminjaman" />
                        </div>
                        <x-input-error :messages="$errors->get('dateRange')" />
                    </div>
                </div>

                <div class="col-span-3 bg-white p-4 rounded-xl shadow">
                    <x-input-error :messages="$errors->get('selectedTools')" />
                    <div class="w-full flex items-center justify-between mb-5">
                        <h2 class="font-semibold text-lg">Pilih Barang</h2>
                        <div class="flex">
                            {{-- <x-button-trash-icon :target="unCart()" :key="$cart->id" confirm="Yakin ingin membatalkan semua peminjaman barang?" /> --}}
                            <div class="flex gap-2.5">
                                <input type="checkbox" wire:model.live="selectAll" id="checkAll" />
                                <label for="checkAll" class="">Check All Items</label>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 max-h-100 overflow-y-auto">
                        @forelse ($carts as $cart)
                            <label 
                                class="flex items-center justify-between hover:bg-black border p-4 rounded-xl cursor-pointer transition"
                                wire:key="{{__('cart-') . $cart->id}}">

                                <div class="flex items-center gap-4">
                                    <input 
                                        type="checkbox" 
                                        value="{{ $cart->id }}"
                                        wire:model.live="selectedTools"
                                        class="checkboxes rounded"
                                    >

                                    <img 
                                        src="{{ asset('storage/' . $cart->tool->cover_path) }}" 
                                        alt="{{ $cart->tool->name }}"
                                        class="w-16 h-16 object-cover rounded-lg border"
                                    >

                                    <div class="flex flex-col">
                                        <p class="font-semibold text-gray-800">
                                            {{ $cart->tool->name_tool }}
                                        </p>

                                        <p class="text-sm text-gray-500">
                                            Stok Tersedia: {{ $cart->tool->qty }}
                                        </p>

                                        <p class="text-xs text-gray-400 line-clamp-2 max-w-xs">
                                            Stok: {{ $cart->tool->qty }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex gap-5 items-center">
                                    <input 
                                        type="number" 
                                        wire:model="quantities.{{ $cart->id }}"
                                        {{-- @disabled(!in_array($cart->id, $selectedTools)) --}}
                                        class="w-30 text-center border rounded disabled:bg-gray-100"
                                    >

                                    <x-nav-icon-button-load 
                                        target="unCart({{$cart->id}})" 
                                        i="bi bi-trash"
                                        confirm="Apakah kamu yakin ingin membatalkan peminjaman barang ini?" />
                                </div>
                            </label>
                        @empty
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="text-4xl text-gray-400">
                                    🔨
                                </div>

                                <h2 class="text-base font-semibold text-gray-700">
                                    Belum ada draft peminjaman barang
                                </h2>

                                <a href="{{route('tools.user')}}" wire:navigate>Pinjam Sekarang</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <x-primary-button>
                    Pinjam
                </x-primary-button>
            </div>
        </form>
    </div>

    <script>
        flatpickr('#dateFlatpickr', {
            mode: 'range',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd M Y',
            minDate: 'today',
        })
    </script>
</div>