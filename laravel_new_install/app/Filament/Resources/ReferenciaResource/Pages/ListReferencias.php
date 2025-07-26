<?php

namespace App\Filament\Resources\ReferenciaResource\Pages;

use App\Filament\Resources\ReferenciaResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListReferencias extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = ReferenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
