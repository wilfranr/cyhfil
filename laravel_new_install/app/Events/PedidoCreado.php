<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PedidoCreado implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new Channel('new-public-chat');
    }

    public function broadcastAs()
    {
        return 'pedido.creado';
    }

    public function broadcastWith()
    {
        return [
            'message' => "{$this->user->name} ha creado un pedido",
            'created_at' => now()->format('d M H:i'),
        ];
    }
}
