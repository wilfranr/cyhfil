<?php

namespace App\Filament\Logistica\Resources\TransportadoraResource\Pages;

use App\Filament\Logistica\Resources\TransportadoraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransportadoras extends ListRecords
{
    protected static string $resource = TransportadoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
