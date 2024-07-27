<?php

namespace App\Filament\Logistica\Resources\TerceroResource\Pages;

use App\Filament\Logistica\Resources\TerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTercero extends EditRecord
{
    protected static string $resource = TerceroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
