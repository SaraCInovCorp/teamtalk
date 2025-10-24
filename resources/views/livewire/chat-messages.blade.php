
    <div class="flex flex-col h-[calc(100vh-160px)] overflow-hidden">
        @php
            $pivot = $room ? $room->users()->firstWhere('id', auth()->id())?->pivot : null;
            use Carbon\Carbon;
        @endphp



        @if (!$pivot || !$pivot->blocked)
            <div class="flex-1 overflow-hidden flex flex-col">
                <div id="messages-list"
                    class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 rounded-lg border">
                {{ count($messages) }} mensagens carregadas.
                @forelse($messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs px-4 py-2 rounded-lg 
                                {{ $message->sender_id === auth()->id() ? 'bg-teamtalk-blue-claro text-white' : 'bg-teamtalk-gray text-gray-300'}}">
                            <p class="text-xs mb-1">{{ $message->sender->name }}</p>
                            <p class="font-semibold">{{ $message->content }}</p>
                            @if ($message->attachment)
                                <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank">Ver anexo</a>
                            @endif
                            <span class="text-xs text-yellow-100">{{ $message->created_at->format('H:i') }}</span>

                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400">Nenhuma mensagem nesta conversa.</p>
                @endforelse
            </div>
        
        <div class="border-t bg-white p-2">
        @if(($room && $room->allow_attachment) || $recipientId)
            <div class="flex items-center gap-2 m-4">
                <input type="file" wire:model="attachment" class="text-sm" />
                @error('attachment') <span class="text-red-500">{{ $message }}</span> @enderror

            </div>
        @endif

                <form wire:submit.prevent="sendMessage" enctype="multipart/form-data" class="flex space-x-2">
                    <x-input 
                        type="text" 
                        wire:model="messageText" 
                        placeholder="Digite sua mensagem..."
                        autocomplete="off"
                        class="w-full"
                        id="messageTextid"
                    />
                    <emoji-picker id="picker" style="display:none;"></emoji-picker>
                    <x-secondary-button type="button" onclick="document.getElementById('picker').style.display = 'block'" class="hover:bg-teamtalk-blue-claro">😀</x-secondary-button>
                    <x-secondary-button type="submit" class="hover:bg-teamtalk-blue hover:text-white">Enviar</x-secondary-button>
                </form>
            </div>
        </div>
        @else
            <p>Você está bloqueado e não pode acessar essa sala.</p>
            <x-button wire:click="leaveRoom">Sair da Sala</x-button>
        @endif
        <script type="module" src="https://unpkg.com/emoji-picker-element?module"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const picker = document.getElementById('picker');
                const input = document.getElementById('messageTextid');

                picker.addEventListener('emoji-click', event => {
                    const emoji = event.detail.unicode;

                    input.value = (input.value || '') + emoji;

                    input.dispatchEvent(new Event('input', { bubbles: true }));

                    const livewireEl = document.querySelector('[wire\\:id]');
                    if (livewireEl && livewireEl.__livewire) {
                        try {
                            livewireEl.__livewire.call('addEmojiToMessageText', emoji);
                        } catch (e) {
                            console.debug('Não foi possível chamar __livewire.call:', e);
                        }
                    }

                    picker.style.display = 'none';
                });
                // Scroll automático ao fim das mensagens
                Livewire.hook('message.processed', (message, component) => {
                    const messagesDiv = document.getElementById('messages-list');
                    if (messagesDiv) {
                        setTimeout(() => {
                            messagesDiv.scrollTop = messagesDiv.scrollHeight;
                        }, 100); 
                    }
                });

                Livewire.on('clearMessageInputs', () => {
                    document.getElementById('messageTextid').value = '';
                    const fileInput = document.querySelector('input[type="file"][wire\\:model="attachment"]');
                    if (fileInput) fileInput.value = '';
                });

            });
        </script>
    </div>

