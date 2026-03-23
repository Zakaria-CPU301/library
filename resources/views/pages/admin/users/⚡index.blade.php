<?php

use Livewire\Component;
use App\Models\Collection;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\Attributes\On;

new class extends Component
{
    public $collections = '';
    public $isActive = 0;

    public $perPage = [0 => 15];

    public function mount() {
        $this->collections = Collection::all();
        foreach ($this->collections as $c){
            $this->perPage[$c->id] = 10;
        }
    }
    
    public function loadMore() {
        $this->perPage[$this->isActive] += 10;
    }

    public $searchKey = '';
    #[On('search-key')]
    public function search($key) {
        $this->searchKey = $key;
    }

    public function render() {
        $query = User::with('collection')->latest('fullname')->whereAny(['fullname', 'username', 'email'], 'LIKE', '%' . $this->searchKey . '%');
        if ($this->isActive != 0) {
            $query->where('collection_id', $this->isActive);
        }
        $users = $query->paginate($this->perPage[$this->isActive]);
        return view('pages.admin.users.⚡index', ['users' => $users]);
    }

    public function filterUser($param) {
        $this->isActive = $param;
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
        <div id="nav-collection" wire:ignore.self class="mt-1 px-5 py-5 sticky top-5 duration-500 rounded-lg">
            <div id="collection" class="flex space-x-4 overflow-x-scroll">
                @csrf
                <button type="button" wire:click="filterUser({{0}})" class="{{$isActive == 0 ? 'bg-gray-900 text-white' : ''}} px-4 py-2 rounded-lg inline-flex font-bold capitalize cursor-pointer hover:bg-slate-700 hover:text-white duration-100">{{ __('semua') }}</button>
                @foreach ($collections as $c)
                    <button type="button" wire:click="filterUser({{$c->id}})" class="{{$isActive == $c->id ? 'bg-gray-900 text-white' : ''}} whitespace-nowrap px-4 py-2 rounded-lg inline-flex font-bold capitalize cursor-pointer hover:bg-slate-700 hover:text-white duration-200 ">{{ $c->collection_name }}</button>
                @endforeach
            </div>
        </div>

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
                    {{-- @dd($users) --}}
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
                            {{-- Admin Tidak Bisa Edit User --}}
                            <td class="border px-1 py-1 text-center">
                                <form action="{{route('users.destroy', $user->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('apakah kamu yakin ingin menghapus user ini?')" class="inline-flex bg-red-500 px-4 py-2 text-white rounded-md">{{ __('Hapus') }}</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr wire:loading.remove wire:target="filterUser">
                                <td colspan="9" class="border px-4 py-3 text-center capitalize">pencarian data pengguna <b>{{isset($searchKey) ? $searchKey : ''}}</b> untuk koleksi <b>{{$collections->firstWhere('id', $isActive)['collection_name'] ?? 'semua'}}</b> tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody> 
                </table>
                <div wire:loading wire:target="filterUser" class="w-full">
                    <div class="flex justify-center py-3 border-x border-b">
                        <div class="px-2 py-px ring-1 ring-inset ring-brand-subtle text-center text-fg-brand-strong text-md font-medium rounded-sm bg-brand-softer animate-pulse">
                            Loading...
                        </div>
                    </div>
                </div>
                <div wire:loading.remove wire:target="filterUser" class="bg-slate-100 w-full flex justify-center py-5" id="spinner-load-data">
                    @if ($users->hasMorePages())
                        <div>
                            <x-loading-state-session />
                            <span class="sr-only">Loading...</span>
                        </div>
                    @else 
                        <div class="font-bold capitalize">sudah di ujung halaman</div>
                    @endif
                </div>
            </x-main-section>
        </div>
    </div>
</div>