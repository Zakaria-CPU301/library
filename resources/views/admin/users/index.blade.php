<x-app-layout>
    <x-slot name="header">
        <x-header-info title="Manajemen User" desc="kelola data user yang terdaftar di dalam sistem"/>
    </x-slot>
    
    <div class="flex justify-center">
        <x-main-section>
            @if (session('success'))
                <div class="text-sm text-green-600">
                    {{ session('success') }}
                </div>
            @endif
                <table>
                    <thead>
                        <tr>
                            <th class="border">No</th>
                            <th class="border">Name</th>
                            <th class="border">Username</th>
                            <th class="border">Email</th>
                            <th class="border">Role</th>
                            <th class="border">Collection</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2 capitalize">{{ $user->fullname }}</td>
                            <td class="border px-4 py-2">{{ $user->username }}</td>
                            <td class="border px-4 py-2">{{ $user->email }}</td>
                            <td class="border px-4 py-2 capitalize">{{ $user->role }}</td>
                            <td class="border px-4 py-2 capitalize">{{ $user->collection->collection_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </x-main-section>
    </div>
</x-app-layout>