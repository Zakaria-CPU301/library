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

<body class="font-sans antialiased">
    <div class="h-screen w-full bg-gray-100 overflow-hidden">
        <div 
            class="flex flex-col h-full" 
            x-data="{open: JSON.parse(localStorage.getItem('sidebar-open') ?? 'true')}"
        >
            <div class="shrink-0">
                @include('layouts.navigation')
            </div>

            <div class="flex flex-1 min-h-0">
                <aside class="shrink-0 bg-white">
                    <div class="h-full overflow-y-scroll">
                        @include('layouts.sidebar')
                    </div>
                </aside>

                <main class="flex flex-col min-w-0 w-full">
                    @isset ($headerFilter)
                    <div class="bg-white shrink-0 py-2.5">
                        {{$headerFilter}}
                    </div>
                    @endisset

                    <div class="overflow-y-auto px-10">
                        {{ $slot }}
                    </div>
                </main>

            </div>
        </div>

        @livewireScripts
    </div>
</body>

</html>