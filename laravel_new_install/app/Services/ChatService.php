<?php

namespace App\Services;

use App\Models\ChatMessage;

class ChatService
{
    public function logEvent($sender, $message)
    {
        return ChatMessage::create([
            'sender' => $sender,
            'message' => $message,
            'type' => 'event',
        ]);
    }

    public function sendMessage($userId, $message)
    {
        return ChatMessage::create([
            'user_id' => $userId,
            'message' => $message,
            'type' => 'message',
        ]);
    }
}
