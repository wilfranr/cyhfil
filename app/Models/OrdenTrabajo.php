<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    use HasFactory;

    protected $fillable = [
        'tercero_id',
        'pedido_id',
        'cotizacion_id',
        'estado',
        'fecha_ingreso',
        'fecha_entrega',
        'descripcion',
        'observaciones',
        'direccion',
        'telefono',
        'celular',
        'email',
        'contacto_id',
        'guia',
        'transportadora_id',
        'archivo',
    ];

    public function tercero()
    {
        return $this->belongsTo(Tercero::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function transportadora()
    {
        return $this->belongsTo(Transportadora::class);
    }

}
