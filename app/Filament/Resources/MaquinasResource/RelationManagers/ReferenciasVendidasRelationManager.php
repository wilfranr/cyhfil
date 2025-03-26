<?php

namespace App\Filament\Resources\MaquinasResource\RelationManagers;

use App\Filament\Resources\ArticulosResource;
use App\Models\Articulo;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Actions\Action;
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('referencia.marca')
            ->with('referencia.articulos');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
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
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('verArticulo')
                    ->label('Ver Artículo')
                    ->url(fn($record) => $record->referencia?->articulos?->first()
                        ? ArticulosResource::getUrl('view', ['record' => $record->referencia->articulos->first()->id])
                        : null),
            ])
            
            ->bulkActions([]);
    }

}