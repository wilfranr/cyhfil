<?php

namespace App\Filament\Resources\ListasResource\Pages;

use App\Filament\Resources\ListasResource;
use App\Models\Marca;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateListas extends CreateRecord
{
    protected static string $resource = ListasResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterSave(): void
    {
        $data = $this->record->toArray();

        // Si el tipo es 'Fabricante', crear la entrada en la tabla 'marcas'
        if ($data['tipo'] === 'Fabricante') {
            Marca::create([
                'nombre' => $data['nombre'],
            ]);
        }
    }
}
