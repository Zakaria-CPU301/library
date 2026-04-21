<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Laravel' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Tom Select  --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.5.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.5.2/dist/js/tom-select.complete.min.js"></script>
    
    {{-- flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *::-webkit-scrollbar {
            display: none;
        }

        * {
            scrollbar-width: none;
        }
    </style>
    @livewireStyles
</head>

<body class="font-sans antialiased">
    @php
        $currentUser = Auth::user();
    @endphp
    <div class="min-h-screen w-full bg-gray-100">
        <div 
            class="flex flex-col min-h-screen" 
            x-data="{open: JSON.parse(localStorage.getItem('sidebar-open') ?? 'true')}"
        >
            @isset ($currentUser)
                @include('layouts.navigation')
            @endisset

            <div class="flex">
                @if ($currentUser)
                <div class="top-16.25 h-[calc(100vh-65px)] sticky bg-white w-auto">
                    @include('layouts.sidebar')
                </div>
                @endif
                
                <main class="flex flex-col min-w-0 w-full">
                    <div class="{{$currentUser ? '' : 'flex h-screen'}} px-10">
                        {{ $slot }}
                    </div>
                </main>

                @if (request()->segment(1) != 'login')
                    <div x-data="{markOpen: JSON.parse(localStorage.getItem('mark-open') ?? 'false')}"
                        @open-mark.window="
                            markOpen = true;
                            localStorage.setItem('mark-open', markOpen)
                        ">
                        <livewire:mark />
                    </div>
                @endif
            </div>
        </div>

        @livewireScripts
    </div>
</body>

</html>