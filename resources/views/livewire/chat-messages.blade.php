<div class="flex flex-col h-full max-h-full">

    <div class="flex-1 overflow-auto p-4 space-y-3 bg-gray-50 rounded-lg border">
        @forelse($messages as $message)
            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs px-4 py-2 rounded-lg 
                    {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900'}}">
                    <p class="font-semibold mb-1">{{ $message->sender->name }}</p>
                    <p>{{ $message->content }}</p>
                    <span class="text-xs text-gray-500">{{ $message->created_at->format('H:i') }}</span>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400">Nenhuma mensagem nesta conversa.</p>
        @endforelse
    </div>

    <form wire:submit.prevent="sendMessage" class="mt-4 flex space-x-2">
        <input 
            type="text" 
            wire:model.defer="messageText" 
            placeholder="Digite sua mensagem..."
            class="flex-1 input input-bordered input-sm"
            autocomplete="off"
        />
        <button type="submit" class="btn btn-primary btn-sm">Enviar</button>
    </form>
</div>
