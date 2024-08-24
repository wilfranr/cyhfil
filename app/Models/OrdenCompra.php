<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;

    protected $fillable = [
        'tercero_id',
        'pedido_id',
        'cotizacion_id',
        'proveedor_id',
        'estado',
        'referencia_id',
        'pedido_referencia_id',
        'fecha_expedicion',
        'fecha_entrega',
        'observaciones',
        'cantidad',
        'direccion',
        'telefono',
        'valor_unitario',
        'valor_total',
        'valor_iva',
        'valor_descuento',
        'guia',
        'color',
    ];

    public function tercero()
    {
        return $this->belongsTo(Tercero::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function referencia()
    {
        return $this->belongsTo(Referencia::class);
    }

    public function pedidoReferencia()
    {
        return $this->belongsTo(PedidoReferencia::class);
    }


}
