<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Filament\Notifications\Notification; // Usamos la clase Notification de Filament
use Filament\Notifications\Actions\Action;


class CreatePedidos extends CreateRecord
{
    protected static string $resource = PedidosResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate()
    {
        $pedido = $this->record;

        // Obtener todos los usuarios con los roles relevantes
        $recipients = User::role(['Administrador', 'super_admin', 'Analista'])->get();

        // Recorrer cada usuario y enviar la notificación con la URL personalizada
        foreach ($recipients as $recipient) {
            // Determinar el panel según el rol del usuario
            $panel = $this->getPanelBasedOnRole($recipient);

            // Generar la URL correcta para el pedido
            $url = "{$panel}/pedidos/{$pedido->id}/edit";

            // Enviar la notificación con el enlace correcto
            Notification::make()
                ->title('Pedido Creado')
                ->body("Se ha creado un nuevo pedido No. {$pedido->id}.")
                ->actions([
                    Action::make('Ver Pedido')
                        ->url($url)  // URL dinámica basada en el panel
                        ->button(),
                ])
                ->sendToDatabase($recipient);
        }

    }
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Pedido Creado')
            ->body("Se ha creado un nuevo pedido con ID: {$this->record->id}.")
            ->success()
            ->actions([
                Action::make('Ver Pedido')
                ->url($this->getResource()::getUrl('edit', ['record' => $this->record->id]))
                ->button(),
            ])
            ->send();
    }

    /**
     * Determinar el panel basado en el rol del usuario.
     */
    protected function getPanelBasedOnRole(User $user): string
    {
        if ($user->hasRole('Administrador') || $user->hasRole('super_admin')) {
            return '/admin'; // Panel de administración
        }

        if ($user->hasRole('Analista')) {
            return '/partes'; // Panel de partes para el analista
        }

        if ($user->hasRole('Vendedor')) {
            return '/ventas'; // Panel de ventas para el vendedor
        }
    }
}