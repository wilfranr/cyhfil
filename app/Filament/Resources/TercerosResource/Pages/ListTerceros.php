<?php

namespace App\Filament\Resources\TercerosResource\Pages;

use App\Filament\Resources\TercerosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTerceros extends ListRecords
{
    protected static string $resource = TercerosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todos' => Tab::make(),
            'Clientes' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Cliente');
            }),
            'Proveedores' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Proveedor');
            }),
        ];
    }
}
