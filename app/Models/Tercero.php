<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Maquina;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tercero extends Model
{
    protected $fillable = [
        'nombre',
        'tipo_documento',
        'numero_documento',
        'direccion',
        'telefono',
        'email',
        'dv',
        'estado',
        'forma_pago',
        'email_factura_electronica',
        'rut',
        'certificacion_bancaria',
        'camara_comercio',
        'cedula_representante_legal',
        'sitio_web',
        'puntos',
        'tipo',
        'country_id',
        'state_id',
        'city_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function states()
    {
        return $this->belongsTo(State::class);
    }

    public function maquinas()
    {
        return $this->belongsToMany(Maquina::class, 'tercero_maquina', 'tercero_id', 'maquina_id');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class, 'tercero_id');
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'tercero_id');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function sistemas(): BelongsToMany
    {
        return $this->belongsToMany(Sistema::class, 'tercero_sistemas', 'tercero_id', 'sistema_id');
    }


    public function marcas(): BelongsToMany
    {
        return $this->belongsToMany(Marca::class, 'tercero_marcas', 'tercero_id', 'marca_id');
    }

    public function getProveedoresPorMarca($marcaId)
    {
        return $this->whereHasMany('marcas', function ($query) use ($marcaId) {
                $query->where('marca_id', $marcaId);
            })
            ->select('nombre', 'id')
            ->pluck('nombre', 'id');
    }
}
