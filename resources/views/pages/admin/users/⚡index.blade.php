<?php

use Livewire\Component;
use App\Models\Collection;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\Attributes\On;

new class extends Component
{
    public $collections = [];
    public $activeCollection = 0;
    
    public $perPage = [0 => 15];

    public function mount() {
        $this->collections = Collection::all();
        foreach ($this->collections as $c){
            $this->perPage[$c->id] = 1;
        }
        $this->perPage[$this->activeCollection] += 10;
        }
        
    public function loadMore() {
        $this->perPage[$this->activeCollection] += 10;
    }

    public $searchKey = '';
    #[On('search-key')]
    public function search($key) {
        $this->searchKey = $key;
    }

    public function render() {
        $query = User::with('collection')->latest('fullname')->whereAny(['fullname', 'username', 'email'], 'LIKE', '%' . $this->searchKey . '%');
        if ($this->activeCollection != 0) {
            $query->where('collection_id', $this->activeCollection);
        }
        $users = $query->paginate($this->perPage[$this->activeCollection]);
        return view('pages.admin.users.⚡index', ['users' => $users]);
    }

    public $user;
    public function destroyUser($userId) {
        $this->user = $userId;
        User::findOrFail($this->user)->delete();
        session()->flash('success', 'akun berhasil di hapus');
    }

    public $statusEnum = ['active', 'disabled'];
    public function suspendedAccount($userId) {
        $this->user = $userId;
        $statusAccount = User::findOrFail($this->user);
        $currentStatus = $statusAccount->status;
        $statusMissing = collect($this->statusEnum)->diff($currentStatus);
        $statusAccount->update(['status' => $statusMissing->first()]);
    }
};
?>
<div>
    <x-header>
        <x-header-info 
            title="Manajemen User" 
            desc="Kelola data user yang terdaftar di dalam sistem" 
        />

        <livewire:search-input />
    </x-header>

    <div class="flex flex-col px-10">
        <livewire:nav-slide-filter :toggleButton="$collections" wire:model.live="activeCollection" />

        <div class="flex justify-center">
            <x-main-section>
                <x-session-success />
                <table class="table-fixed w-full">
                    <thead>
                        <tr>
                            <th class="border w-[5%]">No</th>
                            <th class="border w-[20%]">Name</th>
                            <th class="border w-[20%]">Username</th>
                            <th class="border w-[20%]">Email</th>
                            <th class="border w-[10%]">Role</th>
                            <th class="border w-[10%]">Collection</th>
                            <th class="border w-[25%]" colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody id="view-data">
                        @forelse($users as $user)

                        <tr wire:loading.remove wire:target="filterUser">
                            <td class="border px-4 py-3 text-center">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-3 capitalize">{{ Str::words($user->fullname, 2, ' ...') }}</td>
                            <td class="border px-4 py-3">{{ $user->username }}</td>
                            <td class="border px-4 py-3">{{ $user->email }}</td>
                            <td class="border px-4 py-3 capitalize text-center">{{ $user->role }}</td>
                            <td class="border px-4 py-3 capitalize">{{ $user->collection->collection_name }}</td>
                            <td class="border px-1 py-1 text-center"><a href="" class="inline-flex bg-yellow-500 px-4 py-2 text-white rounded-md">{{ __('Lihat') }}</a></td>
                            <td class="border px-1 py-1 text-center"><button wire:confirm="Are you sure want to {{collect($statusEnum)->diff($user->status)->first()}} this account?" wire:click="suspendedAccount({{$user->id}})" class="inline-flex bg-gray-800 px-4 py-2 text-white rounded-md">
                                @if ($user->status === 'active')
                                    {{ __('Blokir') }}
                                @else
                                    {{ __('Buka Blokir') }}
                                @endif
                            </button></td>
                            <td class="border px-1 py-1 text-center">
                                    <button wire:click="destroyUser({{$user->id}})" wire:confirm="apakah kamu yakin ingin menghapus user ini?" class="inline-flex bg-red-500 px-4 py-2 text-white rounded-md hover:cursor-pointer">{{ __('Hapus') }}</button>
                            </td>
                        </tr>
                        @empty
                            <div >
                                <tr>
                                    <td wire:loading.remove colspan="9" class="px-4 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            {{-- Icon --}}
                                            <div class="text-4xl text-gray-400">
                                                👨‍👩‍👧‍👦
                                            </div>
                                            {{-- Title --}}
                                            <h2 class="text-base font-semibold text-gray-700">
                                                Data pengguna tidak ditemukan
                                            </h2>
                                            {{-- Description --}}
                                            <p class="text-sm text-gray-500">
                                                @if($searchKey)
                                                    Tidak ada hasil untuk
                                                    <span class="font-medium text-gray-700">"{{ $searchKey }}"</span>
                                                @endif
                                                @if($activeCollection)
                                                    di koleksi
                                                    <span class="font-medium text-gray-700">
                                                        {{ $collections->firstWhere('id', $activeCollection)['collection_name'] ?? 'tidak diketahui' }}
                                                    </span>
                                                @else
                                                    di semua koleksi
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </div>
                        @endforelse
                    </tbody> 
                </table>
                <div wire:loading wire:target="activeCollection" class="w-full">
                    <div class="flex justify-center py-3">
                        <div class="px-2 py-px ring-1 ring-inset ring-brand-subtle text-center text-fg-brand-strong text-md font-medium rounded-sm bg-brand-softer animate-pulse">
                            Loading...
                        </div>
                    </div>
                </div>
                <div wire:loading.remove wire:target="activeCollection" class="w-full flex justify-center py-5" id="spinner-load-data">
                    @if ($users->hasMorePages())
                        <div>
                            <button type="button" wire:click="loadMore" id="loadClick" hidden></button>
                            <x-loading-state-session class="w-8 h-8" wire:loading wire:target="loadMore" />
                        </div>
                    @else 
                        <div class="font-bold capitalize">sudah di ujung halaman</div>
                    @endif
                </div>
            </x-main-section>
        </div>
    </div>
</div>