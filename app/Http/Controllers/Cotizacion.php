<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Cotizacion as ModelsCotizacion;
use App\Models\Empresa;
use App\Models\Lista;
use App\Models\Maquina;
use App\Models\Pedido;
use App\Models\PedidoReferencia;
use App\Models\PedidoReferenciaProveedor;
use App\Models\Tercero;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class Cotizacion extends Controller
{
    public function generate($id)
    {
        $cotizacion = ModelsCotizacion::findOrFail($id);
        $pedido = Pedido::with('user')->findOrFail($cotizacion->pedido_id);
        $pedidoReferencia = PedidoReferencia::with(['referencia.articuloReferencia.articulo', 'proveedores'])
            ->where('pedido_id', $pedido->id)
            ->get();
        $totalGeneral = $pedidoReferencia
            ->flatMap->proveedores
            ->where('estado', 1)
            ->sum('valor_total');

        $maquina = Maquina::findOrFail($pedido->maquina_id);
        $tipo_maquina = Lista::find($maquina->tipo)?->nombre ?? 'N/A';
        $cliente = Tercero::findOrFail($pedido->tercero_id);
        $ciudad_cliente = City::find($cliente->city_id)?->name ?? 'Sin ciudad';

        $empresaActiva = Empresa::where('estado', true)->first();

        return Pdf::loadView('pdf.cotizacion', [
            'id' => $id,
            'pedido' => $pedido,
            'cliente' => $cliente,
            'vendedor' => $pedido->user,
            'empresas' => Empresa::all(),
            'cotizacion' => $cotizacion,
            'pedidoReferencia' => $pedidoReferencia,
            'tipo_maquina' => $tipo_maquina,
            'maquina' => $maquina,
            'mostrarReferencia' => $pedidoReferencia->first()?->mostrar_referencia ?? 1,
            'ciudad_cliente' => $ciudad_cliente,
            'empresaActiva' => $empresaActiva,
            'totalGeneral' => $totalGeneral,
        ])->stream('COT' . $cotizacion->id . '.pdf');
    }
}