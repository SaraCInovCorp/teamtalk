<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configurações da Sala') }}
        </h2>
    </x-slot>

    <div class="p-8 max-w-xl">
        <form wire:submit.prevent="save">
            <div class="mb-4">
                <x-label for="room.name" value="{{ __('Nome') }}" />
                <x-input type="text" wire:model.defer="room.name" id="room.name" />
                <x-input-error for="room.name" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-label for="room.description" value="{{ __('Descrição') }}" />
                <textarea wire:model.defer="room.description" id="room.description" class="textarea textarea-bordered w-full" rows="4"></textarea>
                <x-input-error for="room.description" class="mt-2" />
            </div>

            <div class="mb-4 space-y-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.defer="room.allow_attachment" id="allow_attachment" class="checkbox" />
                    <span class="ml-2">{{ __('Permitir anexos') }}</span>
                </label>

                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.defer="room.allow_edit_description" id="allow_edit_description" class="checkbox" />
                    <span class="ml-2">{{ __('Permitir editar descrição') }}</span>
                </label>

                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model.defer="room.allow_send_messages" id="allow_send_messages" class="checkbox" />
                    <span class="ml-2">{{ __('Permitir enviar mensagens') }}</span>
                </label>
            </div>

            <div class="mb-4 max-w-xs">
                <x-label for="room.message_delete_days" value="{{ __('Dias para apagar mensagens (0 para nunca)') }}" />
                <input type="number" wire:model.defer="room.message_delete_days" id="room.message_delete_days" min="0" class="input input-bordered w-full" />
                <x-input-error for="room.message_delete_days" class="mt-2" />
            </div>

            <div class="mb-4">
                <label class="block font-medium">{{ __('Membros') }}</label>
                <ul>
                    @foreach ($room->users as $member)
                        <li class="flex justify-between items-center">
                            <span>{{ $member->name }}</span>
                            @can('remove', $room)
                                <x-button type="button" wire:click="removeMember({{ $member->id }})" class="text-teamtalk-orange text-xs">Remover</x-button>
                            @endcan
                        </li>
                    @endforeach
                </ul>
                {{-- Botão para adicionar membros via modal/opção pode ser adicionado aqui --}}
            </div>

            <x-button type="submit">{{ __('Salvar') }}</x-button>
        </form>
    </div>
</div>
