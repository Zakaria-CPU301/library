<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Borrow;

new class extends Component
{
    public $userId;
    public $histories = [];
    public function mount() {
        $this->userId = Auth::id();
        $this->histories = Borrow::where('user_id', $this->userId)->where('status', '!=', 'draft')->latest('updated_at')->get();
    }

    public function print($borrowId) {
        $this->redirectRoute('borrowing.user.print', $borrowId, navigate: true);
    }
};
?>

<div>
    <x-header>
        <x-header-info title="History Peminjaman" desc="Semua aktivitas peminjaman barang yang anda lakukan" />
    </x-header>
    <ul role="list" class="bg-white px-5 rounded-xl">
        @forelse ($histories as $history)
            <li class="flex justify-between gap-x-6 py-5">
                <div class="flex min-w-0 gap-x-4">
                    <img src="{{asset('storage/' . $history->tool->cover_path)}}" alt="" class="h-52 flex-none rounded-lg bg-gray-800 outline -outline-offset-1 outline-white/10" />
                    <div class="min-w-0 flex-auto space-y-2.5">
                        <a href="" class="py-1 px-2 bg-gray-400 text-sm text-white rounded-xl">{{Str::ucfirst($history->tool->category->category_name)}}</a>
                        <p class="mt-2 font-semibold text-xl text-black">{{$history->tool->name_tool}}</p>
                        <p class="truncate text-xs/5 text-gray-400">
                            @php
                                $status = $history->status
                            @endphp
                            @if ($status === 'waiting')
                                Menunggu jawaban
                            @elseif($status === 'accept')
                                <x-indicator-information-ping bgIndicator="bg-gray-500" class="text-gray-400 font-bold">Barang bisa di ambil</x-indicator-information-ping>
                            @elseif($status === 'reject')
                                <x-indicator-information-ping bgIndicator="bg-red-500" class="text-red-400 font-bold">Peminjaman barang di tolak</x-indicator-information-ping>
                            @else 
                                Barang dikembalikan
                            @endif
                        </p>

                        <p class="text-sm">Jumlah peminjaman: {{$history->qty}}</p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                    <p class="text-sm/6 text-black">{{$history->user->username}}</p>
                    <p class="mt-1 text-xs/5 text-gray-400">{{$history->updated_at->format('d M Y')}}</p>
                    @if ($status === 'accept')
                    <button type="button" wire:click="print({{$history->id}})">Cetak Leporan Peminjaman</button>
                    @endif
                </div>
            </li>
        @empty
            <x-empty-data-rows 
                class="bg-green-500"
                icon="🔨" info="Belum ada aktivitas peminjaman barang" 
                :label="__('Lakukan Peminjaman')" 
                :route="route('borrowing.user.request')" />
        @endforelse
    </ul>
</div>