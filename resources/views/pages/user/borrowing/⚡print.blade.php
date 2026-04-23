<?php

use Livewire\Component;
use App\Models\Borrow;
use Livewire\Attributes\Layout;

new #[Layout('layouts.clean')] class extends Component
{
    public $borrow = [];

    public function mount($borrowId) {
        $this->borrow = Borrow::findOrFail($borrowId);
    }
};
?>

<div class="max-w-2xl mx-auto p-6 bg-white text-gray-800 print:p-0 print:shadow-none">

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold">Detail Peminjaman</h2>
        <p class="text-sm text-gray-500">Laporan Data Peminjaman Barang</p>
    </div>

    <div class="flex justify-center mb-6">
        <img 
            src="{{ asset('storage/' . $borrow->tool->cover_path) }}" 
            alt="Foto Barang"
            class="w-40 h-40 object-cover rounded-lg border"
        >
    </div>

    <div class="space-y-3 text-sm">

        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Nama Peminjam</span>
            <span>{{ $borrow->user->fullname }}</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Email</span>
            <span>{{ $borrow->user->email }}</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Nama Barang</span>
            <span>{{ $borrow->tool->name_tool }}</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Nama Barang</span>
            <span>{{ Str::ucfirst($borrow->tool->category->category_name) }}</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Tanggal Pinjam</span>
            <span>{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Tanggal Kembali</span>
            <span>{{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}</span>
        </div>

        <div class="flex justify-between border-b pb-2">
            <span class="font-semibold">Status</span>
            <span class="capitalize">{{ $borrow->status }}</span>
        </div>

    </div>

    <div class="mt-10 flex justify-between text-sm">
        <div>
            <p class="text-gray-500">Dicetak pada:</p>
            <p>{{ now()->format('d M Y') }}</p>
        </div>

        <div class="text-center">
            <p class="mb-12">Tanda Tangan</p>
            <p class="border-t pt-1">(.........................)</p>
        </div>
    </div>

    <div class="mt-6 text-center flex space-x-3.5 justify-center items-center print:hidden">
        <a 
            href="{{route('borrowing.user.index')}}" wire:navigate
            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-400 transition"
        >
            <i class="bi bi-arrow-bar-left"></i>
            Cancel
        </a>
        <button 
            onclick="window.print()" 
            class="px-6 py-2 bg-blue-600 cursor-pointer text-white rounded-lg hover:bg-blue-700 transition"
        >
            Print
        </button>
    </div>

</div>