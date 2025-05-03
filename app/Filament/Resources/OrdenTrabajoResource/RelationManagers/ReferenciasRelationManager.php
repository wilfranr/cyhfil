<?php

namespace App\Filament\Resources\OrdenTrabajoResource\RelationManagers;

use App\Models\OrdenTrabajoReferencia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ColorColumn;

class ReferenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'referencias'; // Método en el modelo OrdenTrabajo

    protected static ?string $title = 'Referencias Solicitadas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('cantidad')
                    ->disabled(),

                TextInput::make('cantidad_recibida')
                    ->label('Cantidad Recibida')
                    ->numeric()
                    ->minValue(0),

                Select::make('estado')
                ->label('Estado')
                    ->options([
                        '#FF0000' => '🔴 No Recibido',
                        '#E5BE01' => '🟡 Recibido Parcial',
                        '#00913F' => '🟢 Recibido Total',
                    ])
                    ->default('#FF0000')
                    ->required(),

                DatePicker::make('fecha_recepcion')
                    ->label('Fecha de Recepción'),

                Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id') // Podrías mostrar el ID o cualquier campo identificador
            ->columns([
                TextColumn::make('referencia.referencia')
                    ->label('Referencia'),

                TextColumn::make('cantidad')
                    ->label('Solicitado'),

                TextColumn::make('cantidad_recibida')
                    ->label('Recibido'),

                ColorColumn::make('estado')
                    ->label('Estado')
                    ->tooltip(fn (OrdenTrabajoReferencia $record): string => match ($record->estado) {
                    '#FF0000' => '🔴 No recibido',
                        '#E5BE01' => '🟡 Recibido parcialmente',
                        '#00913F' => '🟢 Recibido completo',
                        default => 'Estado desconocido',
                    }),

                TextColumn::make('fecha_recepcion')
                    ->label('Fecha recepción')
                    ->date(),

                TextColumn::make('observaciones')
                    ->label('Observaciones')
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->headerActions([]) // ❌ No permitir agregar desde aquí
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([]);
    }
}

