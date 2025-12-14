<?php

namespace App\Filament\Resources\SubcategoriaLandingResource\Pages;

use App\Filament\Resources\SubcategoriaLandingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubcategoriaLanding extends EditRecord
{
    protected static string $resource = SubcategoriaLandingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
