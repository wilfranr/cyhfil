<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion as ModelsCotizacion;
use App\Models\Empresa;
use App\Models\Lista;
use App\Models\Maquina;
use App\Models\Marca;
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
        $cotizacion = ModelsCotizacion::where('id', $id)->first();
        // dd($id);
        $pedido_id = ModelsCotizacion::where('id', $id)->pluck('pedido_id')->first();
        // dd($pedido_id);
        $pedido = Pedido::where('id', $pedido_id)->first();
        // dd($pedido);
        $pedidoReferencia = PedidoReferencia::where('pedido_id', $pedido_id)->get();
        // dd($pedidoReferencia);

        foreach ($pedidoReferencia as $value) {
            $pedidoReferenciaProveedor = PedidoReferenciaProveedor::where('pedido_id', $value->id)->get();
        }

        // $referencias = [];
        // foreach ($pedidoReferencia as $value) {
        //     $referencia = Referencia::where('id', $value->referencia_id)->first();
        //     // dd($cantidad);
        //     $referencias[] = $referencia;
        // }

        $maquina = Maquina::where('id', $pedido->maquina_id)->first();
        $tipo_maquina = Lista::where('id', $maquina->tipo)->first();
        // dd($tipo_maquina->nombre);




        $empresas = Empresa::all();
        $tercero_id = $pedido->tercero_id;
        $cliente = Tercero::where('id', $tercero_id)->first();
        $vendedor_id = $pedido->user_id;
        $vendedor = User::where('id', $vendedor_id)->first();
        
        // dd($cliente);
        
        $pdf = PDF::loadView('pdf.cotizacion',['id' => $id,'pedido' => $pedido, 'cliente' => $cliente, 'vendedor' => $vendedor, 'empresas' => $empresas, 'cotizacion' => $cotizacion, 'pedidoReferenciaProveedor' => $pedidoReferenciaProveedor, 'tipo_maquina' => $tipo_maquina->nombre, 'maquina' => $maquina, 'pedidoReferencia' => $pedidoReferencia]);
        return $pdf->download();
    }
}
