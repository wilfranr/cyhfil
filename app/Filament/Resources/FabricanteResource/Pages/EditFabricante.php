<?php

namespace App\Filament\Resources\FabricanteResource\Pages;

use App\Filament\Resources\FabricanteResource;
// use App\Filament\Resources\MarcaResource\Widgets\ReferenciasMarcas;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Editfabricante extends EditRecord
{
    protected static string $resource = FabricanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
