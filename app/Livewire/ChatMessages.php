<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatMessages extends Component
{
    public $messageText = '';
    public $roomId = null;
    public $recipientId = null; 

    protected $listeners = [
        'echo:chat.room.*,MessageSent' => 'handleNewMessage',
        'echo-private:chat.user.*,MessageSent' => 'handleNewMessage',
        'refreshMessages' => '$refresh',
    ];

    protected $rules = [
        'messageText' => 'required|string|max:1000',
    ];

    public function sendMessage()
    {
        $this->validate();

        $message = Message::create([
            'sender_id' => Auth::id(),
            'room_id' => $this->roomId,
            'recipient_id' => $this->recipientId,
            'content' => $this->messageText,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        $this->messageText = '';  // Limpa o campo de input após enviar
    }

    public function handleNewMessage($payload)
    {
        $this->emit('refreshMessages');
    }

    public function getMessagesProperty()
    {
        return Message::with('sender')
            ->where(function ($query) {
                $query->where('room_id', $this->roomId)
                    ->orWhere(fn($q) => $q->where('sender_id', Auth::id())->where('recipient_id', $this->recipientId));
            })
            ->latest()
            ->get()
            ->reverse(); 
    }

    public function render()
    {
        return view('livewire.chat-messages', [
        'messages' => $this->messages,
    ]);
    }
}
