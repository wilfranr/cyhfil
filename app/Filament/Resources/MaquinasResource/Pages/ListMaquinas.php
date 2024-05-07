<?php

namespace App\Filament\Resources\MaquinasResource\Pages;

use App\Filament\Resources\MaquinasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaquinas extends ListRecords
{
    protected static string $resource = MaquinasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
