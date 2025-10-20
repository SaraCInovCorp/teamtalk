<div>
    @if ($message)
        <div class="p-4 mb-4 text-green-800 bg-green-200 rounded">
            {{ $message }}

            @if ($redirect)
                <script>
                    setTimeout(() => {
                        window.location.href = @js($redirect);
                    }, 3000);
                </script>
            @else
                <x-button wire:click="redirectTo('chat.contacts')">Ir para Contatos</x-button>
            @endif
        </div>
    @else
        <p>Processando aceitação do convite...</p>
    @endif
</div>
