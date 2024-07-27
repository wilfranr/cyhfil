<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'tercero_id',
        'direccion',
        'city_id',
        'state_id',
        'country_id',
        'principal',
    ];
    
    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
}