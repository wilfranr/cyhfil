<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use App\Models\{City, Contacto, Country, State, Direccion, PedidoReferencia, User, PedidoReferenciaProveedor, Referencia, Cotizacion, OrdenCompra, OrdenTrabajo};
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\{Card, Select, Textarea, TextInput};
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPedidos extends EditRecord
{
    protected static string $resource = PedidosResource::class;

    protected function beforeSave()
    {
        $this->record->user_id = auth()->user()->id;
    }

    protected function getHeaderActions(): array
    {
        $rol = Auth::user()->roles->first()->name;

        // Verificar si el usuario es "Administrador" o "super-admin"
        if (in_array($rol, ['Administrador', 'super_admin'])) {
            if ($this->record->estado === 'Nuevo') {
                return [
                    $this->getGuardarCambiosAction(),
                    $this->getEnviarACosteoAction(),
                    $this->getWhatsappClienteAction(),
                    // Otras acciones específicas para el estado "Nuevo"
                ];
            } elseif ($this->record->estado === 'En_Costeo') {
                return [
                    $this->getGuardarCambiosAction(),
                    $this->getGenerarCotizacionAction(),
                    $this->getWhatsappClienteAction(),
                    // Otras acciones específicas para el estado "En_Costeo"
                ];
            } elseif ($this->record->estado === 'Cotizado') {
                return [
                    $this->getGuardarCambiosAction(),
                    $this->getAprobarCotizacionAction(),
                    $this->getRechazarCotizacionAction(),
                    $this->getWhatsappClienteAction(),
                    // Otras acciones específicas para el estado "Cotizado"
                ];
            } elseif ($this->record->estado === 'Aprobado') {
                return [
                    $this->getGuardarCambiosAction(),
                    $this->getWhatsappClienteAction(),
                    // Otras acciones específicas para el estado "Aprobado"
                ];
            }
            // Estado por defecto si no se cumple ninguna condición anterior
            return [
                $this->getGuardarCambiosAction(),
                $this->getWhatsappClienteAction(),
                // Agregar otras acciones genéricas si es necesario
            ];
        }

        // Roles específicos
        switch ($rol) {
            case 'Analista':
                return $this->getAnalistaActions();
            case 'Logistica':
                return $this->getLogisticaActions();
            case 'Vendedor':
                return $this->getVendedorActions();
            default:
                return $this->getDefaultActions();
        }
    }



    // Acciones específicas para "Analista"
    private function getAnalistaActions(): array
    {
        if ($this->record->estado === 'Nuevo') {
            return [
                $this->getGuardarCambiosAction(),
                $this->getEnviarACosteoAction(),
            ];
        }

        return [$this->getGuardarCambiosAction()];
    }

    // Acciones específicas para "Logística"
    private function getLogisticaActions(): array
    {
        return [$this->getGuardarCambiosAction()];
    }

    // Acciones específicas para "Vendedor"
    private function getVendedorActions(): array
    {
        if ($this->record->estado === 'En_Costeo') {
            return [
                $this->getGuardarCambiosAction(),
                $this->getGenerarCotizacionAction(),
                $this->getWhatsappClienteAction(),
            ];
        } elseif ($this->record->estado === 'Cotizado') {
            return [
                $this->getGuardarCambiosAction(),
                $this->getAprobarCotizacionAction(),
                $this->getRechazarCotizacionAction(),
                $this->getWhatsappClienteAction(),
            ];
        }

        return [$this->getGuardarCambiosAction()];
    }

    // Acciones predeterminadas
    private function getDefaultActions(): array
    {
        return [$this->getGuardarCambiosAction()];
    }

    // Acción: Guardar cambios
    private function getGuardarCambiosAction(): Action
    {
        return Action::make('Guardar Cambios')
            ->action('save')
            ->color('primary');
    }

    // Acción: Enviar a Costeo
    private function getEnviarACosteoAction(): Action
    {
        return Action::make('Enviar a Costeo')
            ->action(function () {
                $record = $this->getRecord();
                $record->estado = 'En_Costeo';
                $record->save();

                $this->sendNotification(
                    'Pedido Enviado a Costeo',
                    "El pedido No. {$record->id} ha sido enviado a Costeo."
                );

                Notification::make()
                    ->title('Éxito')
                    ->body('El estado del pedido se ha cambiado a En Costeo.')
                    ->success()
                    ->send();

                $this->redirect($this->getResource()::getUrl('index'));
            })
            ->color('info')
            ->requiresConfirmation()
            ->modalHeading('¿Enviar a Costeo?')
            ->modalDescription('Esta acción guardará todos los cambios y cambiará el estado del pedido a En Costeo.');
    }

    // Acción: Generar Cotización
    private function getGenerarCotizacionAction(): Action
    {
        return Action::make('Generar Cotización')
            ->color('success')
            ->action(function () {
                $record = $this->getRecord();

                // Verificar referencias activas
                if (!PedidoReferencia::where('pedido_id', $record->id)->where('estado', 1)->exists()) {
                    Notification::make()
                        ->title('Error')
                        ->body('Debe seleccionar al menos una referencia activa para generar la cotización.')
                        ->danger()
                        ->send();
                    return;
                }

                // Crear la cotización
                $cotizacion = Cotizacion::create([
                    'pedido_id' => $record->id,
                    'tercero_id' => $record->tercero_id,
                ]);

                Notification::make()
                    ->title('Cotización Generada')
                    ->body('La cotización ha sido generada exitosamente.')
                    ->success()
                    ->send();

                $this->redirect(route('pdf.cotizacion', ['id' => $cotizacion->id]));
            });
    }

    // Acción: Aprobar Cotización
    private function getAprobarCotizacionAction(): Action
    {
        return Action::make('Aprobar Cotización')
            ->color('info')
            ->form([
                Select::make('direccion')
                    ->label('Dirección de Entrega')
                    ->preload()
                    ->searchable()
                    ->options(fn() => Direccion::where('tercero_id', $this->record->tercero_id)
                        ->pluck('direccion', 'id')
                        ->toArray())
                    ->required(),
            ])
            ->action(function ($data) {
                $record = $this->getRecord();
                $record->estado = 'Aprobado';
                $record->save();

                Notification::make()
                    ->title('Éxito')
                    ->body('La cotización ha sido aprobada.')
                    ->success()
                    ->send();

                $this->redirect($this->getResource()::getUrl('index'));
            });
    }

    // Acción: Rechazar Cotización
    private function getRechazarCotizacionAction(): Action
    {
        return Action::make('Rechazar Cotización')
            ->color('danger')
            ->form([
                Select::make('motivo_rechazo')
                    ->label('Motivo de Rechazo')
                    ->options([
                        'precio' => 'Precio',
                        'tiempo_entrega' => 'Tiempo de Entrega',
                        'condiciones_pago' => 'Condiciones de Pago',
                        'otros' => 'Otros',
                    ])
                    ->required(),
                Textarea::make('comentarios_rechazo')
                    ->label('Comentarios'),
            ])
            ->action(function ($data) {
                $record = $this->getRecord();
                $record->estado = 'Rechazado';
                $record->motivo_rechazo = $data['motivo_rechazo'];
                $record->comentarios_rechazo = $data['comentarios_rechazo'] ?? '';
                $record->save();

                Notification::make()
                    ->title('Éxito')
                    ->body('La cotización ha sido rechazada.')
                    ->success()
                    ->send();

                $this->redirect($this->getResource()::getUrl('index'));
            });
    }

    // Acción: WhatsApp Cliente
    private function getWhatsappClienteAction(): Action
    {
        return Action::make('Whatsapp Cliente')
            ->label(function () {
                $record = $this->getRecord();
                $contacto = Contacto::find($record->contacto_id);

                return $contacto ? $contacto->nombre : 'Sin Contacto';
            })
            ->icon('ri-whatsapp-line')
            ->color('success')
            ->url(function () {
                $record = $this->getRecord();
                $contacto = Contacto::find($record->contacto_id);

                $telefono = $contacto ? $contacto->telefono : $record->tercero->telefono;

                return "https://wa.me/$telefono";
            }, shouldOpenInNewTab: true);
    }

    /**
     * Método para enviar notificaciones.
     */
    private function sendNotification(string $title, string $body): void
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->success()
            ->send();
    }

    /**
     * Obtener URL de redirección.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
