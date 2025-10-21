<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\InviteContactMail;

class ChatContacts extends Component
{
    use WithPagination;

    protected $listeners = ['refresh' => '$refresh'];

    public $emailToInvite;
    public $search = '';
    public $letter = null;
    public $showInviteForm = false;

    protected $paginationTheme = 'tailwind';

    public function openInviteForm()
    {
        $this->showInviteForm = true;
    }


    public function showInviteForm()
    {
        $this->showInviteForm = true;
    }

    public function inviteContact()
    {
        $this->validate([
            'emailToInvite' => 'required|email',
        ]);

        $existingUser = User::where('email', $this->emailToInvite)->first();

        $contactData = [
            'user_id' => auth()->id(),
            'status' => 'pending',
            'token' => Str::random(64),
        ];

        if ($existingUser) {
            $contactData['contact_id'] = $existingUser->id;
            $contactData['email'] = null;
        } else {
            $contactData['contact_id'] = null;
            $contactData['email'] = $this->emailToInvite;
            Mail::to($this->emailToInvite)->send(new InviteContactMail($contactData['token']));
        }

        Contact::create($contactData);

        $this->emailToInvite = '';
        $this->showInviteForm = false; 
        session()->flash('message', 'Convite enviado!');
        $this->resetPage();
    }


    public function startPrivateChat($id)
    {
        $this->dispatchBrowserEvent('start-private-chat', ['contactId' => $id]);
    }

    public function acceptInvite($inviteId)
    {
        $invite = Contact::findOrFail($inviteId);
        $invite->update(['status' => 'accepted']);
        session()->flash('message', 'Convite aceito!');
        $this->resetPage();
    }

    public function cancelInvite($contactId)
    {
        $contact = Contact::find($contactId);

        if ($contact && $contact->user_id === auth()->id() && $contact->status === 'pending') {
            $contact->delete();
            session()->flash('message', 'Convite cancelado.');
            $this->resetPage();
            return redirect()->route('chat.contacts');
        }
    }

    public function searchContacts()
    {
        $this->resetPage(); 
    }

    public function declineInvite($inviteId)
    {
        $invite = Contact::findOrFail($inviteId);
        $invite->update(['status' => 'declined']);
        session()->flash('message', 'Convite recusado.');
    }


    public function deleteInvite($contactId)
    {
        $contact = Contact::findOrFail($contactId);
        if ($contact->user_id === auth()->id() && $contact->status === 'declined') {
            $contact->delete();
            session()->flash('message', 'Convite excluído.');
            $this->resetPage();
            return redirect()->route('chat.contacts');
        }
    }

    public function render()
    {
        $user = Auth::user();

        // Contatos aceitos (amizades aprovadas)
        $acceptedContactsQuery = Contact::where('status', 'accepted')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                ->orWhere('contact_id', $user->id);
            })->with(['user', 'contactUser']);

        // Convites recebidos
        $incomingInvitesQuery = Contact::where('contact_id', $user->id)
            ->where('status', 'pending')
            ->with('user');

        // Convites pendentes
        $pendingContactsQuery = Contact::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'declined'])
            ->with('contactUser');

        // Prioriza busca por texto
        if (!empty($this->search)) {
            $term = $this->search;

            $acceptedContactsQuery->where(function ($query) use ($user, $term) {
                $query
                    // Nome do contactUser para contatos que criei
                    ->where(function($sub) use($user, $term){
                        $sub->where('user_id', $user->id)
                            ->whereHas('contactUser', fn($q) =>
                                $q->where('name', 'like', '%' . $term . '%')
                            );
                    })
                    // Nome do user para contatos que recebi
                    ->orWhere(function($sub) use($user, $term){
                        $sub->where('contact_id', $user->id)
                            ->whereHas('user', fn($q) =>
                                $q->where('name', 'like', '%' . $term . '%')
                            );
                    })
                    // Por email se contato não tem usuário vinculado
                    ->orWhere(function($sub) use($user, $term){
                        $sub->where('user_id', $user->id)
                            ->whereNull('contact_id')
                            ->where('email', 'like', '%' . $term . '%');
                    });
            });

            $incomingInvitesQuery->where(function ($query) use ($term) {
                $query
                    ->whereHas('user', fn($q) =>
                        $q->where('name', 'like', '%' . $term . '%')
                    )
                    ->orWhere('email', 'like', '%' . $term . '%');
            });

            $pendingContactsQuery->where(function ($query) use ($term) {
                $query
                    ->whereHas('contactUser', fn($q) =>
                        $q->where('name', 'like', '%' . $term . '%')
                    )
                    ->orWhere('email', 'like', '%' . $term . '%');
            });
        }
        // Se não há busca, usa filtro de letra
        elseif (!empty($this->letter)) {
            $l = $this->letter;

            $acceptedContactsQuery->where(function ($query) use ($user, $l) {
                $query
                    // Nome do contactUser para contatos que criei
                    ->where(function($sub) use($user, $l){
                        $sub->where('user_id', $user->id)
                            ->whereHas('contactUser', fn($q) =>
                                $q->where('name', 'like', $l . '%')
                            );
                    })
                    // Nome do user para contatos que recebi
                    ->orWhere(function($sub) use($user, $l){
                        $sub->where('contact_id', $user->id)
                            ->whereHas('user', fn($q) =>
                                $q->where('name', 'like', $l . '%')
                            );
                    })
                    // Por email se contato não tem usuário vinculado
                    ->orWhere(function($sub) use($user, $l){
                        $sub->where('user_id', $user->id)
                            ->whereNull('contact_id')
                            ->where('email', 'like', $l . '%');
                    });
            });

            $incomingInvitesQuery->where(function ($query) use ($l) {
                $query
                    ->whereHas('user', fn($q) =>
                        $q->where('name', 'like', $l . '%')
                    )
                    ->orWhere('email', 'like', $l . '%');
            });

            $pendingContactsQuery->where(function ($query) use ($l) {
                $query
                    ->whereHas('contactUser', fn($q) =>
                        $q->where('name', 'like', $l . '%')
                    )
                    ->orWhere('email', 'like', $l . '%');
            });
        }

        $acceptedContacts = $acceptedContactsQuery->get();
        $incomingInvites = $incomingInvitesQuery->get();
        $pendingContacts = $pendingContactsQuery->paginate(10);

        return view('livewire.chat-contacts', compact(
            'acceptedContacts',
            'pendingContacts',
            'incomingInvites'
        ))->layout('layouts.app');
    }



}
