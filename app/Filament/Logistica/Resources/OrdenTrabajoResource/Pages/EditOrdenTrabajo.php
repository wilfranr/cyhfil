<?php

namespace App\Filament\Logistica\Resources\OrdenTrabajoResource\Pages;

use App\Filament\Logistica\Resources\OrdenTrabajoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrdenTrabajo extends EditRecord
{
    protected static string $resource = OrdenTrabajoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
