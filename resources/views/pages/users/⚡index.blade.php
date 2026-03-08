<?php

use Livewire\Component;
use App\Models\Collection;
use App\Models\User;

new class extends Component
{
    public $collections = null;
    public $users = null;
    public $isActive = 0;
    public function mount() {
        $this->collections = Collection::all();
        $this->users = User::with('collection')->get();
    }
    public function filterUser($param) {
        $this->users = $param != 0 ? User::with('collection')->where('collection_id', $param)->get() : User::with('collection')->get();
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
                        <tbody id="view-data">
                            @foreach($users as $user)
                            <tr wire:loading.remove>
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
                            @endforeach
                        </tbody>
                    </table>
                </x-main-section>
            </div>
        </div>
</div>