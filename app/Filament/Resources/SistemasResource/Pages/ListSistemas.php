<?php

namespace App\Filament\Resources\SistemasResource\Pages;

use App\Filament\Resources\SistemasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSistemas extends ListRecords
{
    protected static string $resource = SistemasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
