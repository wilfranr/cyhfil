<x-filament::modal id="chat-modal" width="7xl" slide-over>
    <x-slot name="header">
        <h2 class="text-lg font-medium text-gray-900">Chat</h2>
    </x-slot>

    <x-slot name="content">
        @include('chatify.partial') {{-- Asegúrate de que este archivo existe --}}
    </x-slot>
</x-filament::modal>


