<x-filament::button
    icon="heroicon-o-chat-bubble-left-right"
    color="primary"
    wire:click="openChat"
>
    Chat
</x-filament::button>

@push('scripts')
<script>
    document.addEventListener("livewire:load", () => {
        Livewire.on('openChatModal', () => {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'chat-modal' } }));
        });
    });
</script>
@endpush

