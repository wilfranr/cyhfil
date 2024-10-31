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
        $empresas = Empresa::all();
        $proveedor = Tercero::where('id', $ordenCompra->proveedor_id)->first();
        $ciudad_proveedor = City::where('id', $proveedor->city_id)->first();
        $articuloId = $ordenCompra->referencia->articulo_id;
        $item = Articulo::where('id', $articuloId)->first()->definicion;
        $empresaActiva = Empresa::where('estado', true)->first();

        $pdf = PDF::loadView('pdf.ordenCompra', [
            'id' => $id,
            'ordenCompra' => $ordenCompra,
            'empresas' => $empresas,
            'proveedor' => $proveedor,  
            'ciudad_proveedor' => $ciudad_proveedor,
            'item' => $item,
            'empresaActiva' => $empresaActiva
        ]);
        $fileName = 'OC' . $ordenCompra->id . '.pdf';

        return $pdf->download($fileName);
    
        
        
    }


}
