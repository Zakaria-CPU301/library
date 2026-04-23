<x-clean-layout>
    <!-- CONTAINER -->
    <div class="w-full max-w-6xl mx-auto px-6">

        <!-- NAVBAR -->
        <header class="flex justify-between items-center mb-12">
            <h1 class="text-xl font-semibold text-orange-600">SediaPinjam</h1>

            <a href="/dashboard" wire:navigate
                class="px-5 py-2 bg-orange-500 text-white rounded-xl shadow-sm hover:bg-orange-600 transition">
                @auth
                    Dashboard
                @else
                    Login
                @endauth
            </a>
        </header>

        <!-- HERO -->
        <div class="grid md:grid-cols-2 gap-10 items-center">

            <!-- TEXT -->
            <div>
                <h2 class="text-4xl md:text-5xl font-bold leading-tight mb-5">
                    Kelola Peminjaman <span class="text-orange-500">Lebih Mudah</span>
                </h2>

                <p class="text-gray-600 mb-8">
                    Sistem modern untuk mengatur peminjaman barang secara cepat, rapi, dan efisien dalam satu tempat.
                </p>

                <div class="flex gap-4">
                    <a href="
                        @auth
                            @if (Auth::user()->role === 'admin')
                                {{route('tools.admin')}}
                            @elseif (Auth::user()->role === 'user')
                                {{route('tools.user')}}
                            @endif
                        @else
                            {{route('dashboard')}}
                        @endauth
                    " 
                        wire:navigate
                        class="px-6 py-3 bg-orange-500 text-white rounded-xl shadow hover:bg-orange-600 transition">
                        Mulai Sekarang
                    </a>
                </div>
            </div>

            <div class="hidden md:flex justify-center">
                <div class="w-96 p-4 bg-white/60 backdrop-blur rounded-3xl shadow-2xl">
                    <img 
                        src="{{ asset('storage/defaults/preview_item.png') }}" 
                        alt="Preview Item"
                        class="w-full h-auto rounded-2xl object-contain"
                    >
                </div>
            </div>

        </div>

    </div>

</x-clean-layout>