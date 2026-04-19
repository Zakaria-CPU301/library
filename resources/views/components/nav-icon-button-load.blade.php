<div class="w-10 h-9 flex justify-center items-center">
    <button
        wire:click="{{$target}}" type="button"
        wire:confirm="{{$confirm}}" 
        wire:loading.remove wire:target="{{$target}}" 
        class="relative z-50 bg-red-500 hover:bg-red-400 text-white px-3 py-1.5 rounded-lg cursor-pointer hover:shadow-xl transition duration-200">
        <i class="{{$i}}"></i>
    </button>
    <x-loading-state-session class="h-5 w-5" wire:loading wire:target="{{$target}}" /> 
</div>