<?php

namespace App\Filament\Resources\ListasResource\Pages;

use App\Filament\Resources\ListasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListListas extends ListRecords
{
    protected static string $resource = ListasResource::class;

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
            'Marcas' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Marca');
            }),
            'Tipos de Máquina' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Tipo de Máquina');
            }),
            'Definiciones' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Definición de artículo');
            }),
            'Uni. Medida' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Unidad de medida');
            }),
            'Tipo Medida' => Tab::make()->query(function (Builder $query) {
                $query->where('tipo', 'Tipo de medida');
            }),
        ];
    }
}
