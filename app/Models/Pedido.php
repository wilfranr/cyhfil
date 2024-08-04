<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'user_id',
        'tercero_id',
        'direccion',
        'comentario',
        'contacto_id',
        'maquina_id',
        'estado',
        'motivo_rechazo',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el modelo Tercero
    public function tercero(): BelongsTo
    {
        return $this->belongsTo(Tercero::class);
    }

    //Relación con el modelo maquina
    public function maquina()
    {
        return $this->belongsTo(Maquina::class);
    }

    //relación con referencia
    public function referencias()
    {
        return $this->hasMany(PedidoReferencia::class);
    }

    public function articulos(): HasMany
    {
        return $this->hasMany(PedidoArticulo::class);
    }

    public function referenciasProveedor(): HasMany
    {
        return $this->hasMany(PedidoReferenciaProveedor::class);
    }




}

