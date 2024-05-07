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
            Stat::make('Pedidos Enviados', Pedido::where('estado', 'enviado')->count())
                ->icon('heroicon-o-truck'),
            Stat::make('Pedidos Entregados', Pedido::where('estado', 'entregado')->count())
                ->icon('heroicon-o-check-circle'),
            Stat::make('Pedidos Cancelados', Pedido::where('estado', 'cancelado')->count())
                ->chart([1, 2, 3, 4, 5, 6])
                ->color('danger')
                ->icon('heroicon-o-x-circle'),

        ];
    }
}
