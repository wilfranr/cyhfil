<?php

namespace App\Filament\Resources\OrdenTrabajoResource\Pages;

use App\Filament\Logistica\Resources\TerceroResource;
use App\Filament\Resources\OrdenTrabajoResource;
use App\Models\OrdenTrabajo;
use App\Models\Pedido;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrdenTrabajo extends EditRecord
{
    protected static string $resource = OrdenTrabajoResource::class;

    protected function getHeaderActions(): array
    {
        if ($this->getRecord()->estado == 'Pendiente') {
            return [
                Actions\DeleteAction::make(),
                Action::make('print')
                    ->label('Imprimir Guia')
                    ->icon('heroicon-o-printer')
                    ->action(function (OrdenTrabajo $ordenTrabajo) {
                        $record = $this->getRecord();

                        // Actualizar el registro con los datos del formulario
                        $this->form->getState();


                        // Guardar el pedido con todos los cambios
                        $record->save();
                        //Guardar datos ctuales
                        $ordenTrabajo->save();
                        //validar que los campos estén bien diligenciados
                        if ($ordenTrabajo->transportadora == null) {
                            Notification::make()
                                ->title('Error')
                                ->body('Debe seleccionar la transportadora que llevará la orden de trabajo')
                                ->danger() // Establece el color de la notificación como rojo (de error)
                                ->send();
                            return redirect()->back();
                        }
                        return redirect()->route('ordenTrabajo.pdf', $ordenTrabajo->id);
                    }),
                Action::make('ver_cliente')
                    ->label('Ver Cliente')
                    ->icon('heroicon-o-user')
                    ->color('')
                    ->url(function (array $data) {
                        $record = $this->getRecord();
                        return TerceroResource::getUrl('edit', ['record' => $record->tercero_id]);
                    }, shouldOpenInNewTab: true),
                Action::make('Enviar Pedido')
                    ->label('Enviar Pedido')
                    ->icon('ri-mail-line')
                    ->color('primary')
                    ->action(function (OrdenTrabajo $ordenTrabajo) {
                        $record = $this->getRecord();

                        // Actualizar el registro con los datos del formulario
                        $this->form->getState();

                        //cambiar estado a pedido
                        $ordenTrabajo->estado = 'En Proceso';
                        //obtener idPedido
                        $idPedido = $ordenTrabajo->pedido_id;
                        //cambiar estado de pedido
                        $pedido = Pedido::find($idPedido);
                        $pedido->estado = 'Enviado';

                        // Guardar el pedido con todos los cambios
                        $record->save();
                        //Guardar datos ctuales
                        $ordenTrabajo->save();
                        //validar que los campos estén bien diligenciados
                        if ($ordenTrabajo->transportadora == null) {
                            Notification::make()
                                ->title('Error')
                                ->body('Debe seleccionar la transportadora que llevará la orden de trabajo')
                                ->danger() // Establece el color de la notificación como rojo (de error)
                                ->send();
                            return redirect()->back();
                        }
                        //enviar notificación
                        Notification::make()
                            ->title('Pedido Enviado')
                            ->body('El pedido ha sido enviado al cliente, por favor espere la confirmación del cliente')
                            ->send();
                        //retornar al index
                        return redirect()->route('ordenTrabajo.pdf', $ordenTrabajo->id);
                    }),
                Action::make('Cancelar Entrega')
                    ->label('Cancelar Entrega')
                    ->icon('ri-close-line')
                    ->color('danger')
                    ->form([
                        Select::make('motivo')
                            ->label('Motivo de Cancelación')
                            ->options([
                                'No hay disponibilidad de productos' => 'No hay disponibilidad de productos',
                                'El cliente canceló el pedido' => 'El cliente canceló el pedido',
                                'Cliente no responde' => 'Cliente no responde',
                                'Otro' => 'Otro',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        //obtener el registro actual
                        $record = $this->getRecord();
                        //obtener el motivo de cancelación
                        $motivo = $data['motivo'];
                        //cambiar estado a cancelado
                        $record->estado = 'Cancelado';
                        //guardar motivo de cancelación
                        $record->motivo_cancelacion = $motivo;
                        //obtener idPedido
                        $idPedido = $record->pedido_id;
                        //cambiar estado de pedido
                        Pedido::find($idPedido)->update(['estado' => 'Cancelado']);
                        // Guardar el pedido con todos los cambios
                        $record->save();
                        //enviar notificación
                        Notification::make()
                            ->title('Pedido Cancelado')
                            ->body('El pedido ha sido cancelado')
                            ->danger()
                            ->send();
                        //retornar al index
                        return redirect(request()->header('Referer')); // Esto recargará la página actual
                    }),


            ];
        } else if ($this->getRecord()->estado == 'En Proceso') {
            return [
                Actions\DeleteAction::make(),
                Action::make('print')
                    ->label('Imprimir Guia')
                    ->icon('heroicon-o-printer')
                    ->action(function (OrdenTrabajo $ordenTrabajo) {
                        $record = $this->getRecord();

                        // Actualizar el registro con los datos del formulario
                        $this->form->getState();


                        // Guardar el pedido con todos los cambios
                        $record->save();
                        //Guardar datos ctuales
                        $ordenTrabajo->save();
                        //validar que los campos estén bien diligenciados
                        if ($ordenTrabajo->transportadora == null) {
                            Notification::make()
                                ->title('Error')
                                ->body('Debe seleccionar la transportadora que llevará la orden de trabajo')
                                ->danger() // Establece el color de la notificación como rojo (de error)
                                ->send();
                            return redirect()->back();
                        }
                        return redirect()->route('ordenTrabajo.pdf', $ordenTrabajo->id);
                    }),
                Action::make('ver_cliente')
                    ->label('Ver Cliente')
                    ->icon('heroicon-o-user')
                    ->color('')
                    ->url(function (array $data) {
                        $record = $this->getRecord();
                        return TerceroResource::getUrl('edit', ['record' => $record->tercero_id]);
                    }, shouldOpenInNewTab: true),
                Action::make('Enviar Pedido')
                    ->label('Enviar Pedido')
                    ->icon('ri-mail-line')
                    ->color('primary')
                    ->action(function (OrdenTrabajo $ordenTrabajo) {
                        $record = $this->getRecord();

                        // Actualizar el registro con los datos del formulario
                        $this->form->getState();

                        //cambiar estado a pedido
                        $ordenTrabajo->estado = 'En Proceso';
                        //obtener idPedido
                        $idPedido = $ordenTrabajo->pedido_id;
                        //cambiar estado de pedido
                        Pedido::find($idPedido)->update(['estado' => 'Enviado']);

                        // Guardar el pedido con todos los cambios
                        $record->save();
                        //Guardar datos ctuales
                        $ordenTrabajo->save();
                        //validar que los campos estén bien diligenciados
                        if ($ordenTrabajo->transportadora == null) {
                            Notification::make()
                                ->title('Error')
                                ->body('Debe seleccionar la transportadora que llevará la orden de trabajo')
                                ->danger() // Establece el color de la notificación como rojo (de error)
                                ->send();
                            return redirect()->back();
                        }
                        //enviar notificación
                        Notification::make()
                            ->title('Pedido Enviado')
                            ->body('El pedido ha sido enviado al cliente, por favor espere la confirmación del cliente')
                            ->send();
                        //retornar al index
                        return redirect()->route('ordenTrabajo.pdf', $ordenTrabajo->id);
                    }),
                Action::make('Confirmar Entrega')
                    ->label('Confirmar Entrega')
                    ->icon('ri-check-line')
                    ->color('success')
                    ->action(function (OrdenTrabajo $ordenTrabajo) {
                        $record = $this->getRecord();

                        // Actualizar el registro con los datos del formulario
                        $this->form->getState();

                        //cambiar estado a pedido
                        $ordenTrabajo->estado = 'Completado';
                        //obtener idPedido
                        $idPedido = $ordenTrabajo->pedido_id;
                        //cambiar estado de pedido
                        Pedido::find($idPedido)->update(['estado' => 'Entregado']);

                        // Guardar el pedido con todos los cambios
                        $record->save();
                        //Guardar datos ctuales
                        $ordenTrabajo->save();
                        //validar que los campos estén bien diligenciados
                        if ($ordenTrabajo->transportadora == null) {
                            Notification::make()
                                ->title('Error')
                                ->body('Debe seleccionar la transportadora que llevará la orden de trabajo')
                                ->danger() // Establece el color de la notificación como rojo (de error)
                                ->send();
                            return redirect()->back();
                        }
                        //enviar notificación
                        Notification::make()
                            ->title('Pedido Entregado')
                            ->body('El pedido ha sido entregado al cliente.')
                            ->success()
                            ->send();
                        return redirect(request()->header('Referer')); // Esto recargará la página actual

                    }),
                Action::make('Cancelar Entrega')
                    ->label('Cancelar Entrega')
                    ->icon('ri-close-line')
                    ->color('danger')
                    ->form([
                        Select::make('motivo')
                            ->label('Motivo de Cancelación')
                            ->options([
                                'No hay disponibilidad de productos' => 'No hay disponibilidad de productos',
                                'El cliente canceló el pedido' => 'El cliente canceló el pedido',
                                'Cliente no responde' => 'Cliente no responde',
                                'Otro' => 'Otro',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        //obtener el registro actual
                        $record = $this->getRecord();
                        //obtener el motivo de cancelación
                        $motivo = $data['motivo'];
                        //cambiar estado a cancelado
                        $record->estado = 'Cancelado';
                        //guardar motivo de cancelación
                        $record->motivo_cancelacion = $motivo;
                        //obtener idPedido
                        $idPedido = $record->pedido_id;
                        //cambiar estado de pedido
                        Pedido::find($idPedido)->update(['estado' => 'Cancelado']);
                        // Guardar el pedido con todos los cambios
                        $record->save();
                        //enviar notificación
                        Notification::make()
                            ->title('Pedido Cancelado')
                            ->body('El pedido ha sido cancelado')
                            ->danger()
                            ->send();
                        //retornar al index
                        return redirect(request()->header('Referer')); // Esto recargará la página actual
                    }),

            ];
        } else if ($this->getRecord()->estado == 'Cancelado') {
            return [
                Actions\DeleteAction::make(),
                Action::make('ver_cliente')
                    ->label('Ver Cliente')
                    ->icon('heroicon-o-user')
                    ->color('')
                    ->url(function (array $data) {
                        $record = $this->getRecord();
                        return TerceroResource::getUrl('edit', ['record' => $record->tercero_id]);
                    }, shouldOpenInNewTab: true),
            ];
        } else if ($this->getRecord()->estado == 'Completado') {
            return [
                Actions\DeleteAction::make(),
                Action::make('ver_cliente')
                    ->label('Ver Cliente')
                    ->icon('heroicon-o-user')
                    ->color('')
                    ->url(function (array $data) {
                        $record = $this->getRecord();
                        return TerceroResource::getUrl('edit', ['record' => $record->tercero_id]);
                    }, shouldOpenInNewTab: true),
            ];
        }
        return [
            Actions\DeleteAction::make(),
            Action::make('ver_cliente')
                ->label('Ver Cliente')
                ->icon('heroicon-o-user')
                ->color('')
                ->url(function (array $data) {
                    $record = $this->getRecord();
                    return TerceroResource::getUrl('edit', ['record' => $record->tercero_id]);
                }, shouldOpenInNewTab: true),
            Action::make('Cancelar Entrega')
                ->label('Cancelar Entrega')
                ->icon('ri-close-line')
                ->color('danger')
                ->form([
                    Select::make('motivo')
                        ->label('Motivo de Cancelación')
                        ->options([
                            'No hay disponibilidad de productos' => 'No hay disponibilidad de productos',
                            'El cliente canceló el pedido' => 'El cliente canceló el pedido',
                            'Cliente no responde' => 'Cliente no responde',
                            'Otro' => 'Otro',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    //obtener el registro actual
                    $record = $this->getRecord();
                    //obtener el motivo de cancelación
                    $motivo = $data['motivo'];
                    //cambiar estado a cancelado
                    $record->estado = 'Cancelado';
                    //guardar motivo de cancelación
                    $record->motivo_cancelacion = $motivo;
                    //obtener idPedido
                    $idPedido = $record->pedido_id;
                    //cambiar estado de pedido
                    Pedido::find($idPedido)->update(['estado' => 'Cancelado']);
                    // Guardar el pedido con todos los cambios
                    $record->save();
                    //enviar notificación
                    Notification::make()
                        ->title('Pedido Cancelado')
                        ->body('El pedido ha sido cancelado')
                        ->danger()
                        ->send();
                    //retornar al index
                    return redirect(request()->header('Referer')); // Esto recargará la página actual
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    } 
}
