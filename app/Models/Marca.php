<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = [

        'nombre',
        'logo'

    ];



    public function referencias() : HasMany
    {
        return $this->hasMany(Referencia::class, 'marca_id');
    }
}
