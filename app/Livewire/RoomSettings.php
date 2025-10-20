<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;

class RoomSettings extends Component
{
    public Room $room;

    protected $rules = [
        'room.name' => 'required|string|max:255',
        // outras regras para campos da sala
    ];

    public function mount($roomId)
    {
        $this->room = Room::with('users')->findOrFail($roomId);
    }


    public function save()
    {
        $this->validate();
        $this->room->save();

        session()->flash('message', 'Configurações salvas com sucesso!');
    }

    public function render()
    {
        return view('livewire.room-settings');
    }
}

