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
        
        return [
            $this->getGuardarCambiosAction(),
            $this->getWhatsappClienteAction()
        ];
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
                // Sincroniza los datos del formulario con el modelo
                $record->fill($this->form->getState());

                // Verificar si hay referencias en el pedido sin artículos asociados
                $referenciasSinArticulo = $record->referencias()
                    ->whereDoesntHave('referencia.articuloReferencia') // Revisa la relación articuloReferencia
                    ->exists();

                if ($referenciasSinArticulo) {
                    // Mostrar un mensaje de error y detener la acción
                    Notification::make()
                    ->title('No se puede enviar a costeo. Hay referencias sin artículos asociados.')
                    ->danger()
                    ->send();
                    
                    return;
                }

                //verificar si PedidoReferencia tiene sistemas asociados
                $referenciasSinSistema = $record->referencias()
                    ->whereNull('sistema_id')
                    ->exists();
                if ($referenciasSinSistema) {
                    // Mostrar un mensaje de error y detener la acción
                    Notification::make()
                    ->title('No se puede enviar a costeo. Hay referencias sin sistemas asociados.')
                    ->danger()
                    ->send();
                    
                    return;
                }

                $record->estado = 'En_Costeo';
                $record->save();

                // Enviar la notificación
                $this->sendNotification(
                    'Pedido Enviado a Costeo',
                    "El pedido No. {$record->id} ha sido enviado a Costeo."
                );

                // Redirigir al índice de pedidos
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

                // Guardar los cambios del formulario
                $record->fill($this->form->getState());
                $record->estado = 'Cotizado';
                $record->save();

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

                // Redirigir para generar el PDF
                $this->redirect(route('pdf.cotizacion', ['id' => $cotizacion->id]));
            });
    }


    // Acción: Aprobar Cotización
    private function getAprobarCotizacionAction(): Action
    {
        return Action::make('Aprobar')
            ->label('Aprobar Cotización')
            ->color('info')
            ->form([
                Select::make('direccion')
                    ->label('Dirección de Entrega')
                    ->preload()
                    ->searchable()
                    ->options(fn() => Direccion::where('tercero_id', $this->record->tercero_id)
                        ->pluck('direccion', 'id')
                        ->toArray())
                    ->required()
                    ->placeholder('Seleccione una dirección')
                    ->createOptionForm([
                        TextInput::make('direccion')
                            ->label('Nueva Dirección')
                            ->required(),
                    ])
                    ->createOptionUsing(function ($data) {
                        $direccion = Direccion::create([
                            'tercero_id' => $this->record->tercero_id,
                            'direccion' => $data['direccion'],
                            'city_id' => $this->record->tercero->city_id,
                            'state_id' => $this->record->tercero->city->state_id,
                            'country_id' => $this->record->tercero->city->state->country_id,
                        ]);
                        return $direccion->id;
                    }),
            ])
            ->action(function (array $data) {
                // Obtener el registro actual
                $record = $this->getRecord();
                // Actualizar el registro con los datos del formulario
                $this->form->getState();
                $record->fill($data);
                // Cambiar el estado del pedido a 'Aprobado'
                $record->estado = 'Aprobado';
                // cambiar el estado de la cotización a 'Aprobado'
                $cotizacion = \App\Models\Cotizacion::where('pedido_id', $record->id)->first();
                $cotizacion->estado = 'Aprobada';
                // Guardar el pedido con todos los cambios
                $record->save();
                // Notificar al usuario
                Notification::make()
                    ->title('Éxito')
                    ->body('La cotización ha sido aprobada y todos los cambios han sido guardados.')
                    ->success()
                    ->send();
                //Viene desde la tabla pedido_referencia
                $pedido_referencia = PedidoReferencia::where('pedido_id', $record->id)->get();
                // dd($pedido_referencia);
                foreach ($pedido_referencia as $referencia) {
                    $proveedor = PedidoReferenciaProveedor::where('pedido_id', $referencia->id)->first();
                    // dd($proveedor);
                    $referencia_nombre = Referencia::where('id', $referencia->referencia_id);
                    //crear orden de compra
                    $ordenCompra = new \App\Models\OrdenCompra();
                    $ordenCompra->pedido_id = $record->id;
                    $ordenCompra->tercero_id = $record->tercero_id;
                    // $ordenCompra->proveedor_id = $record->proveedor_id;
                    $ordenCompra->referencia_id = $referencia->referencia_id;
                    $ordenCompra->fecha_expedicion = now();
                    $ordenCompra->fecha_entrega = now()->addDays(30);
                    $ordenCompra->observaciones = $record->observaciones;
                    $ordenCompra->direccion = $data['direccion'];
                    $ordenCompra->telefono = $record->tercero->telefono;
                    $ordenCompra->proveedor_id = $proveedor->proveedor_id;
                    $ordenCompra->cantidad = $proveedor->cantidad;
                    $ordenCompra->valor_unitario = $proveedor->costo_unidad;
                    $ordenCompra->valor_total = $proveedor->valor_total;
                    $ordenCompra->save();
                }
                //Traer todos los proveedores de este pedido
                // $proveedor = PedidoReferenciaProveedor::where('pedido_id', $pedido_referencia->id)->first();
                // dd($proveedor);
                // $ordenCompra_id = $ordenCompra->id;
                // Redirigir a la página de la orden de compra en PDF
                // return redirect()->route('pdf.ordenCompra', ['id' => $ordenCompra_id]);
                //Redirigir a la página de pedidos
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