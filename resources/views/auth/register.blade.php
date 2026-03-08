<?php
    use Livewire\Component;
    use Livewire\Attributes\Layout;

    new #[Layout('layouts.form')] class extends Component
    {
        public $file = null;
        public $role = '';
        public $collection = '';
        
        public function render() {
            return view('/');
        }

        public function save() {
            dump($this->file);
            dump($this->role);
            dump($this->collection);
            dd();
        }
    };
?>

    {{-- @if(session('err')) --}}
    {{-- @dump(session('err')) --}}
    {{-- <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
    @endforeach
    </ul>
    </div> --}}
    {{-- @endif --}}

    @php
        $mode = request()->segment(3);
    @endphp
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <x-header-info title="Register User" desc="daftarkan user baru untuk mengakses sistem" />
                <div class="flex gap-5">
                    <x-header-action mode="single" :href="route('users.create.single')" class="{{$mode === 'single' ? 'bg-black text-white' : ''}}" />
                    <x-header-action mode="import" :href="route('users.create.import')" class="{{$mode === 'import' ? 'bg-black text-white' : ''}}" />
                </div>
        </div>
    </x-slot>

    @if ($mode === 'single')
    <form method="POST" action="{{ isset($user) ? route('users.update', $userId) : route('users.single-store') }}" id="siggle-register-form">
        @csrf
        @if (isset($user))
            @method('PUT')
        @endif
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name') ?? $user->fullname ?? ''" autofocus autocomplete="name" placeholder="{{__('Fullname')}}" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block w-full" type="text" name="username" :value="old('username') ?? $user->username ?? ''" autofocus autocomplete="username" placeholder="{{__('Username')}}" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email') ?? $user->email ?? ''" autocomplete="email" placeholder="{{__('Email')}}" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block w-full"
                type="password"
                name="password"
                placeholder="{{__('Password')}}"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        {{-- <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div> --}}

        <div class="mt-4">
            <x-input-label for="role" :value="__('role')" />
            
            <select name="role" id="role">
                <option value="" hidden>Select role user</option>
                <option value="Admin">Admin</option>
                <option value="User">User</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="collection2" :value="__('Collection')" />
            
            <select name="collection" id="collection">
                <option value="" hidden>Pilih koleksi untuk penampungan pengguna</option>
                @foreach ($collections as $collection)
                    <option value="{{$collection->id}}" {{old('collection', $user->collection_id ?? null) == $collection->id ? 'selected' : ''}}>{{$collection->collection_name}}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('collection')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    @elseif ($mode === 'import')
    {{-- Import Data Users --}}
    <form wire:submit="save" enctype="multipart/form-data" id="import-register-form">
        @csrf

        <div class="mt-4">
            <x-input-label for="file" :value="__('Import File')" class="mt-2" />
            <x-text-input type="file" name="import" id="file" class="block w-full" required />
            <x-input-error :messages="$errors->get('import')" class="" />
        </div>

        <div class="mt-4">
            <x-input-label for="role" :value="__('role')" />
            
            <select name="role" id="role">
                <option value="" hidden>Select role user</option>
                <option value="Admin">Admin</option>
                <option value="User">User</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="collection" :value="__('Select Collection')" class="mt-2" />
            <select name="collection" id="collection">
                <option value="" hidden>Pilih koleksi untuk penampungan pengguna</option>
                @foreach ($collections as $collection)
                    <option value="{{$collection->id}}">{{$collection->collection_name}}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('collection')" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Import') }}
            </x-primary-button>
        </div>
    </form>

    @endif