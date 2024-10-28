<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = [

        'nombre',
        'logo'

    ];



    public function referencias(): HasMany
    {
        return $this->hasMany(Referencia::class, 'marca_id');
    }

    public function maquinas(): HasMany
    {
        return $this->hasMany(Maquina::class, 'marca_id');
    }

    public function terceros(): BelongsToMany
    {
        return $this->belongsToMany(Tercero::class, 'tercero_marcas', 'marca_id', 'tercero_id');
    }
}
