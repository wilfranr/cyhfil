<?php

namespace App\Http\Controllers;

use App\Models\{Articulo, OrdenCompra, Pedido, PedidoReferencia, PedidoReferenciaProveedor, Maquina, Lista, Empresa, Tercero, User, City, Referencia};
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrdenCompraController extends Controller
{
    public function generate($id)
    {
        
        $ordenCompra = OrdenCompra::where('id', $id)->first();
        // dd($ordenCompra);

        // $pedido_id = OrdenCompra::where('id', $id)->pluck('pedido_id')->first();
        // $mostrarReferencia = PedidoReferencia::where('pedido_id', $pedido_id)->pluck('mostrar_referencia')->first();
        // dd($mostrarReferencia);
        // $pedido = Pedido::where('id', $pedido_id)->first();
        
        // $pedidoReferencia = PedidoReferencia::where('pedido_id', $pedido_id)->get();
        // dd($pedidoReferencia);
        
        // $totalGeneral = 0; // Inicializa el total general a 0
        // $pedidoReferenciaProveedor = collect(); // Inicializa una colección vacía para almacenar todos los proveedores
        
        // foreach ($pedidoReferencia as $value) {
        //     $proveedores = PedidoReferenciaProveedor::where('pedido_id', $value->id)->get();
        //     foreach ($proveedores as $proveedor) {
        //         $totalGeneral += $proveedor->valor_total; // Suma el valorTotal al total general
        //         $pedidoReferenciaProveedor->push($proveedor); // Agrega este proveedor a la colección
        //     }
        // }
        
        // $maquina = Maquina::where('id', $pedido->maquina_id)->first();
        // $tipo_maquina = Lista::where('id', $maquina->tipo)->first();
        $empresas = Empresa::all();
        $proveedor = Tercero::where('id', $ordenCompra->proveedor_id)->first();
        $ciudad_proveedor = City::where('id', $proveedor->city_id)->first();
        $articuloId = $ordenCompra->referencia->articulo_id;
        $item = Articulo::where('id', $articuloId)->first()->definicion;
        // $tercero_id = $pedido->tercero_id;
        // $cliente = Tercero::where('id', $tercero_id)->first();
        // $vendedor_id = $pedido->user_id;
        // $vendedor = User::where('id', $vendedor_id)->first();
        // $ciudad_cliente = City::where('id', $cliente->city_id)->first();
        
        // Pasa $totalGeneral a la vista junto con los otros datos
        $pdf = PDF::loadView('pdf.ordenCompra', [
            'id' => $id,
            'ordenCompra' => $ordenCompra,
            // 'cliente' => $cliente,
            // 'vendedor' => $vendedor,
            'empresas' => $empresas,
            'proveedor' => $proveedor,
            'ciudad_proveedor' => $ciudad_proveedor,
            'item' => $item,
        ]);

        return $pdf->stream('ordenCompra.pdf', ['Attachment' => false]);
    
        
        
    }


}
