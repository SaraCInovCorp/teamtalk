@extends('layouts.landing')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center px-4">
    <header class="flex flex-col items-center pt-10 max-w-3xl text-center">
        <h1 class="text-5xl font-extrabold text-blue-800 mb-6">Bem-vindo ao TeamTalk</h1>

        <p class="mb-4 text-lg text-gray-600 leading-relaxed">
            Imagine uma ferramenta de comunicação que elimina custos exorbitantes, vem com código
            aberto, é personalizável e totalmente privada. Essa é a essência do <strong>TeamTalk</strong> — 
            a solução de chat que coloca você no controle de tudo.
        </p>
        <p class="mb-8 text-lg text-gray-600 leading-relaxed">
            Hospede seu próprio servidor, mantenha a privacidade, customize funcionalidades, e 
            conecte sua equipe em uma plataforma moderna e segura.
        </p>

        <a href="{{ route('register') }}" class="px-10 py-3 rounded-md bg-blue-700 hover:bg-blue-800 text-white text-lg font-bold shadow transition">
            Experimente gratuitamente
        </a>
        <p class="mt-2 text-sm text-gray-400">Não precisa de cartão — crie seu espaço agora!</p>
    </header>

    <section class="container mx-auto flex flex-col md:flex-row gap-12 justify-center items-center mt-16 mb-16 max-w-6xl">
        <div class="flex-1 max-w-md space-y-6">
            <h2 class="text-3xl font-bold text-green-600">Por que escolher o TeamTalk?</h2>
            <ul class="space-y-4 text-gray-700 leading-relaxed">
                <li><span class="font-semibold text-blue-600">Economize enquanto mantém a equipe conectada:</span> elimine custos e taxas mensais, usando sua própria infraestrutura.</li>
                <li><span class="font-semibold text-blue-600">Flexibilidade e personalização:</span> código aberto para adaptar e criar a solução que sua empresa precisa.</li>
                <li><span class="font-semibold text-blue-600">Privacidade total e segurança:</span> comunicação autônoma, transparente e à prova de interferências externas.</li>
                <li><span class="font-semibold text-blue-600">Alto desempenho e escalabilidade:</span> suporte a milhares de usuários simultâneos, em dispositivos desktop e mobile.</li>
                <li><span class="font-semibold text-blue-600">Instalação simples e rápida:</span> deploy rápido via Docker ou servidores tradicionais, com SSL e segurança integrados.</li>
            </ul>
        </div>

        <div class="flex-1 flex justify-center">
            <img src="{{ asset('logo.png') }}" alt="TeamTalk - Chat para Equipes" class="w-74 h-72 object-contain rounded-lg shadow-lg" />
        </div>
    </section>

    <section class="bg-blue-50 py-12 px-6 rounded-lg text-center max-w-xl">
        <h3 class="text-xl font-semibold text-blue-700 mb-4">Comece agora o seu TeamTalk pessoal e 100% privado</h3>
        <p class="mb-6 text-gray-700 leading-relaxed">
            Baixe, configure e tenha seu chat corporativo no ar em minutos. Sem mensalidades, sem surpresa.
            Liberdade para sua equipe comunicar-se do seu jeito.
        </p>
        <a href="{{ route('register') }}" class="px-8 py-3 rounded-md bg-green-600 hover:bg-green-700 text-white font-bold shadow transition">
            Criar meu espaço grátis
        </a>
    </section>

    <footer class="mt-auto text-center py-8 max-w-md">
        <a href="{{ route('faq') }}" class="text-blue-700 hover:underline font-semibold">Perguntas frequentes</a>
        <p class="text-gray-500 mt-2">TeamTalk &copy; {{ date('Y') }}</p>
    </footer>
</div>
@endsection
