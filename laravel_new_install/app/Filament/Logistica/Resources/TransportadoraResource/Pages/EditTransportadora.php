<?php

namespace App\Filament\Logistica\Resources\TransportadoraResource\Pages;

use App\Filament\Logistica\Resources\TransportadoraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransportadora extends EditRecord
{
    protected static string $resource = TransportadoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
