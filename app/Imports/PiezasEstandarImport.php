<?php

namespace App\Imports;

use App\Models\Lista;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PiezasEstandarImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Lista([
            'tipo' => 'PIEZA ESTANDAR', // Tipo fijo para piezas estándar
            'nombre' => $row['nombre'] ?? null,
            'definicion' => $row['definicion'] ?? null,
        ]);
    }
}
