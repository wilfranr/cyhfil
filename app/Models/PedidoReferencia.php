<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoReferencia extends Model
{
    use HasFactory;

    protected $table = 'pedido_referencia';

    protected $fillable = [
        'pedido_id',
        'referencia_id',
        'cantidad',
        'comentario',
        'imagen',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }



    public function referencia()
    {
        return $this->belongsTo(Referencia::class);
    }    
}
