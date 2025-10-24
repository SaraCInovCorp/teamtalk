<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\Room;
use App\Models\HiddenPrivateChat;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use App\Events\MessageSent;

class ChatMessages extends Component
{
    use WithFileUploads;

    public $messageText = '';
    public $attachment = null;
    public $roomId = null;
    public $recipientId = null;
    public int $recentContactsVersion = 0;

    protected $messages = [];

    protected $rules = [
        'messageText' => 'nullable|string|max:1000',
        'attachment' => 'nullable|file|max:10240',
    ];

    protected $listeners = [
        'addEmoji' => 'addEmojiToMessageText',
        'syncInput' => 'syncInputValue',
        'refreshMessages' => 'refreshMessagesList',
    ];

    public function mount($room = null, $recipient = null)
    {
        $this->messages = [];
        $this->roomId = $room;
        $this->recipientId = $recipient;
        $this->loadMessages();
    }

    public function updatedRoomId($value)
    {
        $this->messageText = '';
        $this->attachment = null;
        $this->loadMessages();
    }

    public function updatedAttachment()
    {
        $this->loadMessages();
    }


    protected function loadMessages()
    {
        if ($this->roomId) {
            $room = Room::find($this->roomId);

            if (!$room) {
                $this->messages = [];
                return;
            }

            $this->messages = Message::with('sender')
                ->where('room_id', $this->roomId)
                ->where('is_active', true)
                ->latest()
                ->take(100)
                ->get()
                ->reverse()
                ->values(); 

        } elseif ($this->recipientId) {
            $this->messages = Message::with('sender')
                ->where(function ($q) {
                    $q->where([
                        ['sender_id', Auth::id()],
                        ['recipient_id', $this->recipientId],
                    ])->orWhere(function ($subQ) {
                        $subQ->where('sender_id', $this->recipientId)
                            ->where('recipient_id', Auth::id());
                    });
                })
                ->where('is_active', true)
                ->latest()
                ->take(100)
                ->get()
                ->reverse()
                ->values();

        } else {
            $this->messages = [];
        }
    }

    public function refreshMessagesList()
    {
        $this->loadMessages();
    }

    public function sendMessage()
    {
        $this->validate();

        if (empty($this->messageText) && !$this->attachment) {
            return;
        }

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

        if ($this->recipientId) {
            HiddenPrivateChat::where('user_id', Auth::id())
                ->where('contact_id', $this->recipientId)
                ->delete();
        }

        broadcast(new MessageSent($message))->toOthers();
        $this->dispatch('recentContactsUpdated');

        $this->messageText = '';
        $this->attachment = null;

        $this->loadMessages();
        $this->dispatch('clearMessageInputs');
        
    }

    public function addEmojiToMessageText($emoji)
    {
        $this->messageText .= $emoji;
    }

    public function syncInputValue($value)
    {
        $this->messageText = $value;
    }

    public function render()
    {
        $room = null;

        if ($this->roomId) {
            $roomModel = Room::find($this->roomId);
            if ($roomModel) {
                $room = $roomModel;
            } else {
                $this->roomId = null;
            }
        }

        return view('livewire.chat-messages', [
            'messages' => $this->messages, 
            'room' => $room,
        ])->layout('layouts.chat-layout');
    }

}
