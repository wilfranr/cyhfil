<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class NuevaNotificacion extends Notification
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'filament'];  // Usar canal 'filament' y 'database'
    }

    public function toFilament($notifiable)
    {
        return FilamentNotification::make()
            ->title('Nueva Notificación')
            ->body($this->message)
            ->success() // Puedes cambiar el estilo: 'success', 'warning', 'danger', etc.
            ->sendToDatabase($notifiable);
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }
}
