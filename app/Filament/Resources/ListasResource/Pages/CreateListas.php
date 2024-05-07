<?php

namespace App\Filament\Resources\ListasResource\Pages;

use App\Filament\Resources\ListasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateListas extends CreateRecord
{
    protected static string $resource = ListasResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
