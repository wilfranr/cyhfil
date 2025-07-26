<?php

namespace App\Filament\Resources\MaquinasResource\Pages;

use App\Filament\Resources\MaquinasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMaquinas extends CreateRecord
{
    protected static string $resource = MaquinasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
