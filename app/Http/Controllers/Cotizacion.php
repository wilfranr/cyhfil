<?php

namespace App\Http\Controllers;

use App\Models\City;
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
        $pedido_id = ModelsCotizacion::where('id', $id)->pluck('pedido_id')->first();
        $mostrarReferencia = PedidoReferencia::where('pedido_id', $pedido_id)->pluck('mostrar_referencia')->first();
        // dd($mostrarReferencia);
        $pedido = Pedido::where('id', $pedido_id)->first();
        
        $pedidoReferencia = PedidoReferencia::where('pedido_id', $pedido_id)->get();
        // dd($pedidoReferencia);
        
        $totalGeneral = 0; // Inicializa el total general a 0
        $pedidoReferenciaProveedor = collect(); // Inicializa una colección vacía para almacenar todos los proveedores
        
        foreach ($pedidoReferencia as $value) {
            $proveedores = PedidoReferenciaProveedor::where('pedido_id', $value->id)->get();
            foreach ($proveedores as $proveedor) {
                $totalGeneral += $proveedor->valor_total; // Suma el valorTotal al total general
                $pedidoReferenciaProveedor->push($proveedor); // Agrega este proveedor a la colección
            }
        }
        
        $maquina = Maquina::where('id', $pedido->maquina_id)->first();
        $tipo_maquina = Lista::where('id', $maquina->tipo)->first();
        $empresas = Empresa::all();
        $tercero_id = $pedido->tercero_id;
        $cliente = Tercero::where('id', $tercero_id)->first();
        $vendedor_id = $pedido->user_id;
        $vendedor = User::where('id', $vendedor_id)->first();
        $ciudad_cliente = City::where('id', $cliente->city_id)->first();
        
        // Pasa $totalGeneral a la vista junto con los otros datos
        $pdf = PDF::loadView('pdf.cotizacion', [
            'id' => $id,
            'pedido' => $pedido,
            'cliente' => $cliente,
            'vendedor' => $vendedor,
            'empresas' => $empresas,
            'cotizacion' => $cotizacion,
            'pedidoReferenciaProveedor' => $pedidoReferenciaProveedor,
            'tipo_maquina' => $tipo_maquina->nombre,
            'maquina' => $maquina,
            'pedidoReferencia' => $pedidoReferencia,
            'mostrarReferencia' => $mostrarReferencia,
            'ciudad_cliente' => $ciudad_cliente->name,
        ]);
        
        // $proveedores = PedidoReferenciaProveedor::where('pedido_id', $pedido_id)->get();
        // dd($proveedores);
        
        return $pdf->download();
    }
}
