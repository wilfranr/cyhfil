<?php
namespace App\Filament\Resources\OrdenTrabajoResource\Pages;

use App\Filament\Resources\OrdenTrabajoResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\TextColumn; // Ajuste en la importación correcta
use Filament\Forms\Components\Repeater;

class ViewOrdenTrabajo extends ViewRecord
{
    protected static string $resource = OrdenTrabajoResource::class;

    protected function getViewSchema(): array
{
  dd('ViewOrdenTrabajo is being used');
    return [
        TextColumn::make('tercero.nombre')
            ->label('Cliente de prueba')
            ->sortable(),
        TextColumn::make('direccion')
            ->label('Dirección de Entrega')
            ->sortable(),
        TextColumn::make('estado')
            ->label('Estado')
            ->sortable(),
        TextColumn::make('fecha_ingreso')
            ->date()
            ->label('Fecha de Ingreso'),
        TextColumn::make('fecha_entrega')
            ->date()
            ->label('Fecha de Entrega'),
        TextColumn::make('telefono')
            ->label('Teléfono'),
        TextColumn::make('observaciones')
            ->label('Observaciones'),
        // Mostrar las referencias asociadas al pedido
        Repeater::make('referencias')
            ->relationship('referencias')  // Asegúrate de que esta relación esté bien definida en el modelo
            ->label('Referencias del Pedido')
            ->schema([
                TextColumn::make('codigo')
                    ->label('Código de Referencia')
                    ->sortable(),
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->sortable(),
                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->sortable(),
            ])
            ->columns(3) // Organiza en 3 columnas para mejor presentación
            ->defaultItems(0), // Muestra los elementos por defecto
    ];
}

}

