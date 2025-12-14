<?php

namespace App\Filament\Resources\SubcategoriaLandingResource\Pages;

use App\Filament\Resources\SubcategoriaLandingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubcategoriaLandings extends ListRecords
{
    protected static string $resource = SubcategoriaLandingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
