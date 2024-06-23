<?php

namespace App\Filament\Ventas\Resources\TercerosResource\Pages;

use App\Filament\Ventas\Resources\TercerosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerceros extends ListRecords
{
    protected static string $resource = TercerosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    } 
}
