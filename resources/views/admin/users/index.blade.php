<x-app-layout>
    <x-slot name="header">
        <x-header-info title="Manajemen User" desc="kelola data user yang terdaftar di dalam sistem" />
    </x-slot>

    <div class="flex flex-col px-10">
        <div class="backdrop-blur-xs py-5 sticky top-0">
            <form id="collection" class="flex space-x-4">
                @csrf
                <button type="submit" name="c-id" value="" class="py-1 px-3 rounded-lg inline-flex font-bold capitalize bg-gray-900 text-white">{{ __('semua') }}</button>
                @foreach ($collections as $c)
                    <button type="submit" name="c-id" value="{{ $c['id'] }}" class="py-1 px-3 rounded-lg inline-flex font-bold capitalize">{{ $c['collection_name'] }}</button>
                @endforeach
            </form>
        </div>
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
                            <th class="border" colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody id="view-data">
                        @foreach($users as $user)
                        <tr>
                            <td class="border px-4 py-3 text-center">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-3 capitalize">{{ $user->fullname }}</td>
                            <td class="border px-4 py-3">{{ $user->username }}</td>
                            <td class="border px-4 py-3">{{ $user->email }}</td>
                            <td class="border px-4 py-3 capitalize">{{ $user->role }}</td>
                            <td class="border px-4 py-3 capitalize">{{ $user->collection->collection_name }}</td>
                            <td class="border px-4 py-3 text-center"><a href="" class="inline-flex bg-yellow-500 px-4 py-2 text-white rounded-md">{{ __('Lihat') }}</a></td>
                            <td class="border px-4 py-3 text-center"><a href="" class="inline-flex bg-blue-500 px-4 py-2 text-white rounded-md">{{ __('Edit') }}</a></td>
                            <td class="border px-4 py-3 text-center"><a href="" class="inline-flex bg-red-500 px-4 py-2 text-white rounded-md">{{ __('Hapus') }}</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-main-section>
        </div>
    </div>
    <script>
        document.getElementById('collection').addEventListener('submit', function(e) {
            e.preventDefault();
            let a = document.querySelectorAll("#collection button").forEach(e => {
                e.classList.remove('bg-gray-900', 'text-white')
            })
            e.submitter.classList.add('bg-gray-900', 'text-white')

            let tableData = document.getElementById('view-data')
            tableData.innerHTML = '<tr><td colspan="7" class="text-center font-bold px-4 py-3 border">Load Data...</td></tr>'
            
            let formData = new FormData(this);
            formData.append('c-id', e.submitter.value) // karna value dari button tidak bisa dikirim melalu response Request

            fetch("{{route('users.data')}}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        // 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    tableData.innerHTML = ''
                    if (data.users.length == 0) tableData.innerHTML = `<tr><td colspan="7" class="border px-4 py-3 text-center capitalize">data in collection ${e.submitter.textContent} is not found</td></tr>`
                    data.users.forEach((e, i) => {
                        tableData.innerHTML += `
                            <tr>
                                <td class="border px-4 py-3 text-center">${i + 1}</td>
                                <td class="border px-4 py-3 capitalize">${e.fullname}</td>
                                <td class="border px-4 py-3">${e.username}</td>
                                <td class="border px-4 py-3">${e.email}</td>
                                <td class="border px-4 py-3 capitalize">${e.role}</td>
                                <td class="border px-4 py-3 capitalize">${e.collection?.collection_name}</td>
                                <td class="border px-4 py-3 text-center"><a href="" class="inline-flex bg-yellow-500 px-4 py-2 text-white rounded-md">{{ __('Lihat') }}</a></td>
                                <td class="border px-4 py-3 text-center"><a href="" class="inline-flex bg-blue-500 px-4 py-2 text-white rounded-md">{{ __('Edit') }}</a></td>
                                <td class="border px-4 py-3 text-center"><a href="" class="inline-flex bg-red-500 px-4 py-2 text-white rounded-md">{{ __('Hapus') }}</a></td>
                            </tr>
                        `
                    });
                })
                .catch(err => console.log(err))
        })
    </script>
</x-app-layout>