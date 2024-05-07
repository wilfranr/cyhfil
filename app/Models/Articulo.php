<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Articulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'definicion',
        'comentarios',
        'descripcionEspecifica',
        'peso',
        'fotoDescriptiva',
        'fotoMedida',
    ];

    public function referencias()
    {
        return $this->hasMany(Referencia::class);
    }

    public function medidas()
    {
        return $this->hasMany(Medida::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    public function articuloJuegos(): HasMany
    {
        return $this->hasMany(ArticuloJuego::class);
    }




}
