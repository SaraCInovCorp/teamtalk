<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meus Contatos') }}
        </h2>
    </x-slot>

    @if(session()->has('message'))
        <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-900 shadow">
            {{ session('message') }}
        </div>
    @endif

    <div class="p-8 space-y-6">
        @if ($showInviteForm)
            <!-- Formulário de convite -->
            <div class="p-8 max-w-md mx-auto border rounded shadow-sm">
                <h2 class="text-xl font-semibold mb-4">Convidar contato</h2>

                <form wire:submit.prevent="inviteContact">
                    <x-input 
                        type="email" 
                        wire:model="emailToInvite" 
                        class="w-full mb-4"
                        placeholder="Digite o e-mail..."
                    />
                    <x-button type="submit" class="bg-blue-600 text-white w-full">
                        Enviar convite
                    </x-button>
                </form>

                <x-secondary-button wire:click="$set('showInviteForm', false)" class="mt-4 w-full">
                    Voltar
                </x-secondary-button>
            </div>
        @else
            <!-- Filtros -->
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach(range('A', 'Z') as $l)
                    <x-button 
                        wire:click="$set('letter', '{{ $l }}')" 
                        class="w-10 h-10 flex items-center justify-center border rounded {{ $letter === $l ? ' bg-teamtalk-blue-claro text-white' : 'bg-white text-teamtalk-gray hover:text-white' }}">
                        {{ $l }}
                    </x-button>
                @endforeach
                <x-secondary-button wire:click="$set('letter', null)">Todos</x-secondary-button>
            </div>

            <!-- Pesquisa -->
            <form wire:submit.prevent="searchContacts">
                <div class="mb-6">
                    <x-input 
                        type="text" 
                        wire:model="search" 
                        class="input input-bordered w-full" 
                        placeholder="Pesquisar contatos..."
                    />
                </div>
            
            <x-button type="submit" class="mt-2">
                Buscar
            </x-button>
            </form>

            <!-- Convites Recebidos -->
            <div>
                <h3 class="font-semibold text-lg mb-2">Convites Recebidos</h3>
                <ul class="divide-y mb-4">
                    @forelse ($incomingInvites as $invite)
                        @php
                            $name = $invite->user->name ?? $invite->email;
                        @endphp
                        <li class="flex justify-between items-center py-2">
                            <span>{{ $name }}</span>
                            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto justify-end">
                                <x-secondary-button wire:click="declineInvite({{ $invite->id }})" class="hover:bg-teamtalk-orange hover:text-white">
                                    Recusar
                                </x-button>
                                <x-button wire:click="acceptInvite({{ $invite->id }})" class="hover:bg-teamtalk-green-claro hover:text-white">
                                    Aceitar
                                </x-button>
                                
                            </div>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Nenhum convite recebido.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Contatos Aceitos -->
            <div>
                <h3 class="font-semibold text-lg mb-2">Contatos Aceitos</h3>
                <ul class="divide-y mb-4">
                    @forelse ($acceptedContacts as $contact)
                        @php
                            $otherUser = $contact->user_id === Auth::id()
                                ? $contact->contactUser
                                : $contact->user;
                        @endphp
                        <li class="flex justify-between items-center py-2">
                            <span>{{ $otherUser->name ?? 'Sem nome' }}</span>
                            <x-button wire:click="startPrivateChat('{{ $contact->id }}')">
                                Conversar
                            </x-button>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Você ainda não tem contatos aceitos.</li>
                    @endforelse
                </ul>
            </div>

            <!-- Convites Pendentes -->
            <div>
                <h3 class="font-semibold text-lg mb-2">Convites Pendentes</h3>
                <ul class="divide-y mb-2">
                    @forelse ($pendingContacts as $contact)
                        @php
                            $name = $contact->contactUser->name ?? $contact->email;
                            $isDeclined = $contact->status === 'declined';
                        @endphp
                        <li class="flex justify-between items-center py-2">
                            <span>{{ $name }}</span>
                            <div class="flex gap-2 items-center">
                                @if ($isDeclined)
                                    <span class="text-teamtalk-orange text-sm">Recusado</span>
                                    <x-secondary-button wire:click="deleteInvite({{ $contact->id }})" class="hover:bg-teamtalk-orange hover:text-white">
                                        Excluir
                                    </x-secondary-button>
                                @else
                                    <span class="text-teamtalk-gray text-sm">Aguardando resposta</span>
                                    <x-secondary-button wire:click="cancelInvite({{ $contact->id }})" class="hover:bg-teamtalk-orange hover:text-white">
                                        Cancelar
                                    </x-secondary-button>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Nenhum convite pendente.</li>
                    @endforelse
                </ul>
                {{ $pendingContacts->links() }}
            </div>


            <x-button wire:click="$set('showInviteForm', true)" class="mt-4">
                + Convidar novo contato
            </x-button>
        @endif
    </div>
</div>
