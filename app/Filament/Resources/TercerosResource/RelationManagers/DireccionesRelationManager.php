<?php

namespace App\Filament\Resources\TercerosResource\RelationManagers;

use App\Models\City;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class DireccionesRelationManager extends RelationManager
{
    protected static string $relationship = 'direcciones';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Sección de información de la dirección
                Forms\Components\Section::make('Información de la dirección')
                    ->schema([
                        TextInput::make('destinatario')
                            ->label('Destinatario')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('nit_cc')
                            ->label('NIT/CC')
                            ->required()
                            ->maxLength(50),
                            
                        Select::make('transportadora_id')
                            ->relationship('transportadora', 'nombre')
                            ->label('Transportadora')
                            ->searchable()
                            ->preload()
                            ->required(),
                            
                        TextInput::make('forma_pago')
                            ->label('Forma de pago')
                            ->required()
                            ->maxLength(100),
                            
                        TextInput::make('telefono')
                            ->label('Teléfono')
                            ->required()
                            ->tel()
                            ->maxLength(20),
                            
                        TextInput::make('direccion')
                            ->label('Dirección')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('Dirección física completa'),
                            
                        // Selector de ubicación
                        Select::make('country_id')
                            ->relationship(name: 'country', titleAttribute: 'name')
                            ->label('País')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('state_id', null);
                                $set('city_id', null);
                            })
                            ->required(),
                            
                        Select::make('state_id')
                            ->options(fn(Get $get): Collection => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id'))
                            ->label('Departamento')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('city_id', null);
                            })
                            ->required(),
                            
                        Select::make('city_id')
                            ->options(fn(Get $get): Collection => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name', 'id'))
                            ->label('Ciudad')
                            ->searchable()
                            ->live()
                            ->preload()
                            ->required(),
                            
                        TextInput::make('ciudad_texto')
                            ->label('Ciudad (texto)')
                            ->helperText('Solo llene si la ciudad no está en la lista desplegable')
                            ->maxLength(100),
                            
                        Forms\Components\Toggle::make('principal')
                            ->label('¿Es la dirección principal?')
                            ->default(true)
                            ->reactive()
                            ->afterStateUpdated(function ($state, $get, $set) {
                                if ($state) {
                                    // Si esta dirección está marcada como principal, desmarca las demás
                                    $this->getRelationship()
                                        ->where('id', '!=', $get('id'))
                                        ->update(['principal' => false]);
                                }
                            }),
                    ])
                    ->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('direccion')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('destinatario')
                    ->label('Destinatario')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nit_cc')
                    ->label('NIT/CC')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('transportadora.nombre')
                    ->label('Transportadora')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('forma_pago')
                    ->label('Forma de pago')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->direccion),
                    
                Tables\Columns\TextColumn::make('city.name')
                    ->label('Ciudad')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('state.name')
                    ->label('Depto')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('principal')
                    ->label('Principal')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('principal', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}