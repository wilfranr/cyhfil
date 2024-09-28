<?php

namespace App\Filament\Logistica\Resources;

use App\Filament\Logistica\Resources\OrdenTrabajoResource\Pages;
use App\Filament\Logistica\Resources\OrdenTrabajoResource\RelationManagers;
use App\Models\City;
use App\Models\Direccion;
use App\Models\OrdenTrabajo;
use App\Models\State;
use App\Models\Transportadora;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdenTrabajoResource extends Resource
{
    protected static ?string $model = OrdenTrabajo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        // Puedes condicionar esto según el rol del usuario o cualquier otra lógica
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('id')
                    ->label('')
                    ->content(fn($record) => $record->id ? "OT-$record->id" : 'N/A')
                    ->columnSpan(2),
                Placeholder::make('cliente')
                    ->label('Cliente')
                    ->content(fn($record) => $record->tercero->nombre ?? 'N/A'),

                Placeholder::make('maquina')
                    ->label('Máquina')
                    ->content(fn($record) => $record->pedido->maquina->modelo ?? 'N/A'),

                Placeholder::make('pedido')
                    ->label('Pedido')
                    ->content(fn($record) => $record->pedido->id ? $record->pedido->id : 'N/A'),

                Placeholder::make('cotizacion')
                    ->label('Cotización')
                    ->content(fn($record) => $record->cotizacion->id ? 'COT-' . $record->cotizacion->id : 'N/A'),

                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'En Proceso' => 'En Proceso',
                        'Completado' => 'Completado',
                        'Cancelado' => 'Cancelado',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('fecha_ingreso')
                    ->label('Fecha de Ingreso')
                    ->required(),

                Forms\Components\DatePicker::make('fecha_entrega')
                    ->label('Fecha de Entrega'),

                Forms\Components\Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('direccion')
                    ->label('Dirección')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('guia')
                    ->label('Guía')
                    ->maxLength(255)
                    ->default(null),

                Forms\Components\Select::make('transportadora_id')
                    ->label('Transportadora')
                    ->relationship('transportadora', 'nombre')
                    ->required()
                    ->preload()
                    ->searchable(),

                FileUpload::make('archivo')
                    ->label('Archivo'),

                Forms\Components\Placeholder::make('referencias_text')
                    ->label('Referencias')
                    ->content(function ($record) {
                        return $record->referencias->map(function ($referencia) {
                            return 'Referencia: ' . $referencia->referencia->referencia . ' - Cantidad: ' . $referencia->cantidad;
                        })->implode("\n"); 
                    })
                    ->extraAttributes(['style' => 'white-space: pre-line;']),  
                    





            ]);
    }








    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pendiente' => 'warning',    // Amarillo
                        'En Proceso' => 'info',      // Azul
                        'Completado' => 'success',   // Verde
                        'Cancelado' => 'danger',     // Rojo
                        default => 'secondary',      // Gris para cualquier otro estado
                    })
                    ->icon(fn(string $state): ?string => match (strtolower(trim($state))) {
                        'pendiente' => 'heroicon-o-clock',
                        'en proceso' => 'heroicon-o-refresh',
                        'completado' => 'heroicon-o-check-circle',
                        'cancelado' => 'heroicon-o-x-circle',
                        default => null
                    }),

                Tables\Columns\TextColumn::make('tercero.nombre')
                    ->label('Cliente')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pedido_id')
                    ->label('Pedido')
                    ->sortable(),

                Tables\Columns\TextColumn::make('cotizacion_id')
                    ->label('Cotización')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_ingreso')
                    ->label('Fecha de Ingreso')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_entrega')
                    ->label('Fecha de Entrega')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable(),

                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),

                Tables\Columns\TextColumn::make('transportadora.nombre')
                    ->label('Transportadora')
                    ->sortable(),

                // Columna para mostrar las referencias asociadas al pedido
                Tables\Columns\TextColumn::make('referencias.referencia')
                    ->label('Referencias')
                    ->sortable()
                    ->searchable()
                    ->limit(50), // Limita la longitud del texto

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Puedes agregar filtros aquí si es necesario
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrdenTrabajos::route('/'),
            'create' => Pages\CreateOrdenTrabajo::route('/create'),
            'edit' => Pages\EditOrdenTrabajo::route('/{record}/edit'),
            'view' => Pages\ViewOrdenTrabajo::route('/{record}/view'),

        ];
    }
}
