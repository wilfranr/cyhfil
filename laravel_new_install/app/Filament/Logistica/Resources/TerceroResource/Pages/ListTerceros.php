<?php

namespace App\Filament\Logistica\Resources\TerceroResource\Pages;

use App\Filament\Logistica\Resources\TerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerceros extends ListRecords
{
    protected static string $resource = TerceroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
