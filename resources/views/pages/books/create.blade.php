<x-form-layout>
    <x-slot name="header">
        <x-header-info title="Buku Baru" desc="masukkan data buku baru ke dalam sistem"/>
    </x-slot>
    <form action="{{route('books.store')}}" method="POST">
        @csrf
        <div class="mt-3">
            <x-input-label for="title">Judul Buku</x-input-label>
            <x-text-input type="text" name="title" id="title"/>
            <x-input-error :messages="$errors->get('title')" />
        </div>
        <div class="mt-3">
            <x-input-label for="author">Nama Penulis</x-input-label>
            <x-text-input type="text" name="author" id="author"/>
            <x-input-error :messages="$errors->get('author')" />
        </div>
        <div class="mt-3">
            <x-input-label for="year">Tahun Terbit</x-input-label>
            <x-text-input type="month" name="year" id="year"/>
            <x-input-error :messages="$errors->get('year')"/>
        </div>
        <div class="mt-3">
            <x-input-label for="qty">Jumlah Buku</x-input-label>
            <x-text-input type="number" min="1" name="qty" id="qty"/>
            <x-input-error :messages="$errors->get('qty')"/>
        </div>
        <div class="mt-3">
            <x-input-label for="lang">Bahasa</x-input-label>
            <select name="lang" id="lang">
                <option value="indonesian">Indonesia</option>
                <option value="english">English</option>
            </select>
            <x-input-error :messages="$errors->get('lang')"/>
        </div>
        <div class="mt-3">
            <x-input-label for="category">Kategori</x-input-label>
            <select name="category" id="category">
                <option value="1">Novel</option>
                <option value="2">Science</option>
                <option value="3">History</option>
                <option value="4">Technology</option>
            </select>
            <x-input-error :messages="$errors->get('category')"/>
        </div>
        <button type="submit">Tambah Buku</button>
    </form>
</x-form-layout>