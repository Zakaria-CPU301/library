    <div class="fixed top-16.25 h-screen w-auto px-3 p-5">
        <!-- drawer component -->
        <ul class="space-y-2 font-medium">
            <li>
                <x-nav-link :href="route('dashboard.index')" :active="request()->routeIs('dashboard.index')">
                    <i class="bi bi-house text-3xl"></i>
                    <span x-show="open" class="ms-3">Dashboard</span>
                </x-nav-link>
            </li>
            <li x-data="{dropdown: JSON.parse(localStorage.getItem('dropdown-navbar') ?? 'false')}">
                <template x-if="! open">
                    <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.*')">
                        <i class="bi bi-book text-3xl"></i>
                    </x-nav-link>
                </template>
                <button x-show="open" @click="dropdown= ! dropdown; localStorage.setItem('dropdown-navbar', dropdown)" type="button" class="flex px-3 text-sm items-center w-full justify-between hover:cursor-pointer text-gray-500 hover:bg-gray-100 hover:text-gray-700 relative gap-3 py-2 rounded-lg text-md text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                    <template x-if="open">
                        <i class="bi bi-book text-3xl"></i>
                    </template>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Managemen Buku</span>
                    <svg :class="dropdown ? 'rotate-180' : ''" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open">
                    <ul x-show="dropdown" class="py-2 space-y-2 ms-8">
                        <li>
                            <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.index')">
                                <span x-show="open" class="ms-3">Dafter Buku</span>
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link :href="route('books.idx')" :active="request()->routeIs('books.idx')">
                                <span x-show="open" class="ms-3">Index Buat User</span>
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link :href="route('books.view-more', '1')" :active="request()->routeIs('books.view-more')">
                                <span x-show="open" class="ms-3">Pengembalian & Denda</span>
                            </x-nav-link>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                    <i class="bi bi-people text-3xl"></i>
                    <span x-show="open" class="flex-1 ms-3 whitespace-nowrap">Managemen Pengguna</span>
                </x-nav-link>
            </li>
        </ul>
    </div>