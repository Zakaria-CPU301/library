<?php

use Livewire\Component;
use App\Models\Borrow;

new class extends Component
{
    public $borrows;

    public function status($status, $borrowId)
    {
        Borrow::findOrFail($borrowId)->update(['status' => $status]);
    }

    public function render()
    {
        $this->borrows = Borrow::with([
            'tool',
            'user'
        ])->get();

        return view('pages.admin.borrowing.⚡manage');
    }
};
?>

@push('styles')
<style>
    .status-btn {
        button {
            background-color: gray;
            padding-inline: 5px;
            padding-block: 3px;
            color: white;
            border-radius: 10px;
        }
    }
</style>
@endpush

<div>
    <x-header>
        <x-header-info title="Kelola Peminjaman" desc="Kelola semua peminjaman barang yang di ajukan pengguna" />
    </x-header>
    <ul role="list" class="bg-white px-5 rounded-xl">
        @forelse ($borrows as $borrow)
        @php
        $status = $borrow->status
        @endphp
        <li class="flex justify-between gap-x-6 py-5">
            <div class="flex min-w-0 gap-x-4">
                <img src="{{asset('storage/' . $borrow->tool->cover_path)}}" alt="" class="h-52 flex-none rounded-lg bg-gray-800 outline -outline-offset-1 outline-white/10" />
                <div class="min-w-0 flex-auto space-y-2.5">
                    <div class="flex flex-row-reverse gap-3.5 items-center text-xs">
                        <a href="" class="py-1 px-2 bg-gray-400 text-sm text-white rounded-xl">{{Str::ucfirst($borrow->tool->category->category_name)}}</a>
                        <p class="text-gray-400">{{$borrow->updated_at->format('d M Y')}}</p>
                    </div>
                    <p class="mt-2 font-semibold text-xl text-black">{{$borrow->tool->name_tool}}</p>
                    <p class="truncate text-xs/5 text-gray-400">{{$status}}</p>

                    <p class="text-sm">Jumlah peminjaman: {{$borrow->qty}}</p>
                </div>
            </div>
            <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                <div class="">
                    <p class="text-sm/6 text-black">{{$borrow->user->username}}</p>
                </div>
                <div class="status-btn flex gap-2.5">
                    @if ($status === 'waiting')
                    <button type="button" wire:click="status('accept', {{$borrow->id}})">Terima</button>
                    <button type="button" wire:click="status('reject', {{$borrow->id}})">Tolak</button>
                    @elseif($status === 'accept')
                    <button type="button" wire:click="status('borrowed', {{$borrow->id}})">Dipinjam</button>
                    @else
                    <button type="button" wire:click="status('return', {{$borrow->id}})">Dikembalikan</button>
                    @endif
                </div>
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