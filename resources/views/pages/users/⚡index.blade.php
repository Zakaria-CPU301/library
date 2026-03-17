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

    public $perPage = [0 => 10];

    public function mount() {
        $this->collections = Collection::all();
        foreach ($this->collections as $c){
            $this->perPage[$c->id] = 10;
        }
    }
    
    #[On(event: 'load-more')]
    public function loadMore() {
        $this->perPage[$this->isActive] += 10;
    }

    public function render() {
        $query = User::with('collection');
        if ($this->isActive != 0) {
            $query->where('collection_id', $this->isActive);
        }
        $users = $query->paginate($this->perPage[$this->isActive]);
        return view('pages.users.⚡index', ['users' => $users]);
    }

    public function filterUser($param) {
        $this->isActive = $param;
    }
};
?>
<div>
    <div id="header">
        <x-slot name="header">
            <x-header-info title="Manajemen User" desc="kelola data user yang terdaftar di dalam sistem" />
        </x-slot>
    </div>

    <div class="flex flex-col px-10">
        <div id="nav-collection" class="backdrop-blur-lg mt-1 px-5 py-5 sticky top-5 duration-500 rounded-lg">
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
                @if (session('success'))
                <div class="text-sm text-green-600">
                    {{ session('success') }}
                </div>
                @endif
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
                            <td class="border px-4 py-3 capitalize">{{ $user->role }}</td>
                            <td class="border px-4 py-3 capitalize">{{ $user->collection->collection_name }}</td>
                            <td class="border px-1 py-1 text-center"><a href="" class="inline-flex bg-yellow-500 px-4 py-2 text-white rounded-md">{{ __('Lihat') }}</a></td>
                            <td class="border px-1 py-1 text-center"><a href="{{route('users.edit', ['userId' => $user->id, 'action' => 'single'])}}" class="inline-flex bg-blue-500 px-4 py-2 text-white rounded-md">{{ __('Edit') }}</a></td>
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
                                <td colspan="9" class="border px-4 py-3 text-center capitalize">user data with collection <b>{{$collections->firstWhere('id', $isActive)['collection_name']}}</b> not found</td>
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
                            <svg wire:loading aria-hidden="true" class="w-8 h-8 me-2 text-gray-500 text-neutral-quaternary animate-spin fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
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