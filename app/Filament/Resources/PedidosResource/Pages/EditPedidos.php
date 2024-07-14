<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use App\Models\{User, PedidoReferenciaProveedor};
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Actions\Action;
// use Filament\Actions\Modal\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
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
            return [
                Action::make('Guardar Cambios')->action('save')->color('primary'),
                // Action::make('Enviar a costeo')->action('changeStatus')->color('info'),
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
                        ->modalDescription('Esta acción guardará todos los cambios y cambiará el estado del pedido a En Costeo.')

            ];
        } elseif ($rol == 'Logistica') {
            return [

                // Actions\DeleteAction::make(),
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
            } elseif($this->record->estado == 'Cotizado'){
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

                            // Aquí puedes agregar el código para procesar las referencias si lo necesitas
                            // ...

                            // Notificar al usuario
                            // Notification::make()
                            //     ->title('Éxito')
                            //     ->body('La cotización ha sido generada y todos los cambios han sido guardados.')
                            //     ->success()
                            //     ->send();

                            // Redirigir a la página de la cotización en PDF
                            return redirect()->route('pdf.cotizacion', ['id' => $cotizacion_id]);
                        }),
                    Action::make('Aprobar')
                        ->label('Aprobar Cotización')
                        ->color('info')
                        ->action(function (array $data) {
                            // Obtener el registro actual
                            $record = $this->getRecord();

                            // Actualizar el registro con los datos del formulario
                            $this->form->getState();
                            $record->fill($data);

                            // Cambiar el estado del pedido a 'Aprobado'
                            $record->estado = 'Aprobado';

                            // Guardar el pedido con todos los cambios
                            $record->save();

                            // Notificar al usuario
                            Notification::make()
                                ->title('Éxito')
                                ->body('La cotización ha sido aprobada y todos los cambios han sido guardados.')
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
}
