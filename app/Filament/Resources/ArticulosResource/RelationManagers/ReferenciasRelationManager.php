<?php

namespace App\Filament\Resources\ArticulosResource\RelationManagers;

use App\Models\Referencia;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReferenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'referencias';
    

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('referencia')
                    ->label('Crear Referencia'),
                Select::make('referencia')
                    ->label('Selccionar referencia existente')
                    ->options(Referencia::query()->pluck('referencia', 'id')),
                Select::make('marca_id')
                    ->label('Marca')
                    ->options(fn () => \App\Models\Marca::pluck('nombre', 'id'))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('referencia')
            ->columns([
                Tables\Columns\TextColumn::make('referencia')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('marca.nombre')->label('Marca')->sortable()->searchable(),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Agregar Referencia'),
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
