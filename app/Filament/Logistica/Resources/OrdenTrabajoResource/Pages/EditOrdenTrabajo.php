<?php

namespace App\Filament\Logistica\Resources\OrdenTrabajoResource\Pages;

use App\Filament\Logistica\Resources\OrdenTrabajoResource;
use App\Models\OrdenTrabajo;
use Filament\Actions;
use Filament\Actions\Action;
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
                        return redirect()->route('ordenTrabajo.pdf', $ordenTrabajo->id);
                    }),
        ];
    }
}
