<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'TeamTalk') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    <script defer src="//unpkg.com/alpinejs"></script>
</head>
<body class="font-sans antialiased">

    <x-banner />

    @livewire('navigation-menu')

    <div class="min-h-screen flex bg-gray-100">
    <!-- Sidebar fixa -->
        <aside class="hidden md:block w-72 bg-white border-r border-gray-200 overflow-y-auto">
            @livewire('chat-rooms')
        </aside>

        <div class="flex-1 flex flex-col">
            @if (isset($header))
                <header class="bg-white shadow p-4">
                    <div class="max-w-7xl mx-auto">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-1 overflow-auto p-6 bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>


    @stack('modals')

    @livewireScripts

</body>
</html>
