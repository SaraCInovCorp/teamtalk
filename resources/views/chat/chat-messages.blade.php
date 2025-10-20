<x-chat-layout>
    @livewire('chat-messages', [
        'roomId' => $roomId ?? null,
        'recipientId' => $recipientId ?? null,
    ])
</x-chat-layout>