<?php

namespace App\Filament\Resources\ArticulosResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
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
                    ->options([
                        'mm',
                        'cm',
                        'm',
                        'km',
                        'm3',
                        'cm2',
                        'm2',
                        'Otro'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('valor'),
                //desde listas
                Select::make('tipo')
                    ->options([
                        'Largo',
                        'Ancho',
                        'Alto',
                        'Diámetro',
                        'Volumen',
                        'Área',
                        'Otro'
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\FileUpload::make('imagen'),
                
            ])->columns(2)
            ;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('identificador')
            ->columns([
                Tables\Columns\ImageColumn::make('imagen'),
                Tables\Columns\TextColumn::make('identificador')->searchable(),
                Tables\Columns\TextColumn::make('nombre')->searchable(),
                Tables\Columns\TextColumn::make('unidad')->searchable(),
                Tables\Columns\TextColumn::make('valor')->searchable(),
                Tables\Columns\TextColumn::make('tipo')->searchable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
