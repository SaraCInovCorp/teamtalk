<div class="p-4">
    <div class="mb-4">
        <x-button as="a" href="{{ route('chat.room.create') }}" >+ Nova Sala</x-button>
    </div>

    <ul class="divide-y">
        @foreach ($rooms as $room)
            <li class="flex justify-between items-center py-3">
                <div>
                    <a href="{{ route('chat.room', $room->id) }}" class="text-blue-700 hover:underline">
                        {{ $room->name }}
                    </a>
                </div>
                <div>
                    <a href="{{ route('chat.room.settings', $room->id) }}" class="text-gray-600 hover:text-gray-900" title="Configurações">
                        <x-icon name="settings" color="currentColor" size="inline w-6 h-6" />
                    </a>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="mt-8">
        <h3 class="font-semibold mb-2">Contatos Recentes</h3>
        <span class="text-gray-500">Em breve...</span>
    </div>
</div>
