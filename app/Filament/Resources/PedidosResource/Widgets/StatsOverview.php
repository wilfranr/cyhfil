<?php

namespace App\Filament\Resources\PedidosResource\Widgets;

use App\Models\Pedido;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pedidos Nuevos', Pedido::where('estado', 'nuevo')->count())->icon('heroicon-o-document-text')
            ->description('Pendientes por procesar')
            ->chart([1, 3, 5, 10, 20, 40])
            ->color('success'),
            Stat::make('Pedidos Cotizados', Pedido::where('estado', 'cotizado')->count())
                ->icon('heroicon-o-currency-dollar'),
            Stat::make('Pedidos Entregados', Pedido::where('estado', 'entregado')->count())
                ->icon('heroicon-o-check-circle'),
            Stat::make('Pedidos Cancelados', Pedido::where('estado', 'cancelado')->count())
                ->chart([1, 2, 3, 4, 5, 6])
                ->color('danger')
                ->icon('heroicon-o-x-circle'),

        ];
    }
}
