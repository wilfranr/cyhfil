<?php

namespace App\Filament\Resources\PedidosResource\Widgets;

use App\Models\Pedido;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $user = Auth::user();
        $rol = $user->roles->first()->name;

        if ($rol == 'Analista') {
            return [
                Stat::make('Pedidos Nuevos', Pedido::where('estado', 'nuevo')->count())
                    ->icon('heroicon-o-star')
                    ->description('Pendientes por procesar')
                    ->chart($this->getBarChartData('nuevo'))
                    ->color('primary'),
            ];
        } elseif ($rol == 'Logistica') {
            return [
                Stat::make('Pedidos Aprobados', Pedido::where('estado', 'Aprobado')->count())
                    ->chart($this->getBarChartData('Aprobado'))
                    ->description('Pendientes por enviar al cliente')
                    ->color('warning')
                    ->icon('ri-checkbox-line'),
            ];
        } elseif ($rol == 'Vendedor') {
            return [
                Stat::make('Mis Pedidos', Pedido::where('user_id', $user->id)->count())
                    ->chart($this->getBarChartDataForUser($user->id))
                    ->description('Pedidos gestionados por ti')
                    ->color('success')
                    ->icon('heroicon-o-user'),
            ];
        } else {
            return [
                Stat::make('Pedidos Nuevos', Pedido::where('estado', 'nuevo')->count())
                    ->icon('heroicon-o-star')
                    ->description('Pendientes por procesar')
                    ->chart($this->getBarChartData('nuevo'))
                    ->color('primary'),

                Stat::make('Pedidos En costeo', Pedido::where('estado', 'En_Costeo')->count())
                    ->icon('heroicon-c-list-bullet')
                    ->description('Pendientes por enviar cotización')
                    ->chart($this->getBarChartData('En_Costeo'))
                    ->color('secondary'),

                Stat::make('Pedidos Cotizados', Pedido::where('estado', 'Cotizado')->count())
                    ->icon('heroicon-o-currency-dollar')
                    ->description('Pendientes por aprobación')
                    ->chart($this->getBarChartData('Cotizado'))
                    ->color('info'),

                Stat::make('Pedidos Aprobados', Pedido::where('estado', 'Aprobado')->count())
                    ->chart($this->getBarChartData('Aprobado'))
                    ->description('Pendientes por enviar al cliente')
                    ->color('warning')
                    ->icon('ri-checkbox-line'),

                Stat::make('Pedidos Enviados', Pedido::where('estado', 'Enviado')->count())
                    ->icon('ri-truck-line')
                    ->description('Pendientes por entrega al cliente')
                    ->chart($this->getBarChartData('Enviado'))
                    ->color('info'),

                Stat::make('Pedidos Entregados', Pedido::where('estado', 'entregado')->count())
                    ->icon('heroicon-o-check-circle')
                    ->chart($this->getBarChartData('entregado'))
                    ->color('success'),
            ];
        }
    }

    protected function getBarChartDataForUser(int $userId): array
    {
        // Obtiene los pedidos gestionados por un usuario específico
        $orders = Pedido::select(DB::raw('MONTH(updated_at) as month'), DB::raw('count(*) as count'))
            ->where('user_id', $userId)
            ->whereYear('updated_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Asegura que haya datos para cada mes
        $months = collect(range(1, 12))->mapWithKeys(function ($i) {
            return [$i => 0];
        });

        return $months->merge($orders)->values()->toArray();
    }
    protected function getBarChartData(string $estado): array
    {
        // Obtiene la cantidad de pedidos agrupados por mes para el año actual
        $orders = Pedido::select(DB::raw('MONTH(updated_at) as month'), DB::raw('count(*) as count'))
            ->where('estado', $estado)
            ->whereYear('updated_at', Carbon::now()->year) // Filtra por el año actual
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
        // dd($orders);

        // Asegura que haya datos para cada mes
        $months = collect(range(1, 12))->mapWithKeys(function ($i) {
            return [$i => 0];
        });

        $data = $months->merge($orders)->values()->toArray();
        

        return $data;
    }
}
