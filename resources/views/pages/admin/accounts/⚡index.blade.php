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
    public $idDataShow = 0;
    public $asd = '';

    #[On('slide-filter')]
    public function navFilter($param)
    {
        $this->activeCollection = $param;
    }

    public $perPage = [0 => 10];

    public function mount()
    {
        $this->showDatas = User::all();

        $this->collections = Collection::all();
        foreach ($this->collections as $c) {
            $this->perPage[$c->id] = 10;
        }
        $this->perPage[$this->activeCollection] += 10;
    }

    public function loadMore()
    {
        $this->perPage[$this->activeCollection] += 10;
    }

    public $searchKey = '';
    #[On('search-key')]
    public function search($key)
    {
        $this->searchKey = $key;
    }

    public function render()
    {
        $query = User::with('collection')->latest('fullname')->whereAny(['fullname', 'username', 'email'], 'LIKE', '%' . $this->searchKey . '%');
        if ($this->activeCollection != 0) {
            $query->where('collection_id', $this->activeCollection);
        }
        $users = $query->paginate($this->perPage[$this->activeCollection]);
        $showData = User::find($this->idDataShow);
        return view('pages.admin.accounts.⚡index', ['users' => $users, 'dataShow' => $showData]);
    }

    public $user;
    public function destroyUser($userId)
    {
        $this->user = $userId;
        User::findOrFail($this->user)->delete();
        session()->flash('success', 'akun berhasil di hapus');
    }

    public $statusEnum = ['active', 'disabled'];
    public function suspendedAccount($userId)
    {
        $this->user = $userId;
        $statusAccount = User::findOrFail($this->user);
        $currentStatus = $statusAccount->status;
        $statusMissing = collect($this->statusEnum)->diff($currentStatus);
        $statusAccount->update(['status' => $statusMissing->first()]);
    }

    public function showModal($userId) {
        $this->idDataShow = $userId;
    }
};
?>
<div>
    <x-slot name="searchEngine"></x-slot>
    <livewire:slide-filter wire:model.live="activeCollection" :toggleButton="$collections" />

    <x-header class="border-b">
        <x-header-info
            title="Manajemen User"
            desc="Kelola data user yang terdaftar di dalam sistem" />

        <x-add-navigate i="bi bi-person-plus" label="add user(s)" :href="route('account.create.single')" />
    </x-header>

    <div class="w-full justify-center mt-5" x-data="{openModal: false}">
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

                <tr wire:loading.remove wire:target="filterUser" wire:loading.class="data-loading:blur-2xl">
                    <td class="border px-4 py-3 text-center">{{ $loop->iteration }}</td>
                    <td class="border px-4 py-3 capitalize">{{ Str::words($user->fullname, 2, ' ...') }}</td>
                    <td class="border px-4 py-3">{{ $user->username }}</td>
                    <td class="border px-4 py-3">{{ $user->email }}</td>
                    <td class="border px-4 py-3 capitalize text-center">{{ $user->role }}</td>
                    <td class="border px-4 py-3 capitalize">{{ $user->collection->collection_name }}</td>
                    <td class="border px-1 py-1 text-center" colspan="{{$user->email != 'superadmin@gmail.com' ? '1' : '3'}}">
                        <button wire:click="showModal({{$user->id}})" @click="openModal = true" class="inline-flex bg-yellow-500 px-4 py-2 text-white rounded-md cursor-pointer">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                    @if ($user->email != 'superadmin@gmail.com')
                        <td class="border px-1 py-1 text-center">
                            <button wire:confirm="Are you sure want to {{collect($statusEnum)->diff($user->status)->first()}} this account?" wire:click="suspendedAccount({{$user->id}})" class="inline-flex bg-gray-800 px-4 py-2 text-white rounded-md cursor-pointer">
                                @if ($user->status === 'active')
                                <i class="bi bi-ban"></i>
                                @else
                                <i class="bi bi-unlock"></i>
                                @endif
                            </button>
                        </td>
                        <td class="border px-1 py-1 text-center">
                            <button wire:click="destroyUser({{$user->id}})" wire:confirm="apakah kamu yakin ingin menghapus user ini?" class="inline-flex bg-red-500 px-4 py-2 text-white rounded-md cursor-pointer"><i class="bi bi-trash"></i></button>
                        </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-10 text-center">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <div class="text-4xl text-gray-400">
                                👨‍👩‍👧‍👦
                            </div>

                            <h2 class="text-base font-semibold text-gray-700">
                                Data pengguna tidak ditemukan
                            </h2>

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
                @endforelse
            </tbody>
        </table>

        <x-modal-detail labelModal="detail information">
            <div class="bg-white p-10 rounded-xl">
                <ul class="space-y-2 text-gray-600">
                    @isset($dataShow)
                    <li>🥸 Nama Lengkap: <span class="font-medium text-black">{{$dataShow->fullname}}</span></li>
                    <li>👤 Username: <span class="font-medium text-black">{{$dataShow->username}}</span></li>
                    <li>📝 Email: <span class="text-sm">{{$dataShow->email}}</span></li>
                    <li>📅 Role: <span class="font-medium text-black">{{$dataShow->role}}</span></li>
                    <li>📌 Status: <span class="{{$dataShow->status == 'active' ? 'text-green-600' : 'text-red-600' }} font-medium ">{{$dataShow->status}}</span></li>
                    <li>👨‍👩‍👧‍👦 Collection: <span class="font-medium text-green-600">{{$dataShow->collection->collection_name}}</span></li>
                    @endisset
                </ul>
            </div>
        </x-modal-detail>

        <x-loading-indicator target="activeCollection" />

        <x-has-more-page target="activeCollection" :datas="$users" />
    </div>
</div>