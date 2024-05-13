<?php

namespace App\Filament\Resources\ArticulosResource\RelationManagers;

use App\Models\Lista;
use Filament\Forms;
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
                    ->required(),
                Forms\Components\TextInput::make('valor'),
                //desde listas
                Select::make('tipo')
                    ->options(Lista::where('tipo', 'Tipo de medida')->pluck('nombre', 'nombre'))
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
