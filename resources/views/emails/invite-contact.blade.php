
@component('mail::message')
Olá!

Você foi convidado para entrar no **TeamTalk**.

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
Crie sua conta usando o botão abaixo e depois aceite o convite.
@component('mail::button', ['url' => route('register', ['invite_token' => $token])])
Criar Conta
@endcomponent
@endif

Aceite o convite clicando no botão abaixo:

@php
    $acceptUrl = url('/invite/accept/'.$token);
@endphp

@component('mail::button', ['url' => $acceptUrl])
Aceitar Convite
@endcomponent

Se você não esperava receber este convite, pode ignorar este email.
@endcomponent
