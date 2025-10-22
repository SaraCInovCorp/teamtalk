<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\User;

class ChatRooms extends Component
{
    public $roomId;

    public function selectRoom($id)
    {
        $this->roomId = $id;
       
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
        ])->layout('layouts.chat-layout');
    }

}
