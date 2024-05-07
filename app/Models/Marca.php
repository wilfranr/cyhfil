<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = [

        'nombre'
    ];

    public function maquinas()
    {
        return $this->belongsToMany(Maquina::class, 'maquina_marca', 'marca_id', 'maquina_id');
    }

    public function referencias()
    {
        return $this->hasMany(Referencia::class, 'marca_id');
    }
}
