<x-manage-user-layout>
    <div class="container">
        <x-slot name="header">
            <x-header-info title="Buku Baru" desc="masukkan data buku baru ke dalam sistem"/>
        </x-slot>
        <main>
            <form action="{{route('books.store')}}" method="POST">
                @csrf
                <label for="title">Judul Buku</label>
                <input type="text" name="title" id="title"/>
                <label for="author">Nama Penulis</label>
                <input type="text" name="author" id="author"/>
                <label for="year">Tahun Terbit</label>
                <input type="month" name="year" id="year"/>
                <label for="qty">Jumlah Buku</label>
                <input type="number" min="1" name="qty" id="qty"/>
                <label for="lang">Bahasa</label>
                <select name="lang" id="lang">
                    <option value="indonesian">Indonesia</option>
                    <option value="english">English</option>
                </select>
                <label for="category">Kategori</label>
                <select name="category" id="category">
                    <option value="1">Novel</option>
                    <option value="2">Science</option>
                    <option value="3">History</option>
                    <option value="4">Technology</option>
                </select>
                <button type="submit">Tambah Buku</button>
            </form>
        </main>
    </div>
</x-manage-user-layout>