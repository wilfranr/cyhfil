<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PedidoController extends Controller
{
    /**
     * Obtener los proveedores que tienen referencias en un pedido específico
     */
    public function getProveedores(Pedido $pedido): JsonResponse
    {
        try {
            $proveedores = $pedido->referencias()
                ->with(['proveedores.tercero' => function ($query) {
                    $query->where('tipo', 'Proveedor')
                          ->orWhere('tipo', 'Ambos');
                }])
                ->get()
                ->flatMap(function ($referencia) {
                    return $referencia->proveedores->map(function ($proveedor) {
                        return [
                            'id' => $proveedor->tercero->id,
                            'nombre' => $proveedor->tercero->nombre,
                        ];
                    });
                })
                ->unique('id')
                ->values()
                ->toArray();

            return response()->json($proveedores);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener proveedores',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
