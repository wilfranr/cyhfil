<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use App\Models\{City, Country, State, Direccion, PedidoReferencia, User, PedidoReferenciaProveedor, Referencia};
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
// use Filament\Actions\Modal\Actions\Action;
use Filament\Forms\Components\{Card, Select, Textarea, TextInput};
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Widgets\{StatsOverviewWidget, Widget};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


class EditPedidos extends EditRecord
{
    protected static string $resource = PedidosResource::class;


    protected function beforeSave()
    {
        $this->record->user_id = auth()->user()->id;
    }
    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),

            // Action::make('Enviar a costeo')->action('changeStatus')->color('info'),
        ];
    }

    protected function getHeaderActions(): array
    {
        $rol = Auth::user()->roles->first()->name;
        if ($rol == 'Analista') {
            if ($this->record->estado == 'Nuevo') {

                return [
                    Action::make('Guardar Cambios')->action('save')->color('primary'),
                    Action::make('Enviar a Costeo')
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();

                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido
                            $record->estado = 'En_Costeo';

                            // Guardar el pedido con todos los cambios
                            $record->save();

                            // Obtener el vendedor (usuario que creó el pedido)
                            $vendedor = User::find($record->user_id);

                            // Obtener el id del pedido
                            $pedido = $this->record;

                            // Obtener el panel correcto para el vendedor
                            $panel = $this->getPanelBasedOnRole($vendedor);

                            $url = "{$panel}/pedidos/{$pedido->id}/edit";

                            // Enviar la notificación al vendedor
                            if ($vendedor) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Pedido Enviado a Costeo')
                                    ->body("El pedido No. {$record->id} ha sido enviado a Costeo.")
                                    ->actions([
                                        \Filament\Notifications\Actions\Action::make('Ver Pedido')
                                            ->button()
                                            ->url($url), // Enlace para ver el pedido
                                    ])
                                    ->sendToDatabase($vendedor);
                            }

                            // Notificar al usuario actual sobre el éxito
                            Notification::make()
                                ->title('Éxito')
                                ->body('El estado del pedido se ha cambiado a En Costeo y todos los cambios han sido guardados.')
                                ->success()
                                ->send();

                            // Refrescar la página para mostrar los cambios
                            $this->redirect($this->getResource()::getUrl('index'));
                        })
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('¿Enviar a Costeo?')
                        ->modalDescription('Esta acción guardará todos los cambios y cambiará el estado del pedido a En Costeo.')

                ];
            } else {
                return [
                    Action::make('Guardar Cambios')->action('save')->color('primary'),
                ];
            }
        } elseif ($rol == 'Logistica') {
            return [
                Action::make('Guardar Cambios')->action('save')->color('primary'),
            ];
        } elseif ($rol == 'Vendedor') {
            if ($this->record->estado == 'En_Costeo') {
                return [
                    Action::make('Guardar Cambios')->action('save')->color('primary'),
                    Action::make('GenerateCotización')
                        ->label('Generar Cotización')
                        ->color('success')
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();

                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido a 'Cotizado'
                            $record->estado = 'Cotizado';


                            // Guardar el pedido con todos los cambios
                            $record->save();

                            // Crear la cotización
                            $cotizacion = new \App\Models\Cotizacion();
                            $cotizacion->pedido_id = $record->id;
                            $cotizacion->tercero_id = $record->tercero_id;
                            $cotizacion->save();

                            $cotizacion_id = $cotizacion->id;

                            // Aquí puedes agregar el código para procesar las referencias si lo necesitas
                            // ...

                            // Notificar al usuario
                            Notification::make()
                                ->title('Éxito')
                                ->body('La cotización ha sido generada y todos los cambios han sido guardados.')
                                ->success()
                                ->send();

                            // Redirigir a la página de la cotización en PDF
                            return redirect()->route('pdf.cotizacion', ['id' => $cotizacion_id]);
                        })
                ];
            } elseif ($this->record->estado == 'Cotizado') {
                return [
                    Action::make('Guardar Cambios')->action('save')->color('primary'),
                    Action::make('GenerateNewCotización')
                        ->label('Generar Nueva Cotización')
                        ->color('success')
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();

                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido a 'Cotizado'
                            $record->estado = 'Cotizado';

                            // Guardar el pedido con todos los cambios
                            $record->save();

                            // Crear la cotización
                            $cotizacion = new \App\Models\Cotizacion();
                            $cotizacion->pedido_id = $record->id;
                            $cotizacion->tercero_id = $record->tercero_id;
                            $cotizacion->save();

                            $cotizacion_id = $cotizacion->id;



                            // Redirigir a la página de la cotización en PDF
                            return redirect()->route('pdf.cotizacion', ['id' => $cotizacion_id]);
                        }),
                    Action::make('Aprobar')
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
                        }),

                    Action::make('Rechazar')
                        ->label('Rechazar Cotización')
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
                                ->required()
                                ->placeholder('Seleccione un motivo'),
                            Textarea::make('comentarios_rechazo')
                                ->label('Comentarios')
                                ->placeholder('Ingrese comentarios adicionales (opcional)'),
                        ])
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();

                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido a 'Rechazado'
                            $record->estado = 'Rechazado';
                            $record->motivo_rechazo = $data['motivo_rechazo'];
                            $record->comentarios_rechazo = $data['comentarios_rechazo'] ?? '';

                            // Guardar el pedido con todos los cambios
                            $record->save();

                            // Cambiar el estado de la cotización a 'Rechazada'
                            $cotizacion = \App\Models\Cotizacion::where('pedido_id', $record->id)->first();
                            $cotizacion->estado = 'Rechazada';

                            // Notificar al usuario
                            Notification::make()
                                ->title('Éxito')
                                ->body('La cotización ha sido rechazada y todos los cambios han sido guardados.')
                                ->success()
                                ->send();

                            // Redirigir a la página de pedidos
                            $this->redirect($this->getResource()::getUrl('index'));
                        }),
                    Action::make('Regresar a Costeo')
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();

                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido
                            $record->estado = 'En_Costeo';

                            // Guardar el pedido con todos los cambios
                            $record->save();

                            // Obtener el vendedor (usuario que creó el pedido)
                            $vendedor = User::find($record->user_id);

                            // Obtener el id del pedido
                            $pedido = $this->record;

                            // Obtener el panel correcto para el vendedor
                            $panel = $this->getPanelBasedOnRole($vendedor);

                            $url = "{$panel}/pedidos/{$pedido->id}/edit";

                            // Enviar la notificación al vendedor
                            if ($vendedor) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Pedido Enviado a Costeo')
                                    ->body("El pedido No. {$record->id} ha sido enviado a Costeo.")
                                    ->actions([
                                        \Filament\Notifications\Actions\Action::make('Ver Pedido')
                                            ->button()
                                            ->url($url), // Enlace para ver el pedido
                                    ])
                                    ->sendToDatabase($vendedor);
                            }

                            // Notificar al usuario
                            Notification::make()
                                ->title('Éxito')
                                ->body('El estado del pedido se ha cambiado a En Costeo y todos los cambios han sido guardados.')
                                ->success()
                                ->send();

                            // Refrescar la página para mostrar los cambios
                            $this->redirect($this->getResource()::getUrl('edit', ['record' => $record]));
                        })
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('¿Enviar a Costeo?')
                        ->modalDescription('Esta acción guardará todos los cambios y cambiará el estado del pedido a En Costeo.')
                ];
            } else {

                return [
                    Action::make('Guardar Cambios')->action('save')->color('primary'),
                    // Action::make('Enviar a Costeo')
                    //     ->action(function (array $data) {
                    //         // Obtener el registro actual
                    //         $record = $this->getRecord();

                    //         // Actualizar el registro con los datos del formulario
                    //         $this->form->getState();
                    //         $record->fill($data);

                    //         // Cambiar el estado del pedido
                    //         $record->estado = 'En_Costeo';

                    //         // Guardar el pedido con todos los cambios
                    //         $record->save();

                    //         // Notificar al usuario
                    //         Notification::make()
                    //             ->title('Éxito')
                    //             ->body('El estado del pedido se ha cambiado a En Costeo y todos los cambios han sido guardados.')
                    //             ->success()
                    //             ->send();

                    //         // Refrescar la página para mostrar los cambios
                    //         $this->redirect($this->getResource()::getUrl('edit', ['record' => $record]));
                    //     })
                    //     ->color('info')
                    //     ->requiresConfirmation()
                    //     ->modalHeading('¿Enviar a Costeo?')
                    //     ->modalDescription('Esta acción guardará todos los cambios y cambiará el estado del pedido a En Costeo.')
                ];
            }
        } else {

            if ($this->record->estado == 'Nuevo') {

                return [
                    Action::make('Guardar Cambios')->action('save')->color('primary'),
                    Action::make('Enviar a Costeo')
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();

                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido
                            $record->estado = 'En_Costeo';

                            // Guardar el pedido con todos los cambios
                            $record->save();

                            // Notificar al usuario
                            Notification::make()
                                ->title('Éxito')
                                ->body('El estado del pedido se ha cambiado a En Costeo y todos los cambios han sido guardados.')
                                ->success()
                                ->send();

                            // Refrescar la página para mostrar los cambios
                            $this->redirect($this->getResource()::getUrl('index'));
                        })
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('¿Enviar a Costeo?')
                        ->modalDescription('Esta acción guardará todos los cambios y cambiará el estado del pedido a En Costeo.'),

                ];
            } elseif ($this->record->estado == 'En_Costeo') {
                return [
                    Action::make('Guardar Cambios')->action('save')->color('primary'),
                    Action::make('GenerateCotización')
                        ->label('Generar Cotización')
                        ->color('success')
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();

                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido a 'Cotizado'
                            $record->estado = 'Cotizado';


                            // Guardar el pedido con todos los cambios
                            $record->save();
                            // Verificar si hay al menos una referencia activa
                            $referenciasActivas = \App\Models\PedidoReferencia::where('pedido_id', $record->id)
                                ->where('estado', 1)
                                ->exists();
                            if (!$referenciasActivas) {
                                // Si no hay referencias activas, mostrar una notificación de alerta
                                Notification::make()
                                    ->title('Error')
                                    ->body('Debe seleccionar al menos una referencia activa para generar la cotización.')
                                    ->danger() // Establece el color de la notificación como rojo (de error)
                                    ->send();

                                return;
                            }
                            // Crear la cotización
                            $cotizacion = new \App\Models\Cotizacion();
                            $cotizacion->pedido_id = $record->id;
                            $cotizacion->tercero_id = $record->tercero_id;
                            $cotizacion->save();

                            $cotizacion_id = $cotizacion->id;



                            $url = route('pdf.cotizacion', ['id' => $cotizacion_id]);

                            // Usar una notificación para mostrar el link con la opción de abrir en nueva pestaña
                            return Notification::make()
                                ->title('Cotización Generada')
                                ->body('La cotización ha sido generada. <a href="' . $url . '" target="_blank" style="color: green;">Haz clic aquí para verla</a>.')
                                ->success()
                                ->send();
                        })
                ];
            } elseif ($this->record->estado == 'Cotizado') {
                return [
                    Action::make('Guardar Cambios')->action('save')->color('primary'),
                    Action::make('GenerateNewCotización')
                        ->label('Generar Nueva Cotización')
                        ->color('success')
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();


                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido a 'Cotizado'
                            $record->estado = 'Cotizado';

                            // Guardar el pedido con todos los cambios
                            $record->save();

                            // Verificar si hay al menos una referencia activa
                            $referenciasActivas = \App\Models\PedidoReferencia::where('pedido_id', $record->id)
                                ->where('estado', 1)
                                ->exists();
                            if (!$referenciasActivas) {
                                // Si no hay referencias activas, mostrar una notificación de alerta
                                Notification::make()
                                    ->title('Error')
                                    ->body('Debe seleccionar al menos una referencia activa para generar la cotización.')
                                    ->danger() // Establece el color de la notificación como rojo (de error)
                                    ->send();

                                return;
                            }
                            // Crear la cotización
                            $cotizacion = new \App\Models\Cotizacion();
                            $cotizacion->pedido_id = $record->id;
                            $cotizacion->tercero_id = $record->tercero_id;
                            $cotizacion->save();

                            $cotizacion_id = $cotizacion->id;



                            $url = route('pdf.cotizacion', ['id' => $cotizacion_id]);

                            // Usar una notificación para mostrar el link con la opción de abrir en nueva pestaña
                            return Notification::make()
                                ->title('Cotización Generada')
                                ->body('La cotización ha sido generada. <a href="' . $url . '" target="_blank" style="color: green;">Haz clic aquí para verla</a>.')
                                ->success()
                                ->send();
                        }),
                    Action::make('Aprobar')
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
                                    Select::make('country_id')
                                        ->options(fn(Get $get): Collection => Country::query()
                                            ->pluck('name', 'id'))
                                        ->label('País')
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('state_id', null);
                                            $set('city_id', null);
                                        }),
                                    Select::make('state_id')
                                        ->options(fn(Get $get): Collection => State::query()
                                            ->where('country_id', $get('country_id'))
                                            ->pluck('name', 'id'))
                                        ->label('Departamento')
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->afterStateUpdated(function (Set $set) {
                                            $set('city_id', null);
                                        }),

                                    Select::make('city_id')
                                        ->options(fn(Get $get): Collection => City::query()
                                            ->where('state_id', $get('state_id'))
                                            ->pluck('name', 'id'))
                                        ->label('Ciudad')
                                        ->searchable()
                                        ->live()
                                        ->preload(),
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

                            // Cambiar el estado de la cotización a 'Aprobado'

                            // Guardar el pedido con todos los cambios
                            $record->save();


                            // Obtener referencias de la tabla pedido_referencia
                            $pedido_referencia = PedidoReferencia::where('pedido_id', $record->id)->get();

                            foreach ($pedido_referencia as $referencia) {
                                $proveedor = PedidoReferenciaProveedor::where('pedido_id', $referencia->id)->first();

                                if (!$proveedor) {
                                    // Si no hay proveedor, mostrar una notificación de alerta
                                    Notification::make()
                                        ->title('Error')
                                        ->body('Debe seleccionar al menos un proveedor para generar la orden de compra.')
                                        ->danger() // Establece el color de la notificación como rojo (de error)
                                        ->send();

                                    return;
                                }

                                // Crear orden de compra
                                $ordenCompra = new \App\Models\OrdenCompra();
                                $ordenCompra->pedido_id = $record->id;
                                $ordenCompra->tercero_id = $record->tercero_id;
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

                            $cotizacion = \App\Models\Cotizacion::where('pedido_id', $record->id)->first();
                            $cotizacion->estado = 'Aprobada';
                            // Obtener el texto de la dirección seleccionada
                            $direccion = Direccion::find($data['direccion']);

                            // Crear una sola orden de trabajo para el pedido completo
                            $ordenTrabajo = new \App\Models\OrdenTrabajo();
                            $ordenTrabajo->tercero_id = $record->tercero_id;
                            $ordenTrabajo->pedido_id = $record->id;
                            $ordenTrabajo->cotizacion_id = $cotizacion->id;
                            $ordenTrabajo->estado = 'Pendiente';
                            $ordenTrabajo->fecha_ingreso = now();
                            $ordenTrabajo->fecha_entrega = now()->addDays(30);
                            $ordenTrabajo->observaciones = $record->observaciones;
                            $ordenTrabajo->direccion = $direccion->id; // Guardar el id de la dirección seleccionada
                            $ordenTrabajo->telefono = $record->tercero->telefono;
                            $ordenTrabajo->transportadora_id = null; // Puedes definir esto según tus necesidades
                            $ordenTrabajo->guia = null; // Puedes definir esto según tus necesidades
                            $ordenTrabajo->archivo = null; // Puedes definir esto según tus necesidades
                            $ordenTrabajo->save();

                            // Notificar al usuario
                            Notification::make()
                                ->title('Éxito')
                                ->body('La cotización ha sido aprobada y todos los cambios han sido guardados.')
                                ->success()
                                ->send();


                            // Redirigir a la página de pedidos
                            $this->redirect($this->getResource()::getUrl('index'));
                        }),


                    Action::make('Rechazar')
                        ->label('Rechazar Cotización')
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
                                ->required()
                                ->placeholder('Seleccione un motivo'),
                            Textarea::make('comentarios_rechazo')
                                ->label('Comentarios')
                                ->placeholder('Ingrese comentarios adicionales (opcional)'),
                        ])
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();

                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido a 'Rechazado'
                            $record->estado = 'Rechazado';
                            $record->motivo_rechazo = $data['motivo_rechazo'];
                            $record->comentarios_rechazo = $data['comentarios_rechazo'] ?? '';

                            // Guardar el pedido con todos los cambios
                            $record->save();

                            // Cambiar el estado de la cotización a 'Rechazada'
                            $cotizacion = \App\Models\Cotizacion::where('pedido_id', $record->id)->first();
                            $cotizacion->estado = 'Rechazada';

                            // Notificar al usuario
                            Notification::make()
                                ->title('Éxito')
                                ->body('La cotización ha sido rechazada y todos los cambios han sido guardados.')
                                ->success()
                                ->send();

                            // Redirigir a la página de pedidos
                            $this->redirect($this->getResource()::getUrl('index'));
                        }),
                    Action::make('Regresar a Costeo')
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();

                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido
                            $record->estado = 'En_Costeo';

                            // Guardar el pedido con todos los cambios
                            $record->save();

                            // Notificar al usuario
                            Notification::make()
                                ->title('Éxito')
                                ->body('El estado del pedido se ha cambiado a En Costeo y todos los cambios han sido guardados.')
                                ->success()
                                ->send();

                            // Refrescar la página para mostrar los cambios
                            $this->redirect($this->getResource()::getUrl('edit', ['record' => $record]));
                        })
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('¿Enviar a Costeo?')
                        ->modalDescription('Esta acción guardará todos los cambios y cambiará el estado del pedido a En Costeo.')
                ];
            } else {
                return [
                    Action::make('Guardar Cambios')->action('save')->color('primary'),
                ];
            }
        }
    }



    // public function generarCotizacion()
    // {

    //     $this->record->estado = 'Cotizado';
    //     $this->record->save();



    //     $cotizacion = new \App\Models\Cotizacion();
    //     $cotizacion->pedido_id = $this->record->id;
    //     $cotizacion->tercero_id = $this->record->tercero_id;

    //     $cotizacion->save();

    //     $cotizacion_id = $cotizacion->id;
    //     // dd($cotizacion_id);


    //     //traer referencias asociadas al pedido
    //     // for ($i=0; $i < count($this->record->referencias); $i++) { 
    //     //     $referencia_id = $this->record->referencias[$i]->id;
    //     //     // dd($referencia_id);
    //     //     $referencia_pedido_proveedor = \App\Models\PedidoReferenciaProveedor::where('pedido_id', $referencia_id)->first();
    //     //     // dd($referencia_pedido_proveedor->id);
    //     //     $cotizacion_referencia = new \App\Models\CotizacionReferenciaProveedor();
    //     //     $cotizacion_referencia->cotizacion_id = $cotizacion->id;
    //     //     $cotizacion_referencia->pedido_referencia_proveedor_id = $referencia_pedido_proveedor->id;

    //     //     $cotizacion_referencia->save();

    //     // }



    //     return redirect()->route('pdf.cotizacion', ['id' => $cotizacion_id]);
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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

        if ($user->hasRole('Logistica')) {
            return '/logistica'; // Panel de logistica para el logistica
        }
    }
}
