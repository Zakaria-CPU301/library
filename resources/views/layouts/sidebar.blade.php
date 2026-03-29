<div>
    <!-- drawer component -->
    <div class='bg-white relative left-0 px-4 z-40 h-screen overflow-y-scroll transition-transform'
        :class='open ? 'w-64' : w-auto'
        tabindex="-1">
        <div class="py-5 overflow-y-scroll">
            <ul class="space-y-2 font-medium">
                <li>
                    <a wire:navigate href="{{route('dashboard')}}" class="flex items-center text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <i class="bi bi-house text-3xl"></i>
                        <span x-show="open" class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li x-data="{dropdown: false}">
                    <button @click="dropdown= ! dropdown" type="button" class="flex items-center w-full justify-between hover:cursor-pointer py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                    <template x-if="open">
                        <i class="bi bi-book text-3xl"></i>
                    </template>

                    <template x-if="! open">
                        <a wire:navigate href="{{route('books.index')}}">
                            <i class="bi bi-book text-3xl"></i>
                        </a>
                    </template>
                        <span x-show="open" class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Managemen Buku</span>
                        <svg x-show="open" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                        </svg>
                    </button>
                    <ul x-show="dropdown" class="py-2 space-y-2">
                        <li>
                            <a wire:navigate href="{{route('books.index')}}" class="pl-10 flex items-center py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">Daftar Buku</a>
                        </li>
                        <li>
                            <a wire:navigate href="#" class="pl-10 flex items-center py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">Peminjaman</a>
                        </li>
                        <li>
                            <a wire:navigate href="#" class="pl-10 flex items-center py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">Pengembalian & Denda</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a wire:navigate href="{{route('users.index')}}" class="flex items-center py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <i class="bi bi-people text-3xl"></i>
                        <span x-show="open" class="flex-1 ms-3 whitespace-nowrap">Managemen Pengguna</span>
                    </a>
                </li>
                <li>
                    <a wire:navigate href="#" class="flex items-center py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <i class="bi bi-inbox text-3xl"></i>
                        <span x-show="open" class="flex-1 ms-3 whitespace-nowrap">Inbox</span>
                        <span x-show="open" class="inline-flex items-center justify-center w-4.5 h-4.5 ms-2 text-xs font-medium text-fg-danger-strong bg-danger-soft border border-danger-subtle rounded-full">2</span>
                    </a>
                </li>
                <li x-show="open">
                    <a wire:navigate href="#" class="flex items-center py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group">
                        <i class="bi bi-box-arrow-right text-3xl"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Sign In</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>