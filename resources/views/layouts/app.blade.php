<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'TeamTalk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireStyles
    <script defer src="//unpkg.com/alpinejs" ></script>
    @livewireScripts
</head>
<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu') 

        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')
    @livewireScripts

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('start-private-chat', (data) => {
                console.log('Evento start-private-chat recebido com ID:', data.contactId);
            });

            Livewire.on('open-invite-contact-modal', () => {
                console.log('Evento open-invite-contact-modal recebido');
            });
        });
    </script>



</body>
</html>
