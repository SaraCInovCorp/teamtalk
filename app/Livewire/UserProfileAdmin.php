<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserProfileAdmin extends Component
{
    public $user;
    public $confirmingUserDelete = false;

    public function mount($id)
    {
        $this->user = User::with(['rooms'])->findOrFail($id);
    }

    public function confirmUserDelete()
    {
        $this->confirmingUserDelete = true;
    }

    public function deleteUser()
    {
        $this->user->delete();

        session()->flash('message', 'Usuário removido com sucesso!');
        return redirect()->route('chat.admin');
    }

    public function goBack()
    {
        return redirect()->route('chat.admin');
    }


    public function render()
    {
        return view('livewire.user-profile-admin')
            ->layout('layouts.app');
    }
}
