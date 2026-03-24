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
        <div class="min-h-screen min-w-full bg-gray-100">
            @include('layouts.navigation')

            <main>
                {{ $slot }}
            </main>
            @livewireScripts
        </div>
    </body>
</html>
