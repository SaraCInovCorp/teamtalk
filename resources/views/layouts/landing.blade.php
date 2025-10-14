<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'TeamTalk')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gradient-to-tr from-blue-100 via-green-50 to-white min-h-screen">
    @livewire('navigation-menu')

    <main class="container mx-auto py-8">
        @yield('content')
    </main>

    <footer class="text-center text-gray-500 py-8">TeamTalk &copy; {{ date('Y') }}</footer>
    @livewireScripts
</body>
</html>
