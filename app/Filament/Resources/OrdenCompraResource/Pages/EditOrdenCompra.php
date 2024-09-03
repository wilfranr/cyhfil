<?php

namespace App\Filament\Resources\OrdenCompraResource\Pages;

use App\Filament\Resources\OrdenCompraResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrdenCompra extends EditRecord
{
    protected static string $resource = OrdenCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\DeleteAction::make(),   
            Action::make('print')
                ->label('Imprimir')
                ->icon('heroicon-o-printer')
                ->action(function () {
                    Notification::make()
                                ->title('Éxito')
                                ->body('El estado del pedido se ha cambiado a En Costeo y todos los cambios han sido guardados.')
                                ->success()
                                ->send();

                    return redirect()->route('pdf.ordenCompra', $this->record->id);

                })
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
