<?php

namespace App\Filament\Resources\ArticulosResource\RelationManagers;

use App\Models\Lista;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\View\TablesRenderHook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedidasRelationManager extends RelationManager
{
    protected static string $relationship = 'medidas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('identificador')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nombre'),
                
                Select::make('unidad')
                    ->options(Lista::where('tipo', 'Unidad de medida')->pluck('nombre', 'nombre'))
                    ->createOptionForm(function () {
                        return [
                        
                            Forms\Components\TextInput::make('nombre')
                                ->required(),
                            Forms\Components\TextInput::make('definicion')->label('Definición'),
                        ];
                    })
                    ->createOptionUsing(function ($data) {
                        $lista= Lista::create([
                            'nombre' => $data['nombre'],
                            'tipo' => 'Unidad de medida',
                        ]);
                        return $lista->nombre;
                    })
                    ->createOptionAction(function (Action $action) {
                        $action->modalHeading('Crear Unidad de Medida');
                        $action->modalDescription('Crea una nueva unidad de medida que será almacenada en la lista de unidades de medida.');
                        $action->modalWidth('md');
                    })
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('valor'),
                //desde listas
                Select::make('tipo')
                    ->options(Lista::where('tipo', 'Tipo de medida')->pluck('nombre', 'nombre'))
                    ->createOptionForm(function () {
                        return [
                            Forms\Components\TextInput::make('nombre')
                                ->required(),
                            Forms\Components\TextInput::make('definicion')->label('Definición'),
                        ];
                    })
                    ->createOptionUsing(function ($data) {
                        $lista= Lista::create([
                            'nombre' => $data['nombre'],
                            'tipo' => 'Tipo de medida',
                        ]);
                        return $lista->nombre;
                    })
                    ->createOptionAction(function (Action $action) {
                        $action->modalHeading('Crear Tipo de Medida');
                        $action->modalDescription('Crea un nuevo tipo de medida que será almacenada en la lista de tipos de medida.');
                        $action->modalWidth('md');
                    })
                    ->searchable()
                    ->required(),
            ])->columns(2)
            ;
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
