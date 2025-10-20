<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;

class AdminPanel extends Component
{
    public function render()
    {
        $rooms = Room::with('users')->get();

        return view('livewire.admin-panel', compact('rooms'));
    }
}
