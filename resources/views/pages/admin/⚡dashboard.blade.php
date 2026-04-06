<?php
use Livewire\Component;
use App\Models\Book;

new class extends Component
{
    public $books = [];

    public function mount() {
        $query = Book::with('category');
        $this->books = collect([
            'countBook' => $query->count(),
            'available' => $query->where('status', 'available')->count(),
            'borrowed' => $query->where('status', 'borrowed')->count(),
            'activeBorrowing' => 'maksud?',
            'lateBorrowing' => 'tahap development!',
        ]);
    }
};
?>

<div>
    <x-header class="border-b">
        <x-header-info title="dashboard admin" desc="ringkasan aktivitas perpustakaan" />
    </x-header>

    <div class="flex justify-between mt-5">
        <x-block-count i="bi bi-journal-medical" :count="$books->get('countBook')" label="total buku" />
        <x-block-count i="bi bi-journal-richtext" :count="$books->get('available')" label="total buku tersedia" />
        <x-block-count i="bi bi-journals" :count="$books->get('borrowed')" label="total buku dipinjam" />
        <x-block-count i="bi bi-person-workspace" :count="$books->get('activeBorrowing')" label="peminjaman aktif" />
        <x-block-count i="bi bi-exclamation-triangle" :count="$books->get('lateBorrowing')" label="peminjaman terlambat" />
    </div>
</div>