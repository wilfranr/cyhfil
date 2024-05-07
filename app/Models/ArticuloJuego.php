<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticuloJuego extends Model
{
    use HasFactory;

    protected $fillable = [
        'articulo_id',
        'juego_id',
        'cantidad',
        'comentario',
    ];

}
