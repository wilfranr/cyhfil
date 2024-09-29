<?php

namespace App\Filament\Logistica\Resources\OrdenTrabajoResource\Pages;

use App\Filament\Logistica\Resources\OrdenTrabajoResource;
use App\Filament\Logistica\Resources\TerceroResource;
use App\Models\OrdenTrabajo;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrdenTrabajo extends EditRecord
{
    protected static string $resource = OrdenTrabajoResource::class;

    protected function getHeaderActions(): array
    {
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
        ];
    }
}
