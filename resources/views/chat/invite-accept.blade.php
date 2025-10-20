<x-app-layout>
    <div class="p-8">
        @livewire('invite-accept', ['token' => request()->token])
    </div>
</x-app-layout>
