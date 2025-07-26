<?php

namespace App\Filament\Resources\TercerosResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaquinasRelationManager extends RelationManager
{
    protected static string $relationship = 'maquinas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tipo')
                    ->required()
                    ->maxLength(255),
                Select::make('marca_id')
                    ->relationship('marcas', 'nombre')
                    ->label('Fabricante')
                    ->preload()
                    ->live()
                    ->searchable(),

                Forms\Components\TextInput::make('modelo')
                    ->label('Modelo')
                    ->required(),

                Forms\Components\TextInput::make('serie')
                    ->label('Serie')
                    ->required(),

                Forms\Components\TextInput::make('arreglo')
                    ->label('Arreglo')
                    ->required(),

                Forms\Components\FileUpload::make('foto')
                    ->label('Foto'),

                Forms\Components\FileUpload::make('fotoId')
                    ->label('FotoId')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tipo')
            ->columns([
                Tables\Columns\TextColumn::make('tipo'),
                Tables\Columns\TextColumn::make('modelo'),
                Tables\Columns\TextColumn::make('serie'),
                Tables\Columns\TextColumn::make('arreglo'),
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto'),
                Tables\Columns\ImageColumn::make('fotoId')
                    ->label('FotoId')

            ])
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
