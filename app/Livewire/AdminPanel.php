<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Livewire\WithPagination;

class AdminPanel extends Component
{
    use WithPagination;

    public $search = '';
    public $letter = null;
    public $confirmingUserDelete = false;
    public $userToDelete = null;

    protected $queryString = ['search', 'letter']; // mantém busca na URL

    // 🔹 Reset da página ao alterar busca ou letra
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLetter()
    {
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->reset(['search', 'letter']);
        $this->resetPage();
    }

    public function confirmDelete($roomId)
    {
        if ($room = Room::find($roomId)) {
            $room->delete();
            session()->flash('message', 'Sala removida com sucesso!');
        } else {
            session()->flash('error', 'Sala não encontrada.');
        }
    }

    public function confirmUserDelete($userId)
    {
        $this->confirmingUserDelete = true;
        $this->userToDelete = $userId;
    }

    public function deleteUser()
    {
        if ($user = User::find($this->userToDelete)) {
            $user->delete();
            session()->flash('message', 'Usuário removido com sucesso!');
        } else {
            session()->flash('error', 'Usuário não encontrado.');
        }

        $this->reset(['confirmingUserDelete', 'userToDelete']);
    }

    public function viewUserProfile($userId)
    {
        // 🚫 Evita erro "Alpine.navigate is not a function"
        return redirect()->route('user.profile.admin', ['id' => $userId]);
    }

    public function render()
    {
        $term = trim($this->search);
        $l = $this->letter;

        // 🔸 Consulta de salas
        $rooms = Room::withoutGlobalScopes()
            ->with(['users', 'creator'])
            ->when($term, fn($q) => $q->where('name', 'like', "%{$term}%"))
            ->orderByDesc('created_at')
            ->get();

        // 🔸 Consulta de usuários
        $users = User::query()
            ->when($term, fn($q) =>
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
            )
            ->when(!$term && $l, fn($q) =>
                $q->where('name', 'like', "{$l}%")
            )
            ->paginate(20);

        // 🔸 Logs recentes
        $logs = Activity::where('causer_id', auth()->id())
            ->latest()
            ->limit(20)
            ->get();

        return view('livewire.admin-panel', compact('rooms', 'users', 'logs'))
            ->layout('layouts.app');
    }
}
