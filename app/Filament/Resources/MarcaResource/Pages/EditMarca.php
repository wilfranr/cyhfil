<?php

namespace App\Filament\Resources\MarcaResource\Pages;

use App\Filament\Resources\MarcaResource;
use App\Filament\Resources\MarcaResource\Widgets\ReferenciasMarcas;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarca extends EditRecord
{
    protected static string $resource = MarcaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    // protected function getFooterWidgets(): array
    // {
    //     return [
            
    //         \App\Filament\Resources\MarcaResource\Widgets\ReferenciasMarcas::make(),
    //     ];
    // }
}
