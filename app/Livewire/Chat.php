<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\User;

class Chat extends Component
{
    protected $listeners = ['refreshRooms' => '$refresh'];
    public $roomId;
    public $showCreateRoomForm = false;
    public $newRoomName = '';

    public function refreshRooms()
    {
        // Pode deixar vazio para apenas rerenderizar
    }

    public function createRoom()
    {
        $this->validate(['newRoomName' => 'required|string|max:255']);

        $room = Room::create([
            'name' => $this->newRoomName,
            'created_by' => auth()->id(),
            'is_private' => false,
        ]);

        $room->users()->attach(auth()->id(), ['role_in_room' => 'admin', 'joined_at' => now()]);

        $this->newRoomName = '';
        $this->showCreateRoomForm = false;
        $this->roomId = $room->id;

        //$this->emit('refreshRooms'); 
    }

    public function getUserRooms()
    {
        return Auth::user()->rooms()->with('users')->get();
    }

    public function getMembers()
    {
        if (!$this->roomId) {
            return collect();
        }

        $room = Room::find($this->roomId);
        return $room ? $room->users()->get() : collect();
    }

    public function getOnlineUsers()
    {
        return User::where('last_seen_at', '>=', now()->subMinutes(5))->get();
    }

    public function inviteUser(int $userId)
    {
        $room = Room::findOrFail($this->roomId);

        $this->authorize('invite', $room);

        if (!$room->users()->where('user_id', $userId)->exists()) {
            $room->users()->attach($userId, ['role_in_room' => 'member', 'joined_at' => now()]);
        }
    }

    public function render()
    {
        return view('livewire.chat', [
            'rooms' => $this->getUserRooms(),
            'members' => $this->getMembers(),
            'onlineUsers' => $this->getOnlineUsers(),
        ]);
    }
}
