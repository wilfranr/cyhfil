<?php

namespace App\Filament\Ventas\Resources\TercerosResource\Pages;

use App\Filament\Ventas\Resources\TercerosResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTerceros extends CreateRecord
{
    protected static string $resource = TercerosResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
