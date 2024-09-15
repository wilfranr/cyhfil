<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PedidoReferencia extends Model
{
    use HasFactory;

    protected $table = 'pedido_referencia';

    protected $fillable = [
        'pedido_id',
        'referencia_id',
        'sistema_id',
        'cantidad',
        'comentario',
        'imagen',
        'mostrar_referencia',
        'estado'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }



    public function referencia(): BelongsTo
    {
        return $this->belongsTo(Referencia::class);
    }

    public function referenciasProveedor(): HasMany
    {
        return $this->hasMany(PedidoReferenciaProveedor::class, 'pedido_id', 'id');
    }
    

}
