<?php

namespace App\Imports;

use App\Models\Lista; // Modelo de tu tabla
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ListasImport implements ToModel, WithHeadingRow
{
    /**
     * Procesa cada fila del archivo Excel.
     */
    public function model(array $row)
    {
        // Lista de valores permitidos para "tipo" en mayúsculas
        $allowedTipos = ['TIPO DE MAQUINA', 'DEFINICION DE ARTICULO', 'UNIDAD DE MEDIDA', 'TIPO DE MEDIDA', 'MARCA', 'PIEZA ESTANDAR'];

        return new Lista([
            'tipo' => (isset($row['tipo']) && in_array(mb_strtoupper($row['tipo'], 'UTF-8'), $allowedTipos))
                ? mb_strtoupper($row['tipo'], 'UTF-8')
                : null,
            'nombre' => $row['nombre'] ?? null,
            'definicion' => $row['definicion'] ?? null,
        ]);
    }
}