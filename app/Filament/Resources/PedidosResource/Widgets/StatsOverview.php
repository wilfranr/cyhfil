<?php

namespace App\Filament\Resources\PedidosResource\Widgets;

use App\Models\Pedido;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pedidos Nuevos', Pedido::where('estado', 'nuevo')->count())
                ->icon('heroicon-o-star')
                ->description('Pendientes por procesar')
                ->chart($this->getChartData('nuevo'))
                ->color('primary'),

            Stat::make('Pedidos En costeo', Pedido::where('estado', 'En_Costeo')->count())
                ->icon('heroicon-c-list-bullet')
                ->description('Pendientes por enviar cotización')
                ->chart($this->getChartData('En_Costeo'))
                ->color('secondary'),

            Stat::make('Pedidos Cotizados', Pedido::where('estado', 'Cotizado')->count())
                ->icon('heroicon-o-currency-dollar')
                ->description('Pendientes por aprobación')
                ->chart($this->getChartData('Cotizado'))
                ->color('info'),
                
                Stat::make('Pedidos Aprobados', Pedido::where('estado', 'Aprobado')->count())
                ->chart($this->getChartData('Aprobado'))
                ->description('Pendientes por enviar al cliente')
                ->color('warning')
                ->icon('ri-checkbox-line'),
            Stat::make('Pedidos Enviados', Pedido::where('estado', 'Enviado')->count())
                ->icon('ri-truck-line')
                ->description('Pendientes por entrega al cliente')
                ->chart($this->getChartData('Enviado'))
                ->color('info'),

            Stat::make('Pedidos Entregados', Pedido::where('estado', 'entregado')->count())
                ->icon('heroicon-o-check-circle')
                ->chart($this->getChartData('entregado'))
                ->color('success'),

        ];
    }

    protected function getChartData(string $estado): array
    {
        $orders = Pedido::select(DB::raw('DATE(updated_at) as date'), DB::raw('count(*) as count'))
            ->where('estado', $estado)
            ->where('updated_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Ensure there are entries for each of the last 7 days
        $dates = collect(range(0, 6))->mapWithKeys(function ($i) {
            return [now()->subDays($i)->format('Y-m-d') => 0];
        });

        $data = $dates->merge($orders)->values()->toArray();

        return $data;
    }
}
