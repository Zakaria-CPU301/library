<x-app-layout>
    <div id="header">
        <x-slot name="header">
            <x-header-info title="Manajemen User" desc="kelola data user yang terdaftar di dalam sistem" />
        </x-slot>
    </div>

    <div class="flex flex-col px-10">
        <div id="nav-collection" class="backdrop-blur-xs px-5 py-5 sticky top-5 duration-500 rounded-lg">
            <form id="collection" class="flex space-x-4">
                @csrf
                <button type="submit" name="c-id" value="" class="py-1 px-3 rounded-lg inline-flex font-bold capitalize cursor-pointer hover:bg-slate-700 hover:text-white duration-100 bg-gray-900 text-white">{{ __('semua') }}</button>
                @foreach ($collections as $c)
                    <button type="submit" name="c-id" value="{{ $c['id'] }}" class="py-1 px-3 rounded-lg inline-flex font-bold capitalize cursor-pointer hover:bg-slate-700 hover:text-white duration-200">{{ $c['collection_name'] }}</button>
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
                        <tr>
                            <td class="border px-4 py-3 text-center back">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-3 capitalize">{{ Str::words($user->fullname, 2, ' ...') }}</td>
                            <td class="border px-4 py-3">{{ $user->username }}</td>
                            <td class="border px-4 py-3">{{ $user->email }}</td>
                            <td class="border px-4 py-3 capitalize">{{ $user->role }}</td>
                            <td class="border px-4 py-3 capitalize">{{ $user->collection->collection_name }}</td>
                            <td class="border px-1 py-1 text-center"><a href="" class="inline-flex bg-yellow-500 px-4 py-2 text-white rounded-md">{{ __('Lihat') }}</a></td>
                            <td class="border px-1 py-1 text-center"><a href="" class="inline-flex bg-blue-500 px-4 py-2 text-white rounded-md">{{ __('Edit') }}</a></td>
                            <td class="border px-1 py-1 text-center"><a href="" class="inline-flex bg-red-500 px-4 py-2 text-white rounded-md">{{ __('Hapus') }}</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-main-section>
        </div>
    </div>
    <script>
        const observer = new IntersectionObserver(entriesObject => {
            entriesObject.forEach(entry => {
                if (!entry.isIntersecting) {
                    console.log(this)
                    document.getElementById('nav-collection').classList.add('contain-nav')
                } else {
                    document.getElementById('nav-collection').classList.remove('contain-nav')
                }
            })
        })
        observer.observe(document.getElementById('header'))
        
        document.getElementById('collection').addEventListener('submit', function(e) {
            e.preventDefault();
            document.querySelectorAll("#collection button").forEach(e => {
                e.classList.remove('bg-gray-900', 'text-white')
            })
            e.submitter.classList.add('bg-gray-900', 'text-white')

            let tableData = document.getElementById('view-data')
            // tableData.innerHTML = '<tr><td colspan="9" class="text-center font-bold px-4 py-3 border">Load Data...</td></tr>'
            let dataRowCollection = ''

            
            let formData = new FormData(this);
            formData.append('c-id', e.submitter.value)

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
                    if (data.users.length == 0) dataRowCollection = `<tr><td colspan="9" class="border px-4 py-3 text-center capitalize">data in collection ${e.submitter.textContent} is not found</td></tr>`
                    data.users.forEach((e, i) => {
                        dataRowCollection += `
                            <tr>
                                <td class="border px-4 py-3 text-center">${i + 1}</td>
                                <td class="border px-4 py-3 capitalize">${e.fullname}</td>
                                <td class="border px-4 py-3">${e.username}</td>
                                <td class="border px-4 py-3">${e.email}</td>
                                <td class="border px-4 py-3 capitalize">${e.role}</td>
                                <td class="border px-4 py-3 capitalize">${e.collection?.collection_name}</td>
                                <td class="border px-1 py-1 text-center"><a href="" class="inline-flex bg-yellow-500 px-4 py-2 text-white rounded-md">{{ __('Lihat') }}</a></td>
                                <td class="border px-1 py-1 text-center"><a href="" class="inline-flex bg-blue-500 px-4 py-2 text-white rounded-md">{{ __('Edit') }}</a></td>
                                <td class="border px-1 py-1 text-center"><a href="" class="inline-flex bg-red-500 px-4 py-2 text-white rounded-md">{{ __('Hapus') }}</a></td>
                            </tr>
                        `
                    });
                    tableData.innerHTML = dataRowCollection;
                })
                .catch(err => console.log(err))
        })
    </script>
</x-app-layout>