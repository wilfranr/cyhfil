<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
        // Incluye el nombre del usuario que envió el mensaje
        $this->user = $message->user->name; // Asegúrate de que ChatMessage tiene una relación con el modelo User
    }

    public function broadcastOn()
    {
        return new Channel('new-public-chat');
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    // Incluye el nombre del usuario junto con el mensaje
    public function broadcastWith()
    {
        return [
            'message' => $this->message->message,
            'sender' => $this->user, // Envía el nombre del usuario
            'created_at' => $this->message->created_at->format('d M H:i'),
        ];
    }
}
