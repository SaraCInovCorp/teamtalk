@component('mail::message')
{{ __('Você foi convidado para entrar no TeamTalk.') }}

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
{{ __('Se ainda não possui uma conta, você pode criar uma clicando no botão abaixo. Após criar a conta, use o botão para aceitar o convite:') }}

@component('mail::button', ['url' => route('register', ['invite_token' => $token])])
{{ __('Criar Conta') }}
@endcomponent

{{ __('Se você já tem uma conta, pode aceitar o convite clicando no botão abaixo:') }}

@else
{{ __('Você pode aceitar o convite clicando no botão abaixo:') }}
@endif

@php
    $acceptUrl = url('/invite/accept/'.$token); // ou outra rota que implemente aceitação
@endphp

@component('mail::button', ['url' => $acceptUrl])
{{ __('Aceitar Convite') }}
@endcomponent

{{ __('Se você não esperava receber este convite, pode ignorar este email.') }}
@endcomponent
