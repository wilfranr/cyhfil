<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maquina extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo', // 'tipo' is a foreign key to the 'listas' table
        'modelo',
        'marca_id',
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


    // public function getMarcaNombreAttribute()
    // {
    //     $marca = $this->marcas->first();
    //     return $marca ? $marca->nombre : 'N/A';
    // }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function marcas(): BelongsTo
    {
        // Reference to the marcas table
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    

    //relación con listas para traer lo tipos de maquina
    public function listas(): BelongsTo
    {
        // Reference to the listas table
        return $this->belongsTo(Lista::class, 'tipo')->where('tipo', "Tipo de Máquina");
    }
}
