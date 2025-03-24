<?php

namespace App\Filament\Resources\MaquinasResource\RelationManagers;

use App\Filament\Resources\ArticulosResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class ReferenciasVendidasRelationManager extends RelationManager
{
    protected static string $relationship = 'ReferenciasVendidas';
    protected static ?string $title = 'Referencias Vendidas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('referencia')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('referencia')
            ->columns([
                TextColumn::make('id')
                ->sortable()
                ->searchable()
                ->label('ID'),
            TextColumn::make('referencia.referencia')
                ->searchable()
                ->sortable()
                ->label('Referencia'),
            TextColumn::make('referencia.marca.nombre')
                ->label('Marca')
                ->sortable()
                ->searchable(),
            TextColumn::make('referencia.articulos.descripcionEspecifica')
                ->label('Artículo Relacionado')
                ->limit(50)
                ->searchable()
                ->sortable()
                ->placeholder('Sin artículo relacionado')
                ->badge(),



            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->label('Creado')
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->label('Actualizado')
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }
}