<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minhas Salas') }}
        </h2>
    </x-slot>
    <div class="p-8">
        <div class="mb-4">
            <a href="#" wire:click="$emit('openCreateRoomModal')" class="btn btn-primary">+ Nova Sala</a>
        </div>
        <ul class="divide-y">
            @foreach ($rooms as $room)
                <li class="flex justify-between items-center py-3">
                    <div>
                        <a href="#" wire:click="$emit('enterRoom', {{ $room->id }})" class="text-blue-700 hover:underline">{{ $room->name }}</a>
                    </div>
                    <div>
                        <a href="{{ route('chat.room.settings', $room->id) }}" class="text-gray-600 hover:text-gray-900" title="Configurações">
                            <svg class="inline w-5 h-5" ...> <!-- ícone de engrenagem --></svg>
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-8">
            <h3 class="font-semibold mb-2">Contatos Recentes</h3>
            {{-- Aqui entrará lista de contatos recentes --}}
            <span class="text-gray-500">Em breve...</span>
        </div>
    </div>
</div>
