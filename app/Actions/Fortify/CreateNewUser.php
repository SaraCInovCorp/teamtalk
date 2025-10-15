<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\UploadedFile;

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
            'avatar' => ['nullable', 'image', 'max:1024'],
            'profile_photo' => ['nullable', 'image', 'max:1024'],
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'bio' => $input['bio'] ?? null,
            'status_message' => $input['status_message'] ?? null,
            'is_active' => true,
        ]);

        if (isset($input['avatar']) && $input['avatar'] instanceof UploadedFile) {
            $avatarPath = $input['avatar']->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        if (isset($input['profile_photo']) && $input['profile_photo'] instanceof UploadedFile) {
            $profilePhotoPath = $input['profile_photo']->store('profile-photos', 'public');
            $user->profile_photo_path = $profilePhotoPath;
        }

        $user->save();

        return $user;

    }
}
