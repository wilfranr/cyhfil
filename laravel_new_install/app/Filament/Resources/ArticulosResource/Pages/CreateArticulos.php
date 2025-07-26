<?php

namespace App\Filament\Resources\ArticulosResource\Pages;

use App\Filament\Resources\ArticulosResource;
use App\Models\Referencia;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateArticulos extends CreateRecord
{
    protected static string $resource = ArticulosResource::class;
    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }

    
}
