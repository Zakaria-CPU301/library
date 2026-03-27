<div wire:loading.remove wire:target="{{$target}}" class="w-full flex justify-center py-5" id="spinner-load-data">
    @if ($datas->hasMorePages())
        <div>
            <button type="button" wire:click="loadMore" id="loadClick" hidden></button>
            <x-loading-state-session class="w-8 h-8" wire:loading wire:target="loadMore" />
        </div>
    @else 
        <div class="text-sm font-medium text-gray-500">
            Sudah di ujung halaman
        </div>
    @endif
</div>