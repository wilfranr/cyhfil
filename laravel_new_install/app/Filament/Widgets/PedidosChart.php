<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PedidosChart extends ChartWidget
{
    protected static ?string $heading = 'Pedidos por Mes';
    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $user = Auth::user();
        $rol = $user->roles->first()->name;

        // Inicializa los meses del año en cero para ambos conjuntos de datos
        $months = array_fill(1, 12, 0);

        // Filtrar los pedidos según el rol del usuario
        $query = Pedido::query();
        if ($rol === 'Analista') {
            $query = $query->where('estado', 'Nuevo');
        } elseif ($rol === 'Logistica') {
            $query = $query->where('estado', 'Aprobado');
        } elseif ($rol === 'Vendedor') {
            $query = $query->where('user_id', $user->id);
        }

        // Consulta de todos los pedidos recibidos para el año actual, agrupados por mes
        $totalOrders = $query->select(DB::raw('MONTH(created_at) as mes'), DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Consulta de pedidos entregados para el año actual, agrupados por mes
        $deliveredOrders = $query->select(DB::raw('MONTH(created_at) as mes'), DB::raw('COUNT(*) as total'))
            ->where('estado', 'entregado')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Inicializa los datos para cada mes del año
        $totalOrdersData = $months;
        $deliveredOrdersData = $months;

        // Rellena los datos para todos los pedidos recibidos
        foreach ($totalOrders as $pedido) {
            $totalOrdersData[$pedido->mes] = $pedido->total;
        }

        // Rellena los datos para los pedidos entregados
        foreach ($deliveredOrders as $pedido) {
            $deliveredOrdersData[$pedido->mes] = $pedido->total;
        }

        return [
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            'datasets' => [
                [
                    'label' => 'Pedidos Totales',
                    'data' => array_values($totalOrdersData),
                    'backgroundColor' => 'rgba(255, 193, 7, 0.2)', // Amarillo con transparencia
                    'borderColor' => 'rgba(255, 193, 7, 1)',       // Amarillo sin transparencia
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.1,
                ],
                [
                    'label' => 'Pedidos Entregados',
                    'data' => array_values($deliveredOrdersData),
                    'backgroundColor' => 'rgba(0, 200, 83, 0.2)', // Verde con transparencia
                    'borderColor' => 'rgba(0, 200, 83, 1)',       // Verde sin transparencia
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.1,
                ],
            ],
            'options' => [
                'scales' => [
                    'y' => [
                        'ticks' => [
                            'beginAtZero' => true, // Asegura que el eje Y comience en 0
                            'stepSize' => 1, // Incrementos de 1 en el eje Y
                            'precision' => 0, // Muestra solo números enteros
                        ],
                    ],
                ],
            ],
        ];
    }
}
