<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InviteContactMail;


class RoomSettings extends Component
{

    use WithFileUploads;

    public $room;
    public $roomId;
    public $name;
    public $description;
    public $avatar; 
    public $is_private;
    public $allow_attachment;
    public $allow_edit_description;
    public $allow_send_messages;
    public $message_delete_days;
    public $users;
    public $newMemberEmail;
    public $selectedMembers = [];
    public $acceptedContacts = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'allow_attachment' => 'boolean',
        'allow_edit_description' => 'boolean',
        'allow_send_messages' => 'boolean',
        'message_delete_days' => 'nullable|integer|min:0',
        'avatar' => 'nullable|image|max:1024',
    ];

    public function mount(Room $room)
    {
        $this->room = $room;

        $user = Auth::user(); 

        $this->acceptedContacts = Contact::where('status', 'accepted')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                ->orWhere('contact_id', $user->id);
            })
            ->with(['user', 'contactUser'])
            ->get();

        $this->roomId = $room->id;
        $this->name = $room->name;
        $this->description = $room->description;
        $this->allow_attachment = $room->allow_attachment;
        $this->allow_edit_description = $room->allow_edit_description;
        $this->allow_send_messages = $room->allow_send_messages;
        $this->message_delete_days = $room->message_delete_days;

        $this->room->load('users');
        $this->users = $this->room->users;

    }

    public function addSelectedMembers()
    {
        $this->checkIfBlocked();

        $user = Auth::user();

        $isAdminGlobal = $user->isAdmin();
        $isAdminRoom = $this->room->users()
                        ->where('user_id', $user->id)
                        ->wherePivot('role_in_room', 'admin')
                        ->exists();

        if (!($isAdminGlobal || $isAdminRoom)) {
            session()->flash('error', 'Apenas admins podem adicionar membros.');
            return;
        }

        $alreadyMembers = $this->room->users->pluck('id')->toArray();

        $newMembers = array_diff($this->selectedMembers, $alreadyMembers);

        if (empty($newMembers)) {
            session()->flash('message', 'Nenhum novo membro selecionado.');
            return;
        }

        foreach ($newMembers as $userId) {
            $this->room->users()->attach($userId, ['joined_at' => now()]);
        }

        $this->room->load('users');
        $this->users = $this->room->users;
        $this->selectedMembers = [];

        session()->flash('message', 'Membros adicionados com sucesso!');
    }

    public function blockMember($userId)
    {
        $this->checkIfBlocked();

        $this->room->users()->updateExistingPivot($userId, ['blocked' => true]);
        $this->room->load('users');
        $this->users = $this->room->users;
        session()->flash('message', 'Membro bloqueado com sucesso!');
    }

    public function unblockMember($userId)
    {
        $this->checkIfBlocked();

        $this->room->users()->updateExistingPivot($userId, ['blocked' => false]);
        $this->room->load('users');
        $this->users = $this->room->users;
        session()->flash('message', 'Membro desbloqueado com sucesso!');
    }

    public function removeMember($userId)
    {
        $this->checkIfBlocked();

        $this->room->users()->detach($userId);
        $this->room->load('users');
        $this->users = $this->room->users;
        session()->flash('message', 'Membro removido com sucesso!');
    }

    public function toggleAdmin($userId)
    {
        $this->checkIfBlocked();

        $currentRole = $this->room->users()->where('user_id', $userId)->first()->pivot->role_in_room ?? 'user';

        $newRole = $currentRole === 'admin' ? 'user' : 'admin';

        $this->room->users()->updateExistingPivot($userId, ['role_in_room' => $newRole]);

        $this->room->load('users');
        $this->users = $this->room->users;

        session()->flash('message', 'Permissões do usuário atualizadas com sucesso!');
    }


    public function addMember()
    {
        $this->checkIfBlocked();

        $user = Auth::user();

        $isAdminGlobal = $user->isAdmin();
        $isAdminRoom = $this->room->users()
                        ->where('user_id', $user->id)
                        ->wherePivot('role_in_room', 'admin')
                        ->exists();

        if (!($isAdminGlobal || $isAdminRoom)) {
            session()->flash('error', 'Apenas admins podem adicionar membros.');
            return;
        }

        $this->validate([
            'newMemberEmail' => 'required|email|exists:users,email', 
        ]);

        $userToAdd = User::where('email', $this->newMemberEmail)->first();

        if (!$userToAdd) {
            session()->flash('message', 'Usuário não encontrado.');
            return;
        }

        if ($this->room->users()->where('user_id', $userToAdd->id)->exists()) {
            session()->flash('message', 'Usuário já é membro da sala.');
            return;
        }

        $contactExists = Contact::where(function ($query) use ($userToAdd) {
            $query->where('user_id', Auth::id())
                ->where('contact_id', $userToAdd->id);
        })->orWhere(function ($query) use ($userToAdd) {
            $query->where('user_id', $userToAdd->id)
                ->where('contact_id', Auth::id());
        })->where('status', 'accepted')->exists();

        if (!$contactExists) {
            $contact = Contact::create([
                'user_id' => Auth::id(),
                'contact_id' => $userToAdd->id,
                'status' => 'pending', 
                'token' => Str::random(64),
            ]);

            Mail::to($this->newMemberEmail)->send(new InviteContactMail($contact->token));
        }

        $this->room->users()->attach($userToAdd->id, ['joined_at' => now()]);

        $this->reset('newMemberEmail');

        $this->room->load('users');
        $this->users = $this->room->users;

        session()->flash('message', 'Membro adicionado com sucesso e convite enviado se necessário!');
    }


    public function deleteRoom()
    {
        $this->authorize('delete', $this->room);

        $this->room->is_active = false;
        $this->room->save();

        session()->flash('message', 'Sala removida com sucesso!');
        
        return redirect()->route('chat.rooms'); 
    }


    public function save()
    {
        $this->checkIfBlocked();
        $this->validate();

        $room = Room::findOrFail($this->roomId);

        $room->name = $this->name;
        $room->description = $this->description;
        $room->allow_attachment = $this->allow_attachment;
        $room->allow_edit_description = $this->allow_edit_description;
        $room->allow_send_messages = $this->allow_send_messages;
        $room->message_delete_days = $this->message_delete_days;

        if ($this->avatar) {
            $avatarPath = $this->avatar->store('avatars', 'public');
            $room->avatar = $avatarPath;

            $room->save();
        }

        session()->flash('message', 'Configurações salvas com sucesso!');

        $this->room = $room->fresh();
        $this->users = $this->room->users;
    }

    private function checkIfBlocked()
    {
        $pivot = $this->room->users()->where('user_id', Auth::id())->first()?->pivot;
        if ($pivot && $pivot->blocked) {
            abort(403, 'Você está bloqueado nesta sala.');
        }
    }

    public function render()
    {        
        $this->room->load('users');
        return view('livewire.room-settings', [
            'room' => $this->room,
            'users' => $this->users,
            'acceptedContacts' => $this->acceptedContacts,
        ])->layout('layouts.chat-layout');
    }

}

