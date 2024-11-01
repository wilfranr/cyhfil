<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'celular',
        'email',
        'logo_light',
        'logo_dark',
        'nit',
        'representante',
        'country_id',
        'state_id',
        'city_id',
        'estado',
        'siglas',
    ];

    // Relación con el modelo Country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Relación con el modelo City
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Relación con el modelo State
    public function states()
    {
        return $this->belongsTo(State::class);
    }

    // Método boot para interceptar el evento saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Si el estado del modelo actual es activo, desactiva todos los demás
            if ($model->estado) {
                static::where('id', '!=', $model->id)
                    ->update(['estado' => false]);
            }
        });
    }
}
