<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;

class Maquina extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'modelo',
        'marca',
        'serie',
        'arreglo',
        'foto',
        'fotoId'
    ];

    public function terceros(): BelongsToMany
    {
        // Reference to the terceros table
        return $this->belongsToMany(Tercero::class, 'tercero_maquina', 'maquina_id', 'tercero_id');
    } 

    //función para traer los datos concatenados de la maquina

    public function getMaquinaAttribute()
    {
        return "{$this->tipo} {$this->modelo} {$this->marca} {$this->serie} {$this->arreglo}";
    }


    public function getMarcaNombreAttribute()
    {
        $marca = $this->marcas->first();
        return $marca ? $marca->nombre : 'N/A';
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function marcas(): BelongsToMany
    {
        // Reference to the marcas table
        return $this->belongsToMany(Marca::class, 'maquina_marca');
    }
}
