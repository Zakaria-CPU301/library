<?php
use Livewire\Component;
use App\Models\Tool;
use App\Models\Mark;
use App\Models\Borrow;
use Livewire\Attributes\On;

new class extends Component
{
    public $tools = [];

    public $recentBorrows = [];

    #[On('comp-cart')]
    public function compMark() {}
    
    public function render() {
        $this->dispatch('mark-event');
        $query = Tool::with('category');
        $this->tools = $query;
        $this->tools = collect([
            'countTool' => $query->count(),
            'available' => $query->where('status', 'available')->count(),
            'borrowed' => $query->where('status', 'borrowed')->count(),
            'save' => Mark::where('user_id', Auth::id())->count(),
            'borrowing' => Borrow::where('user_id', Auth::id())->count(),
        ]);

        $this->recentBorrows = Borrow::with(['user', 'tool'])->get();
        
        return view('pages.public.⚡dashboard');
    }
};
?>

<div class="space-y-6">
    <x-header class="border-b bg-gray-400/10 backdrop-blur">
        <x-header-info 
            :title="__('Dashboard ' . auth()->user()->role)" 
            desc="Ringkasan aktivitas perpustakaan"
        />
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        @if (Auth::user()->role === 'admin')
            <div class="bg-linear-to-br from-blue-50 to-white p-1 rounded-xl">
                <x-block-count i="bi bi-journal-medical" :count="$tools->get('countTool')" label="Total Barang" />
            </div>

            <div class="bg-linear-to-br from-green-50 to-white p-1 rounded-xl">
                <x-block-count i="bi bi-journal-richtext" :count="$tools->get('available')" label="Tersedia" />
            </div>

            <div class="bg-linear-to-br from-orange-50 to-white p-1 rounded-xl">
                <x-block-count i="bi bi-journals" :count="$tools->get('borrowed')" label="Dipinjam" />
            </div>
        @else
            <div class="bg-linear-to-br from-blue-50 to-white p-1 rounded-xl">
                <x-block-count i="bi bi-journal-richtext" :count="$tools->get('save')" label="Disimpan" />
            </div>

            <div class="bg-linear-to-br from-orange-50 to-white p-1 rounded-xl">
                <x-block-count i="bi bi-journals" :count="$tools->get('borrowing')" label="Peminjaman" />
            </div>
        @endif
    </div>

    <div class="bg-linear-to-r from-indigo-50 via-white to-indigo-50 rounded-xl p-5 shadow-sm border border-indigo-100">
        <h3 class="font-semibold text-lg mb-2 text-indigo-700">Ringkasan</h3>

        @if (Auth::user()->role === 'admin')
            <p class="text-sm text-gray-600">
                Saat ini terdapat 
                <span class="font-semibold text-indigo-600">{{ $tools->get('borrowed') }}</span> barang sedang dipinjam 
                dari total 
                <span class="font-semibold text-indigo-600">{{ $tools->get('countTool') }}</span> barang.
            </p>
        @else
            <p class="text-sm text-gray-600">
                Kamu sedang meminjam 
                <span class="font-semibold text-indigo-600">{{ $tools->get('borrowing') }}</span> barang.
            </p>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-lg mb-4 text-gray-700">Aktivitas Terbaru</h3>

            <div class="space-y-3 text-sm">
                @forelse ($recentBorrows as $item)
                    <div class="flex justify-between border-b pb-2 hover:bg-gray-50 px-2 rounded">
                        <span class="text-gray-700">{{ $item->tool->name_tool }}</span>
                        <span class="text-gray-400">
                            {{ \Carbon\Carbon::parse($item->borrow_date)->diffForHumans() }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-lg mb-4 text-gray-700">Aksi Cepat</h3>

            <div class="flex flex-col gap-3">
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('tools.create') }}" wire:navigate
                       class="px-4 py-2 bg-linear-to-r from-blue-500 to-blue-600 text-white rounded-lg text-center hover:opacity-90 transition">
                        + Tambah Barang
                    </a>

                    <a href="{{ route('borrowing.admin.index') }}" wire:navigate
                       class="px-4 py-2 border border-gray-200 rounded-lg text-center hover:bg-gray-50">
                        Kelola Peminjaman
                    </a>
                @else
                    <a href="{{ route('tools.user') }}" wire:navigate
                       class="px-4 py-2 bg-linear-to-r from-blue-500 to-blue-600 text-white rounded-lg text-center hover:opacity-90 transition">
                        Cari Barang
                    </a>

                    <a href="{{ route('borrowing.user.index') }}" wire:navigate
                       class="px-4 py-2 border border-gray-200 rounded-lg text-center hover:bg-gray-50">
                        Riwayat Peminjaman
                    </a>
                @endif
            </div>
        </div>

    </div>

</div>