<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatRooms extends Component
{
    public $roomId;

    public function selectRoom($id)
    {
        $this->roomId = $id;
       
    }

    

    public function render()
    {
        return view('livewire.chat-rooms', [
            'rooms' => Auth::user()->rooms()->with('users')->get(),
        ])->layout('layouts.chat-layout');
    }
}
