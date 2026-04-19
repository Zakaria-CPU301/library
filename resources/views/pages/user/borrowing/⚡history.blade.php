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
        $this->histories = Borrow::where('user_id', $this->userId)->where('status', '!=', 'draft')->get();
    }
};
?>

<div>
    <x-header>
        <x-header-info title="History Peminjaman" desc="Semua aktivitas peminjaman barang yang anda lakukan" />
    </x-header>
    <ul role="list" class="divide-y divide-white/5">
        @forelse ($histories as $history)
            <li class="flex justify-between gap-x-6 py-5">
                <div class="flex min-w-0 gap-x-4">
                    <img src="{{asset('storage/' . $history->tool->cover_path)}}" alt="" class="size-12 flex-none rounded-full bg-gray-800 outline -outline-offset-1 outline-white/10" />
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm/6 font-semibold text-black">{{$history->tool->name_tool}}</p>
                        <p class="mt-1 truncate text-xs/5 text-gray-400">
                            @php
                                $status = $history->status
                            @endphp
                            @if ($status === 'waiting')
                                Menunggu jawaban
                            @elseif($status === 'accept')
                                Barang bisa di ambil
                            @elseif($status === 'rejected')
                                Permintaan peminjaman di tolak
                            @else 
                                Barang dikembalikan
                            @endif
                        </p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                    <p class="text-sm/6 text-black">{{$history->user->username}}</p>
                    <p class="mt-1 text-xs/5 text-gray-400">{{$history->updated_at->diffForHumans()}}</time></p>
                </div>
            </li>
        @empty
            <p>asldkj</p>
        @endforelse
    </ul>
</div>