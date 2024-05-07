<?php

namespace App\Filament\Resources\ListasResource\Pages;

use App\Filament\Resources\ListasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListas extends ListRecords
{
    protected static string $resource = ListasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
