<x-guest-layout>
    @php
    $request = request('action');
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <x-header-info title="Register User" desc="daftarkan user baru untuk mengakses sistem" />
            <div class="flex gap-5">
                <x-header-action id="single" class="{{$request === 'single' ? 'bg-black text-white' : ''}}" />
                <x-header-action id="import" class="{{$request === 'import' ? 'bg-black text-white' : ''}}" />
            </div>
        </div>
    </x-slot>

    @if ($request === 'single')
    <form method="POST" action="{{ route('users.single-store') }}" id="siggle-register-form">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" autofocus autocomplete="name" placeholder="{{__('Fullname')}}" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block w-full" type="text" name="username" :value="old('username')" autofocus autocomplete="username" placeholder="{{__('Username')}}" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" autocomplete="email" placeholder="{{__('Email')}}" />
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
            <x-input-label for="collection" :value="__('Collection')" />
            <select name="collection" id="collection">
                <option value="" hidden>Select Collection For User Storage"</option>
                @foreach ($collections as $collection)
                    <option value="{{$collection['id']}}">{{$collection['collection_name']}}</option>
                @endforeach
            </select>
            <script>
                new TomSelect("#collection", {
                    create: true,
                    createFilter: function(input) {
                        input = input.trim().toLowerCase();

                        for (let key in this.options) {
                            let existingText = this.options[key].text
                                .trim()
                                .toLowerCase();
                            if (existingText === input) {
                                return false; 
                            }
                        }
                        return true; 
                    },
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            </script>
            <x-input-error :messages="$errors->get('collection')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    @elseif ($request === 'import')
    {{-- Import Data Users --}}
    <form action="{{route('users.import-store')}}" method="post" enctype="multipart/form-data" id="import-register-form">
        @csrf

        <div class="mt-4">
            <x-input-error :messages="$errors->get('import')" class="" />
            <x-input-label for="file" :value="__('Import File')" class="mt-2" />
            <x-text-input type="file" name="import" id="file" class="block w-full" required />
        </div>

        <div class="mt-4">
            <x-input-error :messages="$errors->get('collection')" />
            <x-input-label for="collection" :value="__('Select Collection')" class="mt-2" />
            <select name="collection" id="collection">
                <option value="" hidden>Select Collection For User Storage"</option>
                @foreach ($collections as $collection)
                <option value="{{$collection['id']}}">{{$collection['collection_name']}}</option>
                @endforeach
            </select>
            <script>
                new TomSelect("#collection", {
                    create: true,
                    createFilter: function(input) {
                        input = input.trim().toLowerCase();

                        for (let key in this.options) {
                            let existingText = this.options[key].text
                                .trim()
                                .toLowerCase();
                            if (existingText === input) {
                                return false; 
                            }
                        }
                        return true; 
                    },
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            </script>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                {{ __('Import') }}
            </x-primary-button>
        </div>
    </form>
    @endif
</x-guest-layout>