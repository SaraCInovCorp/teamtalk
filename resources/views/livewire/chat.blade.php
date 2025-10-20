<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Salas') }}
        </h2>
    </x-slot>
    <div class="flex h-full">

        <aside class="w-64 p-4 border-r">
            <div class="mb-4">
                <x-button wire:click="$toggle('showCreateRoomForm')">
                    Criar Sala
                </x-button>
            </div>

            @if($showCreateRoomForm)
                <form wire:submit.prevent="createRoom" class="mb-4">
                    <x-input type="text" wire:model.defer="newRoomName" placeholder="Nome da sala" class="input input-bordered w-full" required/>
                    <x-button type="submit" >Criar</x-button>
                    <x-button type="button" wire:click="$set('showCreateRoomForm', false)">Cancelar</x-button>
                </form>
            @endif

            <h2>Salas</h2>
            <ul>
                @foreach($rooms as $room)
                    <li wire:click="$set('roomId', {{ $room->id }})"
                        class="cursor-pointer p-2 hover:bg-gray-200 {{ $room->id === $roomId ? 'bg-gray-300' : '' }}">
                        {{ $room->name }}
                    </li>
                @endforeach
            </ul>

            <h2>Membros</h2>
            <ul>
                @foreach($members as $member)
                    <li>{{ $member->name }}</li>
                @endforeach
            </ul>

            <h2>Online</h2>
            <ul>
                @foreach($onlineUsers as $user)
                    <li>{{ $user->name }}</li>
                @endforeach
            </ul>
        </aside>

        <main class="flex-1 p-4">
            @livewire('chat-messages', ['roomId' => $roomId])
        </main>

    </div>
</div>
