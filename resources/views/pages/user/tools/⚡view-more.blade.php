<?php

use Livewire\Component;
use App\Models\Tool;
use App\Models\Borrow;

new class extends Component
{
    public $idTool;
    public $userId;
    public $tools = []; 

    public function mount($idTool) {
        $this->userId = Auth::id();
        $this->tools = Tool::findOrFail($idTool);
    }

    public function cart($toolId) {
        Borrow::updateOrCreate([
            'status' => 'draft',
            'user_id' => Auth::id(),
            'tool_id' => $toolId,
        ],[
            'user_id' => Auth::id(), 
            'tool_id' => $toolId, 
            'penalty_id' => 1
        ]);
        $this->redirectRoute('borrowing.user.request', navigate: true);
    }
};
?>

<div class="max-w-5xl mx-auto p-6 shadow">
    
    <x-slot name="headerSection">
        <div class="flex justify-between px-4">
            <a href="{{route('tools.user')}}" wire:navigate>Kembali</a>
        </div>
    </x-slot>
    
    <div class="grid md:grid-cols-3 gap-8">
        <div class="flex justify-center">
            <img 
                src="{{ asset('storage/' . $tools->cover_path) }}" 
                alt="{{ $tools->name_tool }}"
                class="w-64 rounded-xl shadow-lg transition duration-300"
            >
        </div>

        <div class="md:col-span-2 flex flex-col justify-between">

            <div>
                <h1 class="text-3xl font-bold text-heading mb-2">
                    {{ $tools->name_tool }}
                </h1>

                <p class="text-lg text-body mb-1">
                    by {{ $tools->author  }}
                </p>

                <p class="text-sm text-gray-500 mb-4">
                    {{ $tools->category->category_name }}
                </p>

                <span class="inline-block px-3 py-1 text-sm rounded-full 
                    {{ $tools->status === 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $tools->status }}
                </span>

                <div class="mt-4 text-sm text-gray-600 space-y-1">
                    <p>Tahun: {{ $tools->year_published }}</p>
                    <p>Stok Tersedia: {{ $tools->qty }}</p>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button wire:click="cart({{$idTool}})" class="cursor-pointer px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Pinjam barang
                </button>

                {{-- <button class="px-5 py-2.5 border rounded-lg hover:bg-gray-100 transition">
                    Mark
                </button> --}}
            </div>
        </div>
    </div>

    <div class="mt-10">
        <h2 class="text-xl font-semibold mb-3">Deskripsi barang</h2>
        <p class="text-body leading-relaxed text-gray-700">
            {{ $tools->description_tool }}
        </p>
    </div>
</div>