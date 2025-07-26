<?php

namespace App\Filament\Resources\FabricanteResource\Pages;

use App\Filament\Resources\FabricanteResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListFabricantes extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = FabricanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    
}
