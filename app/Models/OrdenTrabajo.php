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
        'fecha_entrega',//fecha_despacho
        'descripcion',//quitar
        'observaciones',
        'direccion',//traer desde la dirección seleccionada en el pedido
        'telefono',//traer desde la dirección seleccionada en el pedido
        'celular',//Quitar
        'email',//quitar
        'contacto_id',//quitar
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
