<?php
namespace App\Observers;

use App\Models\Pedido;
use App\Events\MessageSent;
use App\Models\ChatMessage;

class PedidoObserver
{
    public function created(Pedido $pedido)
    {
        // Crea el mensaje que se va a enviar al chat
        $message = new ChatMessage([
            'message' => "{$pedido->user->name} ha creado un nuevo pedido.",
            'user_id' => $pedido->user_id, // O el ID del usuario que haya creado el pedido
        ]);
        $message->save();

        // Dispara el evento del chat para que se envíe en tiempo real
        broadcast(new MessageSent($message))->toOthers();
    }

    // Este método se llama cuando se actualiza un pedido
    public function updated(Pedido $pedido)
    {
        // Verificar si el estado ha cambiado a "En Costeo"
        if ($pedido->isDirty('estado') && $pedido->estado === 'En_Costeo') {
            // Crea el mensaje que se va a enviar al chat
            $message = new ChatMessage([
                'message' => "{$pedido->user->name} ha enviado el pedido #{$pedido->id} a Costeo.",
                'user_id' => $pedido->user_id, // O el ID del usuario que haya cambiado el estado
            ]);
            $message->save();

            // Dispara el evento del chat para que se envíe en tiempo real
            broadcast(new MessageSent($message))->toOthers();
        }
    }
}
