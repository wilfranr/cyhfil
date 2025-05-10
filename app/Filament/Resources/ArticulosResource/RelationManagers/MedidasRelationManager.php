<?php

namespace App\Filament\Resources\ArticulosResource\RelationManagers;

use App\Models\Lista;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MedidasRelationManager extends RelationManager
{
    protected static string $relationship = 'medidas';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('articulo');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Mostrar la imagen del artículo
                Group::make()
                    ->schema([
                        View::make('articulos.medida-imagen'),
                        FileUpload::make('imagen')
                            ->image()
                            ->directory('medidas'),
                    ]),
                Group::make()
                    ->schema([
                        // Otros campos del formulario
                        Forms\Components\TextInput::make('identificador')
                            ->required()
                            ->maxLength(255),

                        Select::make('nombre')
                            ->label('Nombre de la medida')
                            ->options(Lista::where('tipo', 'NOMBRE DE MEDIDA')->pluck('nombre', 'nombre')->toArray())
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nombre de la medida'),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return Lista::create([
                                    'nombre' => $data['nombre'],
                                    'tipo' => 'NOMBRE DE MEDIDA',
                                ])->nombre; // ← devolvemos el nombre, no el id
                            }),

                        Select::make('unidad')
                            ->label('Unidad de medida')
                            ->options(Lista::where('tipo', 'Unidad de medida')->pluck('nombre', 'nombre')->toArray())
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Unidad de medida'),
                            ])
                            ->createOptionUsing(function (?string $state) {
                                if (! $state) {
                                    throw new \Exception("El valor de 'unidad' no puede ser nulo.");
                                }

                                Lista::create([
                                    'nombre' => $state,
                                    'tipo' => 'Unidad de medida',
                                ]);

                                return $state;
                            }),

                        Forms\Components\TextInput::make('valor'),

                        Select::make('tipo')
                            ->label('Tipo de medida')
                            ->options(Lista::where('tipo', 'Tipo de medida')->pluck('nombre', 'nombre')->toArray())
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Tipo de medida'),
                            ])
                            ->createOptionUsing(function (array $data) {
                                $registro = Lista::create([
                                    'nombre' => $data['nombre'],
                                    'tipo' => 'Tipo de medida',
                                ]);

                                return $registro->nombre; // Devuelve el string (clave esperada en el Select)
                            }),

                    ]),

                // View::make('articulos.medida-imagen'),

            ])
            ->columns(2); // Divide el formulario en dos columnas
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('identificador')
            ->columns([
                Tables\Columns\TextColumn::make('identificador')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('nombre')->sortable(),
                Tables\Columns\TextColumn::make('unidad')->sortable(),
                Tables\Columns\TextColumn::make('valor')->sortable(),
                Tables\Columns\TextColumn::make('tipo')->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->modal('form')->slideOver()->stickyModalFooter(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')->modal('form')->slideOver(),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
