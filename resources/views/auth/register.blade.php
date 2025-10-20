<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registo') }}
        </h2>
    </x-slot>
    <x-authentication-card>
        <x-validation-errors class="mb-4" />
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="invite_token" value="{{ request('invite_token') }}">
            @if (request('invite_token'))
                <p class="text-green-600 text-sm mt-2">Token detectado: {{ request('invite_token') }}</p>
            @endif

            <!-- Name -->
            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" type="password" name="password" required autocomplete="new-password" />
            </div>

            <!-- Confirmação password -->
            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <!-- Foto do perfil -->
            <div class="mt-4">
                <x-label for="profile_photo" value="Foto de perfil" />
                <x-input id="profile_photo" type="file" name="profile_photo" accept="image/png,image/jpeg,image/jpg" />
            </div>

            <!-- Bio -->
            <div class="mt-4">
                <x-label for="bio" value="Bio" />
                <x-text-area id="bio" name="bio" rows="2">{{ old('bio') }}</x-text-area>
            </div>

            <!-- Status Message -->
            <div class="mt-4">
                <x-label for="status_message" value="Status" />
                <x-input id="status_message" type="text" name="status_message" :value="old('status_message')" autocomplete="status_message" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button type="submit" class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
