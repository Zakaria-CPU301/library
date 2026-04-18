    <div class="px-3 p-5">
        <!-- drawer component -->
        <ul class="space-y-2 font-medium">
            <li>
                <x-nav-link :href="route('index')">
                    <i class="bi bi-house text-3xl"></i>
                    <span x-show="open" class="ms-3">Home</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <i class="bi bi-clipboard-data text-3xl"></i>
                    <span x-show="open" class="ms-3">Dashboard</span>
                </x-nav-link>
            </li>
            @if ($currentUser->role === 'admin')
                <li>
                    <x-nav-link :href="route('account.index')" :active="request()->routeIs('account.*')">
                        <i class="bi bi-people text-3xl"></i>
                        <span x-show="open" class="flex-1 ms-3 whitespace-nowrap">Managemen User</span>
                    </x-nav-link>
                </li>
            @endif
            <li>
                <x-nav-link :href="route('tools.' . $currentUser->role)" :active="request()->routeIs('tools.*')">
                    <i class="bi bi-tools text-3xl"></i>
                    <span x-show="open" class="flex-1 ms-3 whitespace-nowrap">{{$currentUser->role === 'admin' ? 'Kelola' : ''}} barang</span>
                </x-nav-link>
            </li>
            @if ($currentUser->role === 'user')
            <li>
                <x-nav-link :href="route('borrowing.user.request')" :active="request()->routeIs('borrowing.user.request')">
                    <i class="bi bi-basket text-3xl"></i>
                    <span x-show="open" class="flex-1 ms-3 whitespace-nowrap">Peminjaman barang</span>
                </x-nav-link>
            </li>
            @endif
            <li>
                <x-nav-link :href="route('borrowing.' . $currentUser->role . '.index')" :active="request()->routeIs('borrowing.' . $currentUser->role . '.index')">
                    <i class="bi bi-newspaper text-3xl"></i>
                    <span x-show="open" class="flex-1 ms-3 whitespace-nowrap">{{$currentUser->role === 'admin' ? 'Kelola' : 'History'}} Peminjaman</span>
                </x-nav-link>
            </li>
        </ul>
    </div> 