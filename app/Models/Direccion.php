<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direcciones';

    protected $fillable = [
        'tercero_id',
        'direccion',//placeholder reclama oficina
        'city_id',
        'state_id',
        'country_id',
        'principal',//boton para reclamo oficina, desactivar dirección - default: false
        //transportadora? Foreign key
        //Forma de pago: Al cobro Check
        //Destinatario: Nombre
        //Teléfono: 1234567
        //Documento: 1234567
    ];
    
    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
    
}