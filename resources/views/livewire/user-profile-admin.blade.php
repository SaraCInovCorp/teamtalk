<div class="p-8">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Perfil do Usuário
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white border border-gray-200 rounded-lg shadow p-6">
        {{-- Informações básicas do usuário --}}
        <div class="flex items-center gap-4">
            <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                 alt="{{ $user->name }}"
                 class="w-20 h-20 rounded-full border object-cover">

            <div>
                <h3 class="text-2xl font-semibold text-gray-800">{{ $user->name }}</h3>
                <p class="text-gray-600">{{ $user->email }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    Conta criada em {{ $user->created_at->format('d/m/Y H:i') }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Último login: {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Tipo de usuário: {{ $user->role ?? 'N/A' }}
                </p>
            </div>
        </div>

        {{-- Salas que participa --}}
        @if($user->rooms && $user->rooms->count())
            <div class="mt-6">
                <h4 class="font-semibold text-gray-700 mb-2">Salas que participa</h4>
                <ul class="divide-y divide-gray-200">
                    @foreach ($user->rooms as $room)
                        <li class="py-2 text-gray-700">
                            {{ $room->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Botões de ação --}}
        <div class="mt-6 flex gap-2">
            <x-secondary-button wire:click="goBack" class="hover:bg-teamtalk-blue hover:text-white">
                ← Voltar
            </x-secondary-button>


            <x-secondary-button wire:click="confirmUserDelete" class="bg-red-600 hover:bg-red-700 text-white">
                Remover Usuário
            </x-secondary-button>
        </div>
    </div>

    {{-- Modal de confirmação de exclusão --}}
    @if($confirmingUserDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirmar exclusão</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Tens certeza que desejas excluir este usuário?
                </p>
                <div class="flex justify-end gap-2">
                    <x-secondary-button wire:click="$set('confirmingUserDelete', false)">
                        Cancelar
                    </x-secondary-button>
                    <x-button wire:click="deleteUser" class="bg-red-600 hover:bg-red-700 text-white">
                        Confirmar
                    </x-button>
                </div>
            </div>
        </div>
    @endif
</div>
