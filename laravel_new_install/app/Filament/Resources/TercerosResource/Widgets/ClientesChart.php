<?php

namespace App\Filament\Resources\TercerosResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Tercero;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientesChart extends ChartWidget
{
    protected static ?string $heading = 'Clientes Creados por Mes';
    protected static ?int $sort = 1;


    protected function getType(): string
    {
        return 'bar'; // Gráfico de barras
    }

    protected function getData(): array
    {
        // Filtra solo los registros de terceros creados en el año actual y agrupa por mes
        $clientes = Tercero::select(DB::raw('MONTH(created_at) as mes'), DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', Carbon::now()->year) // Filtra solo por el año actual
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Inicializa un array para los 12 meses del año con valores en 0
        $data = array_fill(1, 12, 0);

        // Rellena el array con los datos de los clientes creados
        foreach ($clientes as $cliente) {
            $data[$cliente->mes] = $cliente->total;
        }

        return [
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            'datasets' => [
                [
                    'label' => 'Clientes Creados',
                    'data' => array_values($data),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)', // Azul claro con transparencia
                    'borderColor' => 'rgba(54, 162, 235, 1)',       // Azul sin transparencia
                    'borderWidth' => 1,
                ],
            ],
            'options' => [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'stepSize' => 1, // Muestra solo números enteros
                            'callback' => 'function(value) { return Number(value).toFixed(0); }',
                        ],
                    ],
                ],
            ],
        ];
    }
}
