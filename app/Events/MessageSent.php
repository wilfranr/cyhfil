<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
        Log::info('Evento MessageSent construido', ['message' => $message]);

    }

    public function broadcastOn()
    {
        Log::info('Evento MessageSent disparado desde broadcastOn.', ['message' => $this->message]);

        return new Channel('new-public-chat');
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
