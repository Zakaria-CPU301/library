<?php
use Livewire\Component;
use App\Models\Tool;
use App\Models\Mark;
use App\Models\Borrow;
use Livewire\Attributes\On;

new class extends Component
{
    public $tools = [];

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
        
        return view('pages.admin.⚡dashboard');
    }
};
?>

<div>
    <x-header class="border-b">
        <x-header-info title="dashboard admin" desc="ringkasan aktivitas perpustakaan" />
    </x-header>

    <div class="flex justify-between mt-5">
        @if (Auth::user()->role === 'admin')
            <x-block-count i="bi bi-journal-medical" :count="$tools->get('countTool')" label="total barang" />
            <x-block-count i="bi bi-journal-richtext" :count="$tools->get('available')" label="total barang tersedia" />
            <x-block-count i="bi bi-journals" :count="$tools->get('borrowed')" label="total barang dipinjam" />
        @elseif (Auth::user()->role === 'user')
            <x-block-count i="bi bi-journal-richtext" :count="$tools->get('save')" label="total simpan barang" />
            <x-block-count i="bi bi-journals" :count="$tools->get('borrowing')" label="total peminjaman" />
        @endif
    </div>
</div>