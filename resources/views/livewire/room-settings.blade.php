<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configurações da Sala') }}
        </h2>
    </x-slot>

    <div class="p-2 max-w-xl">
        @if (session()->has('message'))
            <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-900 shadow">
                {{ session('message') }}
            </div>
        @endif
        <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-4">
            @if (auth()->user()->can('leave', $room) && !auth()->user()->can('delete', $room))
                <div class="m-8 flex justify-end">
                    <x-danger-button wire:click="leaveRoom" class="hover:bg-teamtalk-orange hover:text-white">
                        Sair da Sala
                    </x-danger-button>
                </div>
            @endif

            @can('delete', $room)
                <div class="m-8 flex justify-end">
                    <x-danger-button wire:click="deleteRoom" class="hover:bg-teamtalk-orange hover:text-white">
                        Excluir a Sala
                    </x-danger-button>
                </div>
            @endcan

            @php
                $canEdit = auth()->user()->can('editInfo', $room);
            @endphp

            <div class="mb-4">
                <x-label for="name" value="{{ __('Nome') }}" />
                <x-input type="text" wire:model.defer="name" id="name" :disabled="!$canEdit" />
                <x-input-error for="name" class="mt-2" />
            </div>

            <div>
                <x-label for="avatar" value="{{ __('Avatar') }}" />
                <x-input type="file" wire:model="avatar" id="avatar" accept="image/*" :disabled="!$canEdit" />
                <x-input-error for="avatar" class="text-red-600 mt-1" />
                @if ($avatar)
                    <img src="{{ $avatar->temporaryUrl() }}" alt="Avatar Preview" class="mt-2 w-24 h-24 rounded-full object-cover" />
                @elseif ($room->avatar)
                    <img src="{{ asset('storage/' . $room->avatar) }}" alt="Avatar Atual" class="mt-2 w-24 h-24 rounded-full object-cover" />
                @endif
            </div>

            <div class="mb-4">
                <x-label for="description" value="{{ __('Descrição') }}" />
                <textarea wire:model.defer="description" id="description" rows="4" class="textarea textarea-bordered w-full" :disabled="!$canEdit"></textarea>
                <x-input-error for="description" class="mt-2" />
            </div>

            <div class="flex items-center space-x-4">
                <x-checkbox wire:model.defer="allow_attachment" id="allow_attachment" :disabled="!$canEdit">Permitir anexos</x-checkbox>
                <x-checkbox wire:model.defer="allow_edit_description" id="allow_edit_description" :disabled="!$canEdit">Permitir editar informações</x-checkbox>
            </div>

            <div class="flex items-center space-x-4">
                <x-checkbox wire:model.defer="allow_send_messages" id="allow_send_messages" :disabled="!$canEdit">Permitir enviar mensagens</x-checkbox>
            </div>

            <div class="mb-4 max-w-xs">
                <x-label for="message_delete_days" value="{{ __('Dias para apagar mensagens (0 para nunca)') }}" />
                <x-input type="number" wire:model.defer="message_delete_days" id="message_delete_days" min="0" class="input input-bordered w-full" :disabled="!$canEdit" />
                <x-input-error for="message_delete_days" class="mt-2" />
            </div>

            <div class="mb-4">
                <label class="block font-medium">{{ __('Membros') }}</label>
                <ul>
                    @foreach ($room->users as $member)
                        @php
                            $isBlocked = $member->pivot->blocked ?? false;
                            $isAdmin = ($member->pivot->role_in_room === 'admin');
                        @endphp

                        <li class="flex justify-between items-center py-1">
                            <span class="{{ $isBlocked ? 'text-red-600' : '' }}">
                                {{ $member->name }}
                                @if ($isAdmin)
                                    <span class="ml-2 text-green-600 font-semibold text-xs">(Admin da Sala)</span>
                                @endif
                            </span>

                            <div class="flex space-x-2">
                                @can('invite', $room)
                                    <!-- Botão Remover -->
                                    <a href="#" wire:click.prevent="removeMember({{ $member->id }})" title="Remover Membro" aria-label="Remover membro" class="inline-flex">
                                        <x-icon size="w-6 h-6" color="#e67c1c" class="cursor-pointer" >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </x-icon>
                                    </a>

                                    <!-- Botão Bloquear -->
                                    <a href="#" wire:click.prevent="blockMember({{ $member->id }})" title="Bloquear Membro" aria-label="Bloquear membro" class="inline-flex">
                                        <x-icon size="w-6 h-6" color="#e74c3c" class="cursor-pointer" >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        </x-icon>
                                    </a>

                                    <!-- Botão alternar admin/usuário -->
                                    @if ($member->pivot->role_in_room === 'admin')
                                        <a href="#" wire:click.prevent="toggleAdmin({{ $member->id }})" title="Remover Admin" aria-label="Remover administração do membro" class="inline-flex">
                                            <x-icon size="w-6 h-6" color="#27ae60" class="cursor-pointer" >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                                </svg>
                                            </x-icon>
                                        </a>
                                    @else
                                        <a href="#" wire:click.prevent="toggleAdmin({{ $member->id }})" title="Tornar Admin" aria-label="Conceder administração ao membro" class="inline-flex">
                                            <x-icon size="w-6 h-6" color="#27ae60" class="cursor-pointer" >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                                </svg>
                                            </x-icon>
                                        </a>
                                    @endif
                                @endcan
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            @can('invite', $room)
                <div class="mb-4">
                    <label class="block font-medium mb-2">Selecione contatos para adicionar à sala</label>

                    <div class="max-h-48 overflow-auto border rounded p-2">
                        @foreach ($acceptedContacts as $contact)
                            @php
                                $user = $contact->user_id === auth()->id() ? $contact->contactUser : $contact->user;
                            @endphp
                            <x-checkbox
                                :id="'contact-'.$user->id"
                                name="selectedMembers[]"
                                :value="$user->id"
                                wire:model="selectedMembers"
                            >
                                {{ $user->name }}
                            </x-checkbox>
                        @endforeach
                    </div>

                    <x-secondary-button wire:click.prevent="addSelectedMembers" class="mt-2">Adicionar Selecionados</x-secondary-button>
                </div>

                <div class="mb-4 max-w-md">
                    <x-label for="newMemberEmail" value="{{ __('Adicionar membro por email') }}" />
                    <x-input
                        type="email"
                        wire:model="newMemberEmail"
                        id="newMemberEmail"
                        placeholder="Email do usuário"
                        class="w-full"
                    />
                    <x-input-error for="newMemberEmail" class="mt-1" />
                    <x-secondary-button wire:click.prevent="addMember" type="button" class="mt-2">
                        Adicionar Membro
                    </x-secondary-button>
                </div>
            

            <x-button type="submit">{{ __('Salvar') }}</x-button>
            @endcan
        </form>
    </div>
</div>
