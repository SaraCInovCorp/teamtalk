<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Contact;

class InviteAccept extends Component
{
    public $token;
    public $message = '';
    public $redirect = false;

    public function mount($token)
    {
        $this->token = $token;
        $contact = Contact::where('token', $token)->first();

        if (!$contact) {
            abort(404);
        }

        if ($contact->contact_id === null) {
            // redirecionar para registro com token
            $this->message = 'Você precisa criar uma conta para aceitar o convite. Redirecionando para registro...';
            $this->redirect = route('register', ['invite_token' => $token]);
        } else {
            // Aceitar convite para usuário já registrado
            $contact->update([
                'status' => 'accepted',
                'token' => null,
            ]);
            $this->message = 'Convite aceito com sucesso!';
        }

       
    }

    public function redirectTo(string $routeName)
    {
        return redirect()->route($routeName);
    }

    public function render()
    {
        return view('livewire.invite-accept')->layout('layouts.app-layout');
    }

}
