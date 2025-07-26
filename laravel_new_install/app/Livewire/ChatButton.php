<?php

namespace App\Livewire;

use Livewire\Component;

class ChatButton extends Component
{
    public function openChat()
{
    logger('openChat ejecutado');
    $this->dispatch('openChatModal');
}


    public function render()
    {
        return view('livewire.chat-button');
    }
}
