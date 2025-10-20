<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Informações do perfil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Atualize as informações do perfil e o endereço de e-mail da sua conta.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Selecione uma nova foto') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remover foto') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Seu endereço de e-mail não foi verificado.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Clique aqui para reenviar o e-mail de verificação.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('Um novo link de verificação foi enviado para seu endereço de e-mail.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Bio -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="bio" value="{{ __('Bio') }}" />
            <x-textarea id="bio" class="mt-1 block w-full" wire:model="state.bio" rows="3"></x-textarea>
            <x-input-error for="bio" class="mt-2" />
        </div>

        <!-- Status Message -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="status_message" value="{{ __('Status Message') }}" />
            <x-input id="status_message" type="text" class="mt-1 block w-full" wire:model="state.status_message" autocomplete="status_message" />
            <x-input-error for="status_message" class="mt-2" />
        </div>

        <!-- Last Seen At (Somente leitura ou oculto normalmente, exiba só se for útil ao usuário) -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="last_seen_at" value="{{ __('Última atividade') }}" />
            <x-input id="last_seen_at" type="text" class="mt-1 block w-full bg-gray-100" value="{{ optional($this->user->last_seen_at)->format('d/m/Y H:i') }}" disabled />
        </div>

        <!-- Is Active (switch ou select, exemplo simples como checkbox) -->
        <div class="col-span-6 sm:col-span-4 flex items-center gap-2">
            <input id="is_active" type="checkbox" wire:model="state.is_active" />
            <x-label for="is_active" value="{{ __('Ativo') }}" />
            <x-input-error for="is_active" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Salvo.') }}
        </x-action-message>

        <x-button type="submit" wire:loading.attr="disabled" wire:target="photo">
            {{ __('Salvar') }}
        </x-button>
    </x-slot>
</x-form-section>
