<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/element/observer.js'])

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
    <div class="min-h-screen max-w-full bg-gray-100">
        <div class="flex flex-col" x-data="{open: JSON.parse(localStorage.getItem('sidebar-toggle') ?? 'true')}" >
            @include('layouts.navigation')
            
            <div class="flex min-w-0">
                @include('layouts.sidebar')
            
                <main class="min-w-0">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @livewireScripts
    </div>
</body>

</html>