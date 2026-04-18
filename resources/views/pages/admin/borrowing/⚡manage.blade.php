<?php

use Livewire\Component;
use App\Models\Borrow;

new class extends Component
{
    public $borrows;

    public function mount() {
        $this->borrows = Borrow::all();
    }
};
?>

<div>
    <x-header>
        <x-header-info title="Kelola Peminjaman" desc="Kelola semua peminjaman barang yang di ajukan pengguna" />
    </x-header>
    <ul role="list" class="divide-y divide-white/5">
        <li class="flex justify-between gap-x-6 py-5">
            <div class="flex min-w-0 gap-x-4">
            <img src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-12 flex-none rounded-full bg-gray-800 outline -outline-offset-1 outline-white/10" />
            <div class="min-w-0 flex-auto">
                <p class="text-sm/6 font-semibold text-black">Michael Foster</p>
                <p class="mt-1 truncate text-xs/5 text-gray-400">michael.foster@example.com</p>
            </div>
            </div>
            <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
            <p class="text-sm/6 text-black">Co-Founder / CTO</p>
            <p class="mt-1 text-xs/5 text-gray-400">Last seen <time datetime="2023-01-23T13:23Z">3h ago</time></p>
            </div>
        </li>
        <li class="flex justify-between gap-x-6 py-5">
            <div class="flex min-w-0 gap-x-4">
            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-12 flex-none rounded-full bg-gray-800 outline -outline-offset-1 outline-white/10" />
            <div class="min-w-0 flex-auto">
                <p class="text-sm/6 font-semibold text-black">Dries Vincent</p>
                <p class="mt-1 truncate text-xs/5 text-gray-400">dries.vincent@example.com</p>
            </div>
            </div>
            <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
            <p class="text-sm/6 text-black">Business Relations</p>
            <div class="mt-1 flex items-center gap-x-1.5">
                <div class="flex-none rounded-full bg-emerald-500/30 p-1">
                <div class="size-1.5 rounded-full bg-emerald-500"></div>
                </div>
                <p class="text-xs/5 text-gray-400">Online</p>
            </div>
            </div>
        </li>
    </ul>
</div>