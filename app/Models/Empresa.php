<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Empresa - Gestiona la información de las empresas del sistema
 * 
 * Este modelo representa una empresa y contiene toda la información
 * necesaria para la operación del sistema, incluyendo configuración
 * de moneda (TRM) y costos de flete.
 * 
 * @property int $id Identificador único de la empresa
 * @property string $nombre Nombre completo de la empresa
 * @property string $direccion Dirección física de la empresa
 * @property string $telefono Número de teléfono fijo
 * @property string $celular Número de teléfono móvil
 * @property string $email Dirección de correo electrónico
 * @property string|null $logo_light Logo de la empresa (versión clara)
 * @property string|null $logo_dark Logo de la empresa (versión oscura)
 * @property string $nit Número de identificación tributaria
 * @property string $representante Nombre del representante legal
 * @property int $country_id ID del país donde opera la empresa
 * @property int $state_id ID del estado/provincia
 * @property int $city_id ID de la ciudad
 * @property bool $estado Estado activo/inactivo de la empresa
 * @property string $siglas Siglas o abreviatura de la empresa
 * @property float|null $flete Costo del flete por kilogramo (en pesos colombianos)
 * @property float|null $trm Tasa de cambio USD a COP (Tasa Representativa del Mercado)
 * 
 * @property-read Country $country Relación con el país
 * @property-read State $state Relación con el estado/provincia
 * @property-read City $city Relación con la ciudad
 * 
 * @since 1.0.0
 * @author Sistema CYH
 */
class Empresa extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     * 
     * @var array<string>
     */
    protected $fillable = [
        'nombre',        // Nombre completo de la empresa
        'direccion',     // Dirección física
        'telefono',      // Teléfono fijo
        'celular',       // Teléfono móvil
        'email',         // Correo electrónico
        'logo_light',    // Logo versión clara
        'logo_dark',     // Logo versión oscura
        'nit',           // Número de identificación tributaria
        'representante', // Representante legal
        'country_id',    // ID del país
        'state_id',      // ID del estado/provincia
        'city_id',       // ID de la ciudad
        'estado',        // Estado activo/inactivo
        'siglas',        // Siglas de la empresa
        'flete',         // Costo de flete por kg
        'trm',           // Tasa de cambio USD a COP
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'estado' => 'boolean',  // Convierte a boolean (true = activa, false = inactiva)
    ];

    /**
     * Relación con el país donde opera la empresa.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relación con la ciudad donde opera la empresa.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Relación con el estado/provincia donde opera la empresa.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function states()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Método boot para interceptar eventos del modelo.
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Evento saving: Asegura que solo una empresa esté activa a la vez.
         * 
         * Cuando se guarda una empresa con estado = true, automáticamente
         * se desactivan todas las demás empresas del sistema.
         * 
         * @param Empresa $model El modelo que se está guardando
         */
        static::saving(function ($model) {
            if ($model->estado) {
                static::where('id', '!=', $model->id)
                    ->update(['estado' => false]);
            }
        });
    }
}