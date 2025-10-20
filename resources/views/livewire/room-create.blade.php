<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Nova Sala') }}
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto p-4">

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <x-label for="name" value="{{ __('Nome da Sala') }}" />
                <x-input type="text" wire:model.defer="name" id="name" />
                <x-input-error for="name" class="text-red-600 mt-1" />
            </div>

            <div>
                <x-label for="avatar" value="{{ __('Avatar') }}" />
                <x-input type="file" wire:model="avatar" id="avatar" accept="image/*" />
                <x-input-error for="avatar" class="text-red-600 mt-1" />
                @if ($avatar)
                    <img src="{{ $avatar->temporaryUrl() }}" alt="Avatar Preview" class="mt-2 w-24 h-24 rounded-full object-cover" />
                @endif
            </div>

            <div>
                <x-label for="description" value="{{ __('Descrição') }}" />
                <x-text-area id="description" rows="4" wire:model.defer="description">
                    {{ $description }}
                </x-text-area>
                <x-input-error for="description" class="text-red-600 mt-1" />

            </div>

            <div class="flex items-center space-x-4">
                <x-checkbox wire:model.defer="is_private" id="is_private" >
                    Sala Privada
                </x-checkbox>

                <x-checkbox wire:model.defer="allow_attachment" id="allow_attachment" >
                    Permitir anexos
                </x-checkbox>

            </div>

            <div class="flex items-center space-x-4">
                <x-checkbox wire:model.defer="allow_edit_description" id="allow_edit_description">
                    Permitir editar descrição
                </x-checkbox>
                <x-checkbox wire:model.defer="allow_send_messages" id="allow_send_messages" >
                    Permitir enviar mensagens
                </x-checkbox>
            </div>

            <div>
                <x-label for="message_delete_days" value="{{ __('Dias para apagar mensagens (0 para nunca)') }}" />
                <x-input type="number" wire:model.defer="message_delete_days" id="message_delete_days" min="0" />
                <x-input-error for="message_delete_days" class="text-red-600 mt-1" />
            </div>

            <x-button type="submit">Criar Sala</x-button>
        </form>
    </div>
</div>
