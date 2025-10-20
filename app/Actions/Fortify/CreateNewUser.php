<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\UploadedFile;
use App\Models\Contact;
use Illuminate\Support\Facades\Request;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'bio' => ['nullable', 'string', 'max:1000'],
            'status_message' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:1024'],
        ])->validate();

        // Criação do usuário
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'bio' => $input['bio'] ?? null,
            'status_message' => $input['status_message'] ?? null,
            'is_active' => true,
        ]);

        // Upload da foto de perfil (se existir)
        if (isset($input['profile_photo']) && $input['profile_photo'] instanceof UploadedFile) {
            $profilePhotoPath = $input['profile_photo']->store('profile-photos', 'public');
            $user->profile_photo_path = $profilePhotoPath;
            $user->save();
        }

        // Atualiza convites existentes para este email
        $pendingInvites = Contact::where('email', $user->email)
            ->whereNull('contact_id')
            ->get();

        foreach ($pendingInvites as $invite) {
            $invite->update([
                'contact_id' => $user->id,
                'status' => 'pending', // mantém pendente até o usuário aceitar no chat
            ]);
        }

        // Se veio token direto no registro (link específico)
        $inviteToken = $input['invite_token'] ?? Request::get('invite_token');
        if (!empty($inviteToken)) {
            $invite = Contact::where('token', $inviteToken)->first();
            if ($invite && $invite->contact_id === null) {
                $invite->update([
                    'contact_id' => $user->id,
                    'status' => 'pending',
                ]);
            }
        }

        return $user;
    }
}
