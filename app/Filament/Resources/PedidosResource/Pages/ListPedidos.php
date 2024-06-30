<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListPedidos extends ListRecords
{
    protected static string $resource = PedidosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\PedidosResource\Widgets\StatsOverview::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todos' => Tab::make(),
            'Nuevos' => Tab::make()->query(function (Builder $query) {
                $query->where('estado', 'Nuevo');
            })->icon('heroicon-o-star'),
            'En Costeo' => Tab::make()->query(function (Builder $query) {
                $query->where('estado', 'En_Costeo');
            })->icon('heroicon-c-list-bullet'),
            'Cotizados' => Tab::make()->query(function (Builder $query) {
                $query->where('estado', 'Cotizado');
            })->icon('heroicon-o-currency-dollar'),
            'Aprobados' => Tab::make()->query(function (Builder $query) {
                $query->where('estado', 'Aprobado');
            })->icon('ri-checkbox-line'),
            'Enviados' => Tab::make()->query(function (Builder $query) {
                $query->where('estado', 'Enviado');
            })->icon('heroicon-o-truck'),
            'Entregados' => Tab::make()->query(function (Builder $query) {
                $query->where('estado', 'Entregado');
            })->icon('heroicon-o-check-circle'),
            'Cancelados' => Tab::make()->query(function (Builder $query) {
                $query->where('estado', 'Cancelado');
            })->icon('heroicon-o-x-circle'),
        ];
    }

}
