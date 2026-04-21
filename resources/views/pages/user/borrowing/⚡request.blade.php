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
        $this->carts = Borrow::where('user_id', $this->userId)->where('status', 'draft')->get();
        foreach ($this->carts as $cart) {
            $this->quantities[$cart->id] = 1;
        }
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

    public function updatedSelectedTools($keys) {
        $this->selectAll = count($this->selectedTools) === $this->carts->count() ? true : false;

        foreach ($keys as $key) {
            $this->validateOnly("quantities.$key", $this->qtyRules($key));
        }
    }

    // qty items
    public $quantities = [];

    public function qtyRules($key) {
        return [
            "quantities.$key" => ['required', 'integer', 'min:1', 'max:' . $this->carts->findOrFail($key)->tool->qty]
        ];
    }

    public function updatedQuantities($value, $key) {
        $this->validateOnly("quantities.$key", $this->qtyRules($key));
    }

    #[On('comp-cart')]
    public function compCart() {}

    public function render() {
        $this->dispatch('mark-event');
        $this->carts = Borrow::where('user_id', $this->userId)->where('status', 'draft')->latest('updated_at')->get();
        return view('pages.user.borrowing.⚡request', ['carts' => $this->carts]);
    }

    public function Borrow() {
        foreach ($this->selectedTools as $selected) {
            Borrow::where('user_id', $this->userId)
                    ->where('id', $selected)
                    ->update([
                        'status' => 'waiting',
                        'borrow_date' => $this->startDate,
                        'return_date' => $this->finishDate,
                        'qty' => $this->quantities[$selected]
                    ]);
        }
    }

    public function manyUnCart() {
        foreach ($this->selectedTools as $key) {
            Borrow::findOrFail($key)->delete();
        }
        $this->selectedTools = [];
        $this->updatedSelectedTools(null);
    }

    public function save() {
        if ($this->selectedTools) {
            $rules = array_merge($this->getRules(), $this->qtyRules(...$this->selectedTools));
        } else {
            $rules = $this->getRules();
        }
        $this->validate($rules);
        if(!$this->updatedDateRange()) return;
        $this->Borrow();
        $this->redirectRoute("borrowing.user.index", navigate: true);
    }
};
?>

<div>
    <x-header>
        <x-header-info title="Pinjam barang" desc="" />
    </x-header>
{{-- @dump($selectedTools, $carts->count()) --}}
    @if ($errors->any())
    @foreach ($errors->all() as $err)
        <ul><li>{{$err}}</li></ul>
    @endforeach
    @endif

    <div class="container mx-auto px-6">
        <form wire:submit="save">
            <div class="grid grid-cols-5 gap-5">
                <div class="col-span-2 bg-white p-4 rounded-xl shadow">
                    <h2 class="font-semibold text-lg mb-4">Identitas Peminjaman</h2>

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
                        <div class="{{$carts->isEmpty() ? 'flex' : 'flex'}} items-center gap-2.5">
                            <x-nav-icon-button-load 
                                class="{{$selectedTools ? '' : 'hidden'}}"
                                target="manyUnCart()" 
                                background="bg-red-500" 
                                i="bi bi-trash-fill text-white" 
                                confirm="Yakin ingin membatalkan beberapa peminjaman?" />
                            <div class="flex gap-2.5">
                                <input type="checkbox" wire:model.live="selectAll" id="checkAll" class="cursor-pointer" {{$carts->isEmpty() ? 'disabled' : ''}} />
                                <label for="checkAll" class="cursor-pointer">Check All Items</label>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 max-h-100 overflow-y-auto">
                        @forelse ($carts as $cart)
                            <label 
                                class="flex items-center justify-between hover:bg-black/5 border p-4 rounded-xl cursor-pointer transition"
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
                                    <div class="">
                                        <input 
                                            type="number" 
                                            wire:model.live="quantities.{{ $cart->id }}"
                                            {{-- @disabled(!in_array($cart->id, $selectedTools)) --}}
                                            class="w-30 text-center border rounded disabled:bg-gray-100"
                                        >
                                        <x-input-error :messages="$errors->get('quantities.' . $cart->id)" />
                                    </div>
                                </div>
                            </label>
                        @empty
                            <x-empty-data-rows 
                                class="bg-gray-500"
                                icon="🛒" info="Belum ada barang yang akan di pinjam" 
                                :label="__('Pilih Barang')" 
                                :route="route('tools.user')" />
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