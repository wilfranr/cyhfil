<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoReferenciaProveedor extends Model
{
    use HasFactory;

    protected $table = 'pedido_referencia_proveedor';

    protected $fillable = ['pedido_id', 'referencia_id', 'proveedor_id', 'marca_id', 'Entrega', 'dias_entrega', 'costo_unidad', 'utilidad','valor_unidad', 'valor_total', 'ubicacion', 'estado', 'cantidad'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function referencia()
    {
        return $this->belongsTo(Referencia::class);
    }

    public function tercero()
    {
        return $this->belongsTo(Tercero::class);
    }


    
}
