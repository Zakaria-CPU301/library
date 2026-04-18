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
        // $this->selectedTools = [9];
    }

    #[Validate('required')]
    public $dateRange;

    public $selectAll = false;
    #[Validate('required')]
    public $selectedTools = [];

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedTools = $this->carts->pluck('id')->toArray();
        } else {
            $this->selectedTools = [];
        }
    }

    public function Borrow() {
        
    }
    
    #[On('comp-cart')]
    public function compCart() {}

    public function render() {
        $this->dispatch('mark-event');
        $this->carts = Borrow::latest()->where('user_id', $this->userId)->get();
        return view('pages.user.borrowing.⚡request', ['carts' => $this->carts]);
    }

    public function save() {
        $this->validate();
    }

};
?>

<div>
    <x-header>
        <x-header-info title="Pinjam barang" desc="" />
    </x-header>

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
                            <x-text-input wire:model.live="dateRange" id="dateFlatpickr" />
                        </div>
                        <x-input-error :messages="$errors->get('dateRange')" />
                    </div>
                </div>

                <div class="col-span-3 bg-white p-4 rounded-xl shadow">
                    <div class="w-full flex items-center justify-between mb-5">
                        <h2 class="font-semibold text-lg">Pilih Barang</h2>
                        <div class="flex gap-2.5">
                            <input type="checkbox" wire:model.live="selectAll" id="checkAll" />
                            <label for="checkAll" class="">Check All Items</label>
                        </div>
                    </div>

                    <div class="space-y-3 max-h-100 overflow-y-auto">
                        @forelse ($carts as $cart)
                            <label 
                                class="flex items-center justify-between border p-4 rounded-xl cursor-pointer transition"
                                wire:key="{{$cart->id}}">

                                <div class="flex items-center gap-4">
                                    <input 
                                        type="checkbox" 
                                        value="{{ $cart->id }}"
                                        wire:model="selectedTools"
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

                                <input 
                                    type="number" 
                                    wire:model="quantities.{{ $cart->id }}"
                                    {{-- @disabled(!in_array($cart->id, $selectedTools)) --}}
                                    class="w-20 text-center border rounded disabled:bg-gray-100"
                                >
                            </label>
                        @empty
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="text-4xl text-gray-400">
                                    🔨
                                </div>

                                <h2 class="text-base font-semibold text-gray-700">
                                    Belum ada draft peminjaman barang
                                </h2>

                                <a href="{{route('tools.user')}}">Pinjam Sekarang</button>
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