<?php

namespace App\Filament\Resources\ArticulosResource\Pages;

use App\Filament\Resources\ArticulosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArticulos extends ListRecords
{
    protected static string $resource = ArticulosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
