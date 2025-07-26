<?php

namespace App\Filament\Resources\SistemasResource\Pages;

use App\Filament\Resources\SistemasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSistemas extends CreateRecord
{
    protected static string $resource = SistemasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
