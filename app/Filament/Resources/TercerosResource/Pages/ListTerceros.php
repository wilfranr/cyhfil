<?php

namespace App\Filament\Resources\TercerosResource\Pages;

use App\Filament\Resources\TercerosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerceros extends ListRecords
{
    protected static string $resource = TercerosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // public function getTabs(): array
    // {
    //     return [
    //         'Todos' => Tab::make(),
    //         'Nuevos' => Tab::make()->query(function (Builder $query) {
    //             $query->where('estado', 'Nuevo');
    //         })->icon('heroicon-o-star'),
    //         'Enviados' => Tab::make()->query(function (Builder $query) {
    //             $query->where('estado', 'Enviado');
    //         })->icon('heroicon-o-truck'),
    //         'Entregados' => Tab::make()->query(function (Builder $query) {
    //             $query->where('estado', 'Entregado');
    //         })->icon('heroicon-o-check-circle'),
    //         'Cancelados' => Tab::make()->query(function (Builder $query) {
    //             $query->where('estado', 'Cancelado');
    //         })->icon('heroicon-o-x-circle'),
    //     ];
    // }
}
