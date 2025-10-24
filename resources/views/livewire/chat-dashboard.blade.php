<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bem-vindo ao TeamTalk') }}
        </h2>
    </x-slot>

    <div class="p-8">
        <div class="grid md:grid-cols-3 gap-6">
            <a href="{{ route('chat.rooms') }}" class="bg-blue-500 text-white p-6 rounded-lg shadow hover:bg-blue-600 flex flex-col items-center">
                <span class="text-4xl mb-3">💬</span>
                <span>Salas</span>
            </a>
            <a href="{{ route('chat.contacts') }}" class="bg-green-500 text-white p-6 rounded-lg shadow hover:bg-green-600 flex flex-col items-center">
                <span class="text-4xl mb-3">👥</span>
                <span>Contatos</span>
            </a>
            <a href="{{ route('chat.room.create') }}" class="bg-purple-500 text-white p-6 rounded-lg shadow hover:bg-purple-600 flex flex-col items-center">
                <span class="text-4xl mb-3">➕</span>
                <span>Criar Sala</span>
            </a>
        </div>
        @can('admin')
            <div class="mt-8">
                <a href="{{ route('chat.admin') }}" class="text-indigo-500 hover:text-indigo-700 underline">Administração</a>
            </div>
        @endcan
    </div>
</div>
