<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Filament\Resources\ListaResource\RelationManagers\SistemaRelationManager;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lista extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tipo',
        'nombre',
        'definicion',
        'foto',
        'fotoMedida',
        'sistema_id',
    ];
    
    public function sistemas(): BelongsToMany
    {
        return $this->belongsToMany(Sistema::class, 'sistema_lista', 'lista_id', 'sistema_id');
    }
    
    public function getNombreAttribute($value)
    {
        return ucfirst($value); // 🔥 Asegura que siempre tenga la primera letra en mayúscula
    }

    public static function getRecordTitleAttribute(): string
    {
        return 'nombre'; // 🔥 Define la columna a mostrar en Filament
    }
    
}