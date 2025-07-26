<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPedidos extends ViewRecord
{
    protected static string $resource = PedidosResource::class;

    protected function getHeaderActions(): array
    {
        if (in_array($this->record->estado, ['Aprobado', 'Entregado', 'Cancelado'])) {
            return [];
        }

        return [
            Actions\EditAction::make(),
        ];
    }
}
