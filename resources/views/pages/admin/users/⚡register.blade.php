<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Collection;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Models\User;

new #[Layout('layouts.form')] class extends Component
{
    // -------------------------- first render page -----------------------
    public $mode;
    public function mount()
    {
        $this->mode = request()->segment(3);
        $this->dispatch('currently-page', current: request()->segment(1));
    }

    // --------------------------- validation -----------------------------
    use WithFileUploads;
    public $import;
    public $fullname = '';
    public $username = '';
    public $email = '';
    public $password = '';

    public function importRules()
    {
        return ['import' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:1024']];
    }

    public function singleRules()
    {
        return [
            'fullname' => ['required', 'max:10'],
            'username' => 'required',
            'email' => ['required', 'email:dns'],
            'password' => 'required',
        ];
    }

    // --------------------------- re-render ----------------------------
    public function updatedImport()
    {
        $this->validateOnly('import', $this->importRules());
    }

    public function updated($property)
    {
        if ($this->mode === 'single' && in_array($property, ['fullname', 'username', 'email', 'password'])) {
            $this->validateOnly($property, $this->singleRules());
        }
    }

    // listener
    #[Validate('required', as: 'ROLE')] // reference
    #[Validate('in:admin,user', message: 'ulah salian ti admin atawa user mang')] // reference
    public $role = '';
    #[On('role')]
    public function role($role)
    {
        $this->role = $role;
        $this->validateOnly('role');
    }

    #[Validate(['required'])]
    public $collection_id = '';
    #[On('collection')]
    public function collection($collection)
    {
        $this->collection_id = $collection;
        $this->validateOnly('collection_id');
    }

    // ------------------------- activition model (end point) -----------------------------
    public function User()
    {
        if ($this->mode === 'import') {
            Excel::import(new UsersImport($this->newCollection(), $this->role), $this->import->getRealPath());
            
            session()->flash('success', 'User imported successful');
        } else if ($this->mode === 'single') {
            $user = $this->validate(array_merge($this->getRules(), $this->singleRules()));
            $user['collection_id'] = $this->newCollection();
            User::create($user);
            
            session()->flash('success', 'User registered successful');
        }
    }

    public function newCollection()
    {
        $collection = is_numeric($this->collection_id)
            ? Collection::findOrFail($this->collection_id)
            : Collection::firstOrCreate(['collection_name' => $this->collection_id]);

        $collection_id = $collection->id;
        return $collection_id;
    }

    // end hook with save
    public function save()
    {
        $this->validate(array_merge($this->importRules(), $this->getRules()));
        $this->User(); //DEVELOPMENT ZONE TIME

        $this->redirectRoute('users.index', navigate: true); //reference
    }
};
?>
<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <x-header-info title="Register User" desc="daftarkan user baru untuk mengakses sistem" />
            <div class="flex gap-5">
                <x-header-action mode="single" :href="route('users.create.single')" class="{{$mode === 'single' ? 'bg-black text-white' : ''}}" />
                <x-header-action mode="import" :href="route('users.create.import')" class="{{$mode === 'import' ? 'bg-black text-white' : ''}}" />
            </div>
        </div>
    </x-slot>

    <form method="POST" wire:submit="save" id="siggle-register-form">
        @csrf

        @if ($mode === 'single')
        @if (isset($user))
        @method('PUT')
        @endif
        <!-- Name -->
        <div>
            <x-input-label for="fullname" :value="__('Fullname')" />
            <x-text-input id="fullname" class="block w-full" type="text" wire:model.live="fullname" :value="old('name') ?? $user->fullname ?? ''" autofocus autocomplete="name" placeholder="{{__('Fullname')}}" />
            <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block w-full" type="text" wire:model.live="username" :value="old('username') ?? $user->username ?? ''" autofocus autocomplete="username" placeholder="{{__('Username')}}" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" wire:model.live="email" :value="old('email') ?? $user->email ?? ''" autocomplete="email" placeholder="{{__('Email')}}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block w-full"
                type="password"
                wire:model.live="password"
                placeholder="{{__('Password')}}"
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        {{-- <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block w-full"
                                type="password"
                                wire:model="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div> --}}

        @elseif ($mode === 'import')
        <div class="mt-4">
            <x-input-label for="file" :value="__('Import File')" class="mt-2" />
            <div class="flex items-center border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-2 w-full">
                <input type="file" wire:model="import" accept=".xlsx,.xls,.csv" id="file" class="block px-4 py-2 w-full" />
                <svg aria-hidden="true" wire:target="import" wire:loading class="w-5 h-5 text-neutral-quaternary me-5 animate-spin text-white fill-gray-400" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
            <x-input-error :messages="$errors->get('import')" class="" />
        </div>
        @endif

        <div class="mt-4" wire:ignore>
            <x-input-label for="role" :value="__('role')" />
            <x-indicator-information-ping info="Role tidak bisa di tambah" />
            <livewire:tom-select-selection placeholder="Pilih role" wire:model.live='role' id="role">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </livewire:tom-select-selection>
        </div>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />

        <div class="mt-4" wire:ignore>
            <x-input-label for="collection" :value="__('Select Collection')" class="mt-2" />
            <x-indicator-information-ping info="Penambahan koleksi harus memiliki huruf" />
            <livewire:tom-select-selection placeholder="Pilih koleksi yang sesuai" wire:model.live='collection_id' id="collection">
                @foreach (App\Models\Collection::all() as $collection)
                <option value="{{$collection->id}}">{{$collection->collection_name}}</option>
                @endforeach
            </livewire:tom-select-selection>
        </div>
        <x-input-error :messages="$errors->get('collection_id')" />

        <div class="flex items-center justify-end mt-4">
            <x-primary-button wire:confirm="are you sure wkwk" class="ms-4">
                {{ __('Import') }}
            </x-primary-button>
        </div>
    </form>
</div>