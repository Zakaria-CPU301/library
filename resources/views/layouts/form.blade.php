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

        <!-- Tom Select -->
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.5.2/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.5.2/dist/js/tom-select.complete.min.js"></script>
        
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
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="flex flex-col min-h-screen w-full pt-6 sm:pt-0" x-data="{open: JSON.parse(localStorage.getItem('sidebar-toggle') ?? 'true')}">
            @if(Auth::user())
                @include('layouts.navigation')
            @endif

            <div class="flex">
                @if (Auth::user())
                    @include('layouts.sidebar')
                @endif
                
                <main class="flex flex-col w-full min-h-screen">
                    {{ $slot }}
                </main>
            </div>
            @livewireScripts
        </div>
    </body>
</html>
