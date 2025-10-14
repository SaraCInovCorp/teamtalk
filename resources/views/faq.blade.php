@extends('layouts.landing')

@section('content')
<div class="min-h-screen bg-white flex flex-col items-center pt-12 pb-24 px-4">
    <h1 class="text-3xl md:text-4xl font-extrabold text-blue-800 mb-6">Perguntas Frequentes</h1>
    <div class="max-w-2xl w-full space-y-4 bg-gray-50 rounded-lg shadow px-6 py-8">
        <div>
            <h2 class="text-xl font-semibold text-green-700 mb-1">O que é o TeamTalk?</h2>
            <p class="text-gray-700">Plataforma de chat corporativo para equipes modernas, com salas, mensagens privadas e comunicação instantânea.</p>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-green-700 mb-1">Como criar uma sala para meu time?</h2>
            <p class="text-gray-700">Na área logada, basta clicar em "Criar Sala", escolher nome/avatar e convidar membros da equipe.</p>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-green-700 mb-1">Meus dados estão seguros?</h2>
            <p class="text-gray-700">Sim! Toda comunicação é privada e restrita ao seu grupo. Apenas membros autenticados acessam os dados.</p>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-green-700 mb-1">Posso usar no celular?</h2>
            <p class="text-gray-700">Sim, o TeamTalk é responsivo e funciona perfeitamente em desktop, tablet e smartphone.</p>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-green-700 mb-1">É grátis?</h2>
            <p class="text-gray-700">Sim. Você pode criar uma conta e usar o sistema gratuitamente. Futuramente poderão existir planos avançados.</p>
        </div>
        <a href="{{ route('home') }}" class="mt-8 inline-block bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded-lg font-bold">Voltar à página inicial</a>
    </div>
</div>
@endsection
