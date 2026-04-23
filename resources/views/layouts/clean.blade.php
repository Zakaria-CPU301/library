<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Landing Page</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-linear-to-br from-orange-50 via-white to-orange-100 text-gray-800 min-h-screen flex items-center">
    <div class="w-full">{{$slot}}</div>

    @livewireScripts
</body>
</html>