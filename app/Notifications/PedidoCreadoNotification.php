<?php

namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PedidoCreadoNotification extends Notification
{
    use Queueable;

    protected $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function via($notifiable)
    {
        return ['database', 'filament'];
    }

    public function toArray($notifiable)
    {
        return [
            'pedido_id' => $this->pedido->id,
            'user_name' => $this->pedido->user->name,
            'cliente' => $this->pedido->tercero->nombre,
            'message' => "Se ha creado un nuevo pedido",
        ];
    }

}
