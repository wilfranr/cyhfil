<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'referencia',
        'marca_id',
    ];

    public function articuloReferencia()
    {
        return $this->hasMany(ArticuloReferencia::class, 'referencia_id');
    }

    public function articulos()
{
    return $this->belongsToMany(
        Articulo::class,
        'articulos_referencias',
        'referencia_id',
        'articulo_id'
    );
}


    public function marca()
    {
        return $this->belongsTo(Lista::class, 'marca_id');
    }



    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_referencia', 'referencia_id', 'pedido_id')
            ->withPivot('cantidad');
    }

    public function articuloJuegos()
    {
        return $this->hasMany(ArticuloJuego::class);
    }
}
