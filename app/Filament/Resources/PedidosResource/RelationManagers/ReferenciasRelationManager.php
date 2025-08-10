<?php

namespace App\Filament\Resources\PedidosResource\RelationManagers;

use App\Models\PedidoReferencia;
use App\Models\Referencia;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
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
                TextInput::make('cantidad')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->label('Cantidad')
                    ->columnSpan(3),
                TextInput::make('referencia_id')
                    ->label('Referencia')
                    ->required()
                    ->columnSpan(6),
                TextInput::make('comentario')
                    ->label('Comentario')
                    ->columnSpan(12),
                FileUpload::make('imagen')
                    ->image()
                    ->label('Imagen')
                    ->columnSpan(12),
            ])
            ->columns(12);
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('referencia')
            ->columns([
                Tables\Columns\TextColumn::make('referencia.referencia'),
                Tables\Columns\TextColumn::make('cantidad'),
                Tables\Columns\TextColumn::make('comentario'),
                Tables\Columns\ImageColumn::make('imagen'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Agregar referencia'),
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
