<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administração de Salas
        </h2>
    </x-slot>

    <div class="p-8">
        <div class="mb-4 flex items-center gap-2">
            <x-input type="text" wire:model.defer="search" placeholder="Buscar salas ou usuários..." class="flex-grow" />
            <x-button wire:click="performSearch" class="hover:bg-teamtalk-blue">Buscar</x-button>
            <x-secondary-button wire:click="resetSearch" class="hover:bg-teamtalk-orange">Limpar</x-secondary-button>

        </div>

        <ul class="divide-y">
            @foreach ($rooms as $room)
                <li class="py-3">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            @if($room->avatar)
                                <img src="{{ asset('storage/' . $room->avatar) }}" class="w-7 h-7 rounded-full object-cover border" />
                            @endif
                            <span class="font-semibold">{{ $room->name }}</span>
                            <span class="ml-2 text-gray-400 text-xs">
                                ({{ $room->users->count() }} membros)
                            </span>
                            <span class="ml-4 text-gray-500 text-xs">
                                Criador: {{ $room->creator?->name ?? 'Desconhecido' }}
                            </span>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('chat.room.settings', $room->id) }}"
   class="inline-flex items-center px-4 py-2 border-gray-300 rounded-md font-bold text-xs text-teamtalk-gray uppercase tracking-widest shadow-sm hover:bg-teamtalk-blue-claro hover:text-white transition ease-in-out duration-150">
   Configurações
</a>

                            <x-secondary-button wire:click="confirmDelete({{ $room->id }})" class="hover:bg-teamtalk-orange hover:text-white">Remover</x-secondary-button>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-10">
    <h3 class="mb-4 text-lg font-semibold text-gray-800">Usuários</h3>

    <div class="flex flex-wrap gap-2 mb-4">
            @foreach(range('A', 'Z') as $l)
                <button 
                    wire:click="$set('letter', '{{ $l }}')" 
                    class="w-10 h-10 flex items-center justify-center border rounded {{ $letter === $l ? 'bg-teamtalk-blue-claro text-white' : 'bg-teamtalk-gray text-white hover:bg-teamtalk-blue hover:text-white' }}">
                    {{ $l }}
                </button>
            @endforeach
            <x-secondary-button wire:click="$set('letter', null)">Todos</x-secondary-button>
        </div>

    <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
        <ul class="divide-y divide-gray-200">
            @forelse ($users as $user)
                <li class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                             alt="{{ $user->name }}" 
                             class="w-10 h-10 rounded-full object-cover border border-gray-300">
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <x-secondary-button wire:click="viewUserProfile({{ $user->id }})" class="text-xs hover:bg-teamtalk-blue-claro hover:text-white">
                            Ver Perfil
                        </x-secondary-button>


                       <x-secondary-button wire:click="confirmUserDelete({{ $user->id }})" class="text-xs hover:bg-teamtalk-orange hover:text-white">
                                    Remover
                                </x-secondary-button>

                    </div>
                </li>
            @empty
                <li class="p-4 text-gray-500 text-sm">Nenhum usuário encontrado.</li>
            @endforelse
        </ul>

        <div class="p-3 border-t bg-gray-50">
            {{ $users->links() }}
        </div>
    </div>
</div>

        


        <div class="mt-8">
            <h3 class="mb-2 font-bold">Últimas Ações do Sistema</h3>
            @if($logs->isEmpty())
                <span class="text-gray-500">Nenhum log recente.</span>
            @else
                <ul class="text-sm text-gray-700">
                    @foreach ($logs as $log)
                        <li>
                            {{ $log->description }} —
                            <span class="text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    {{-- Modal de exclusão de usuário --}}
    @if($confirmingUserDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirmar exclusão</h2>
                <p class="text-sm text-gray-600 mb-6">Tens certeza que desejas excluir este usuário?</p>
                <div class="flex justify-end gap-2">
                    <x-secondary-button wire:click="$set('confirmingUserDelete', false)">Cancelar</x-secondary-button>
                    <x-button wire:click="deleteUser" class="bg-red-600 hover:bg-red-700 text-white">Confirmar</x-button>
                </div>
            </div>
        </div>
    @endif
</div>



