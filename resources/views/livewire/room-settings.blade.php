<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configurações da Sala') }}
        </h2>
    </x-slot>
    <div class="p-8 max-w-xl">
        <h2 class="text-xl font-semibold mb-6">Configurações da Sala</h2>
        <form wire:submit.prevent="save">
            <div class="mb-4">
                <label class="block text-sm font-medium">Nome</label>
                <input type="text" wire:model.defer="room.name" class="input input-bordered w-full">
                <x-input-error for="room.name" class="mt-2" />
            </div>
            {{-- Adicione outros campos, como opções de permissão, anexos, emojis, etc --}}
            <div class="mb-4">
                <label class="block font-medium">Membros</label>
                <ul>
                    @foreach ($room->users as $member)
                        <li class="flex justify-between items-center">
                            <span>{{ $member->name }}</span>
                            {{-- Botão remover se for admin --}}
                            @can('remove', $room)
                                <button type="button" wire:click="removeMember({{ $member->id }})" class="text-red-600 text-xs">Remover</button>
                            @endcan
                        </li>
                    @endforeach
                </ul>
                {{-- Botão para adicionar membros via modal/opção --}}
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>
