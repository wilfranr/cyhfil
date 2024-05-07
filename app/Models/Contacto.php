<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $fillable = [
        'tercero_id',
        'nombre',
        'cargo',
        'telefono',
        'email',
        'principal',
    ];

    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }
}
