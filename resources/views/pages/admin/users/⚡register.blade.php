<?php

use Livewire\Component;
;
use App\Models\Collection;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Models\User;

new class extends Component
{
    // -------------------------- first render page -----------------------
    public $mode;
    public $noticeElement;
    public function mount()
    {
        $this->noticeElement = session('slut') ?? 'true';
        $this->mode = request()->segment(3);
        $this->dispatch('currently-page', current: request()->segment(1));
    }
    public function toImport()
    {
        session(['slut' => 'false']);
        $this->redirectRoute('users.create.import', navigate: true);
    }

    public function toSingle() 
    {
        $this->redirectRoute('users.create.single',  navigate: true);
    }

    // ----------------------------- validation ---------------------------
    public $fileRealPath;
    #[On('file-upload')]
    public function import($path) {
        $this->fileRealPath = $path;
        $this->validateOnly('fileRealPath');
    }
    
    public $fullname = '';
    public $username = '';
    public $email = '';
    public $password = '';

    public function singleRules()
    {
        return [
            'fullname' => ['required', 'max:255'],
            'username' => ['required', 'max:50'],
            'email' => ['required', 'email:dns'],
            'password' => 'required',
        ];
    }

    // ---------------------- re-render for live get error in validataion -------------------------
    public function importRules()
    {
        return [
            'fileRealPath' => 'required',
        ];
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
    public $addError = '';
    public function user()
    {
        if ($this->mode === 'import') {
            Excel::import(new UsersImport($this->newCollection(), $this->role), $this->fileRealPath, 'public');

            session()->flash('success', 'User imported successfully');
        } else if ($this->mode === 'single') {
            $user = $this->validate(array_merge($this->getRules(), $this->singleRules()));
            $user['collection_id'] = $this->newCollection();
            User::create($user);

            session()->flash('success', 'User registered successfully');
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

    // ------------------------- end hook and method -----------------------------
    public function save()
    {
        if ($this->mode === 'single') {
            $validate = array_merge($this->getRules(), $this->singleRules());
        } else {
            $validate = array_merge($this->getRules(), $this->importRules());
        }
        $this->validate($validate);

        try {
            $this->user();
            $this->redirectRoute('users.index', navigate: true); //reference
        } catch (\Exception $e) {
            $this->addError('import', $e->getMessage());
        }
    }
};
?>
<div>
    <x-header>
        <x-header-info title="Register User" desc="daftarkan user baru untuk mengakses sistem" />
        <div class="flex items-center justify-center gap-5">
            <x-header-action mode="single" wire:click="toSingle" class="{{$mode === 'single' ? 'bg-black text-white' : 'bg-slate-200'}}" />
            <x-header-action mode="import" wire:click="toImport" class="{{$mode === 'import' ? 'bg-black text-white' : 'bg-slate-200'}} {{$noticeElement === 'true' ? 'animate-bounce' : ''}}" />
        </div>
    </x-header>

    @if ($errors->any())
        @foreach ($errors->all() as $err)
        <ul>
            <li>{{$err}}</li>
        </ul>
        @endforeach
    @endif
    <x-main-form>
        <form wire:submit="save" class="w-full">
            @csrf

            @if ($mode === 'single')
            <!-- Name -->
            <div>
                <x-input-label for="fullname" :value="__('Fullname')" />
                <x-text-input id="fullname" class="block w-full" type="text" wire:model.live="fullname" placeholder="{{__('Fullname')}}" />
                <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
            </div>

            <!-- Username -->
            <div class="mt-4">
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input id="username" class="block w-full" type="text" wire:model.live="username" placeholder="{{__('Username')}}" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block w-full" type="email" wire:model.live="email" placeholder="{{__('Email')}}" />
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
                    <x-indicator-information-ping>Diperlukan heading kolom: username, fullname, email, password pada file excel</x-indicator-information-ping>
                    <livewire:file-input wire:model.live="import" label="import file" acceptExtention=".xlsx,.xls,.csv"/>
                    <x-input-error :messages="$errors->get('fileRealPath')" />
                </div>
            @endif

            <div class="mt-4" wire:ignore>
                <x-input-label for="role" :value="__('role')" />
                <x-indicator-information-ping>Role tidak bisa di tambah</x-indicator-information-ping>
                <livewire:tom-select-selection placeholder="Pilih role" wire:model.live='role' id="role">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </livewire:tom-select-selection>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />

            <div class="mt-4" wire:ignore>
                <x-input-label for="collection" :value="__('Select Collection')" class="mt-2" />
                <x-indicator-information-ping>Penambahan koleksi harus memiliki huruf</x-indicator-information-ping>
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
    </x-main-form>
</div>