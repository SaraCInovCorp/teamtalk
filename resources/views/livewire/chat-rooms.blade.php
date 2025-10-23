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
                    <a href="{{ route('chat.room.settings', $room->id) }}" 
                        class="text-gray-600 hover:text-gray-900" 
                        title="Configurações" 
                        aria-label="Configurações da sala">
                        <x-icon size="w-6 h-6" color="currentColor" class="inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </x-icon>
                    </a>

                </div>
            </li>
        @endforeach
    </ul>

    <div>
        <h3 class="font-semibold text-lg mb-4">Contatos Recentes</h3>
        @if ($recentContacts->isEmpty())
            <p class="text-gray-500">Nenhuma conversa recente.</p>
        @else
            <ul>
                @foreach ($recentContacts as $contact)
                    <li class="flex justify-between items-center py-2">
                        <a href="{{ route('chat.messages.private', ['recipient' => $contact->id]) }}" class="text-teamtalk-blue hover:text-teamtalk-blue-claro">
                            {{ $contact->name }}
                        </a>

                        <x-icon wire:click.prevent="hideRecentContact({{ $contact->id }})" class="text-teamtalk-orange ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </x-icon>

                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
