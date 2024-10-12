<?php
namespace App\Observers;

use App\Models\Pedido;
use App\Events\MessageSent;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

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
        // Obtener el usuario autenticado
        $user = Auth::user(); 
        // Verificar si el estado ha cambiado a "En Costeo"
        if ($pedido->isDirty('estado') && $pedido->estado === 'En_Costeo') {

            // Crea el mensaje que se va a enviar al chat
            $message = new ChatMessage([
                'message' => "Ha enviado el pedido #{$pedido->id} a Costeo.",
                'user_id' => $user->id, // El ID del usuario que realizó la acción
            ]);
            $message->save();

            // Dispara el evento del chat para que se envíe en tiempo real
            broadcast(new MessageSent($message))->toOthers();
        } else if ($pedido->isDirty('estado') && $pedido->estado === 'Cotizado') {

            // Crea el mensaje que se va a enviar al chat
            $message = new ChatMessage([
                'message' => "Ha generado la cotización del pedido #{$pedido->id}.",
                'user_id' => $user->id, // El ID del usuario que realizó la acción
            ]);
            $message->save();

            // Dispara el evento del chat para que se envíe en tiempo real
            broadcast(new MessageSent($message))->toOthers();
        } else if( $pedido->isDirty('estado') && $pedido->estado === 'Aprobado') {
            // Crea el mensaje que se va a enviar al chat
            $message = new ChatMessage([
                'message' => "Ha aprobado el pedido #{$pedido->id}.",
                'user_id' => $user->id, // El ID del usuario que realizó la acción
            ]);
            $message->save();

            // Dispara el evento del chat para que se envíe en tiempo real
            broadcast(new MessageSent($message))->toOthers();
        } else if ($pedido->isDirty('estado') && $pedido->estado === 'Enviado') {
            // Crea el mensaje que se va a enviar al chat
            $message = new ChatMessage([
                'message' => "Ha enviado el pedido #{$pedido->id} al cliente.",
                'user_id' => $user->id, // El ID del usuario que realizó la acción
            ]);
            $message->save();

            // Dispara el evento del chat para que se envíe en tiempo real
            broadcast(new MessageSent($message))->toOthers();
        } else if ($pedido->isDirty('estado') && $pedido->estado === 'Entregado') {
            // Crea el mensaje que se va a enviar al chat
            $message = new ChatMessage([
                'message' => "{$pedido->tercero->nombre} ha recibido el pedido #{$pedido->id}.",
                'user_id' => $user->id, // El ID del usuario que realizó la acción
            ]);
            $message->save();

            // Dispara el evento del chat para que se envíe en tiempo real
            broadcast(new MessageSent($message))->toOthers();
        } else if ($pedido->isDirty('estado') && $pedido->estado === 'Cancelado') {
            // Crea el mensaje que se va a enviar al chat
            $message = new ChatMessage([
                'message' => "Ha cancelado el pedido #{$pedido->id}.",
                'user_id' => $user->id, // El ID del usuario que realizó la acción
            ]);
            $message->save();

            // Dispara el evento del chat para que se envíe en tiempo real
            broadcast(new MessageSent($message))->toOthers();
        }
    }
}
