<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use Livewire\WithFileUploads;


class ChatMessages extends Component
{
    use WithFileUploads;

    public $messageText = '';
    public $attachment = null;
    public $roomId = null;
    public $recipientId = null; 

    protected $listeners = [
        'addEmoji' => 'addEmojiToMessageText',
        'syncInput' => 'syncInputValue',
        'echo:chat.room.*,MessageSent' => 'handleNewMessage',
        'echo-private:chat.user.*,MessageSent' => 'handleNewMessage',
        'refreshMessages' => '$refresh',
    ];

    protected $rules = [
        'messageText' => 'required_without:attachment|string|max:1000',
        'attachment' => 'nullable|file|max:10240',
    ];

    public function syncInputValue($value)
    {
        $this->messageText = $value;
    }

    public function addEmojiToMessageText($emoji)
    {
        $this->messageText .= $emoji;
    }

    public function sendMessage()
    {
        $this->validate();

        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('attachments', 'public');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'room_id' => $this->roomId,
            'recipient_id' => $this->recipientId,
            'content' => $this->messageText,
            'attachment' => $attachmentPath,
        ]);

        broadcast(new MessageSent($message))->toOthers();
        \Log::info('Mensagem criada: ', ['message_id' => $message->id]);

        $this->messageText = '';
        $this->attachment = null;  
    }

    public function handleNewMessage($payload)
    {
        $this->emitSelf('$refresh');
    }

    public function getMessagesProperty()
    {
        $pivot = Room::find($this->roomId)
            ->users()
            ->where('user_id', Auth::id())
            ->first()
            ?->pivot;

        if ($pivot && $pivot->blocked) {
            return collect(); 
        }

        return Message::with('sender')
            ->where('room_id', $this->roomId)
            ->latest()
            ->get()
            ->reverse();
    }

   public function mount($room = null, $recipientId = null)
    {
        $this->roomId = $room;

        if (!$this->roomId && !$this->recipientId) {
            $lastRoom = Auth::user()->rooms()->latest()->first();
            $this->roomId = $lastRoom?->id;
        }
    }



    public function updatedRoomId($value)
    {
        $this->reset('messageText', 'attachment');
        $this->emitSelf('$refresh'); 
    }

    public function leaveRoom()
    {
        $user = Auth::user();
        $room = Room::find($this->roomId);

        if ($room && $user) {
            
            $room->users()->detach($user->id);
            $this->roomId = null;
            $this->dispatch('refreshRooms');
            session()->flash('message', 'Você saiu da sala com sucesso.');
        }

        return redirect()->route('chat.rooms');
    }

    public function render()
    {
        \Log::info('RoomId:', ['roomId' => $this->roomId]);

        $room = Room::find($this->roomId); 

        return view('livewire.chat-messages', [
            'messages' => $this->messages,
            'room' => $room, 
        ])->layout('layouts.chat-layout');
    }

}
