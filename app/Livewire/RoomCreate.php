<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class RoomCreate extends Component
{
    use WithFileUploads;

    public $name;
    public $avatar;
    public $is_private = false;
    public $description;
    public $allow_attachment = true;
    public $allow_edit_description = true;
    public $allow_send_messages = true;
    public $message_delete_days = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'avatar' => 'nullable|image|max:1024', // 1MB máximo
        'description' => 'nullable|string',
        'is_private' => 'boolean',
        'allow_attachment' => 'boolean',
        'allow_edit_description' => 'boolean',
        'allow_send_messages' => 'boolean',
        'message_delete_days' => 'nullable|integer|min:0',
    ];

    public function save()
    {
        $this->validate();

        $avatarPath = null;
        if ($this->avatar) {
            $avatarPath = $this->avatar->store('avatars', 'public');
        }

        $room = Room::create([
            'name' => $this->name,
            'avatar' => $avatarPath,
            'is_private' => $this->is_private,
            'created_by' => Auth::id(),
            'description' => $this->description,
            'allow_attachment' => $this->allow_attachment,
            'allow_edit_description' => $this->allow_edit_description,
            'allow_send_messages' => $this->allow_send_messages,
            'message_delete_days' => $this->message_delete_days,
        ]);

        session()->flash('message', 'Sala criada com sucesso!');

        return redirect()->route('chat.room.settings', $room->id);
    }

    public function render()
    {
        return view('livewire.room-create');
    }
}
