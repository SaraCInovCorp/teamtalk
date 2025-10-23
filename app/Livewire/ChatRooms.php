<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\User;
use App\Models\Message;
use App\Models\HiddenPrivateChat;

class ChatRooms extends Component
{
    public $roomId;
    public $recentContacts;
    public int $recentContactsVersion = 0;


    protected $listeners = [
        'recentContactsUpdated' => 'loadRecentContacts'
    ];


    public function selectRoom($id)
    {
        $this->roomId = $id;
       
    }

    public function getRecentPrivateConversationsProperty()
    {
        $userId = auth()->id();

        $hidden = HiddenPrivateChat::where('user_id', $userId)->pluck('contact_id');

        $contactIds = Message::where(function($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('recipient_id', $userId);
            })
            ->whereNotNull('recipient_id')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($message) => $message->sender_id === $userId ? $message->recipient_id : $message->sender_id)
            ->unique()
            ->filter(fn($id) => !$hidden->contains($id))
            ->values();

        return User::whereIn('id', $contactIds)->get();
    }

    public function mount()
    {
        $this->loadRecentContacts();
    }

    public function loadRecentContacts()
    {
        $userId = auth()->id();
        $hidden = HiddenPrivateChat::where('user_id', $userId)->pluck('contact_id');

        $contactIds = Message::selectRaw('CASE WHEN sender_id = ? THEN recipient_id ELSE sender_id END as contact_id', [$userId])
            ->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('recipient_id', $userId);
            })
            ->whereNotNull('recipient_id')
            ->whereNotIn('contact_id', $hidden->toArray())
            ->groupBy('contact_id')
            ->orderByRaw('MAX(created_at) DESC')
            ->pluck('contact_id');

        $users = User::whereIn('id', $contactIds)->get();

        $ordered = $contactIds->map(function ($id) use ($users) {
            return $users->firstWhere('id', $id);
        });

        $this->recentContacts = $ordered->filter()->values();
    }

    public function updatedRecentContactsVersion($value)
    {
        $this->loadRecentContacts();
    }

    public function hideRecentContact($contactId)
    {
        HiddenPrivateChat::updateOrCreate([
            'user_id' => auth()->id(),
            'contact_id' => $contactId,
        ]);
        $this->loadRecentContacts();
    }

    public function openChat($contactId)
    {
        $this->selectedContact = User::find($contactId);
        $this->loadRecentContacts(); 
    }


    public function render()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $rooms = Room::where(function($query) use ($user) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->orWhere('created_by', $user->id);
            })->with('users')->get();
        } else {
            $rooms = Room::where(function($query) use ($user) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->orWhere('created_by', $user->id);
            })->where('is_active', true)
            ->with('users')
            ->get();
        }

        return view('livewire.chat-rooms', [
            'rooms' => $rooms,
        ])->layout('layouts.chat-layout', [
            'roomId' => $this->roomId,
            'recipient' => $this->selectedContact ?? null,
        ]);
    }

}
