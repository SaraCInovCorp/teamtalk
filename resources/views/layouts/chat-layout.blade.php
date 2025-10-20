<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'TeamTalk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="//unpkg.com/alpinejs" ></script>
    @livewireScripts
</head>
<body class="font-sans antialiased">
    <x-banner />
    
    <!-- Menu de navegação superior padrão -->
        @livewire('navigation-menu')

    <div class="min-h-screen bg-gray-100 flex" x-data="{ sidebarOpen: false }">

        <!-- Sidebar lateral esquerda -->
        <aside class="hidden md:block w-72 bg-white border-r border-gray-200 overflow-y-auto">
            <livewire:chat-rooms />
        </aside>

        <!-- Overlay para mobile -->
        <div
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-25 z-20 md:hidden"
            style="display: none;"
        ></div>

        <!-- Conteúdo principal -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Cabeçalho -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                        <div>
                            {{ $header }}
                        </div>
                        <button @click="sidebarOpen = true" class="md:hidden text-gray-600 hover:text-gray-800 focus:outline-none">
                            ☰ Menu
                        </button>
                    </div>
                </header>
            @endif

            <!-- Conteúdo -->
            <section class="flex-1 overflow-auto p-6 bg-gray-50">
                {{ $slot }}
            </section>
        </main>
    </div>
    @stack('modals')
    
</body>
</html>
