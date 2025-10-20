<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administração de Salas') }}
        </h2>
    </x-slot>
    <div class="p-8">
        <ul class="divide-y">
            @foreach ($rooms as $room)
                <li class="py-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-semibold">{{ $room->name }}</span>
                            <span class="text-gray-500 text-xs ml-4">({{ $room->users->count() }} membros)</span>
                        </div>
                        <a href="{{ route('chat.room.settings', $room->id) }}" class="btn btn-xs btn-info">Configurações</a>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-8">
            <h3 class="mb-2 font-bold">Logs do Sistema</h3>
            <span class="text-gray-500">Aqui futuramente será exibido o log de atividades das salas e sistema.</span>
        </div>
    </div>
</div>
