<div
    x-show="openModal"
    x-transition.opacity
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">

    <div 
        class="absolute inset-0 cursor-zoom-out"
        wire:click="showModal('hidden')"
        @click="openModal = false">
    </div>

        {{-- Content --}}
    <div class="relative overflow-hidden">
        {{ $slot }}

        {{-- Loading overlay --}}
        <div class="" wire:loading wire:target="showModal">
            <div 
            class="absolute inset-0 flex items-center justify-center bg-white/70 backdrop-blur-sm">
                <x-loading-state-session class="w-8 h-8" />
            </div>
        </div>
    </div>
</div>