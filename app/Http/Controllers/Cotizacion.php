<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion as ModelsCotizacion;
use App\Models\Pedido;
use App\Models\PedidoReferencia;
use App\Models\PedidoReferenciaProveedor;
use App\Models\Referencia;
use App\Models\Tercero;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class Cotizacion extends Controller
{
    public function generate($id)
    {
        // dd($id);
        $pedido_id = ModelsCotizacion::where('id', $id)->pluck('pedido_id')->first();
        // dd($pedido_id);
        $pedido = Pedido::where('id', $pedido_id)->first();
        // dd($pedido);
        $pedidoReferencia = PedidoReferencia::where('pedido_id', $pedido_id)->get();
        // dd($pedidoReferencia);

        $pedidoReferenciaProveedor = PedidoReferenciaProveedor::where('pedido_id', $pedido_id)->get();
        // dd($pedidoReferenciaProveedor);

        $referencias = [];
        foreach ($pedidoReferencia as $value) {
            $referencia = Referencia::where('id', $value->referencia_id)->first();
            $referencias[] = $referencia;
        }




        $tercero_id = $pedido->tercero_id;
        $cliente = Tercero::where('id', $tercero_id)->first();
        $vendedor_id = $pedido->user_id;
        $vendedor = User::where('id', $vendedor_id)->first();
        
        // dd($cliente);
        
        $pdf = PDF::loadView('pdf.cotizacion',['id' => $id,'pedido' => $pedido, 'cliente' => $cliente, 'vendedor' => $vendedor, 'referencias' => $referencias]);
        return $pdf->download();
    }
}
