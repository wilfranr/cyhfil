<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaquinasResource\Pages;
use App\Filament\Resources\MaquinasResource\Pages\EditMaquinas;
use App\Filament\Resources\MaquinasResource\RelationManagers;
use App\Models\Maquina;
use App\Models\Marca;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MaquinasResource\RelationManagers\MarcaRelationManager;


class MaquinasResource extends Resource
{
    protected static ?string $model = Maquina::class;

    protected static ?string $navigationIcon = 'heroicon-m-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipo')
                    ->options([
                        'excavadora' => 'Excavadora',
                        'retroexcavadora' => 'Retroexcavadora',
                        'bulldozer' => 'Bulldozer',
                        'grua' => 'Grua',
                        'montacargas' => 'Montacargas',
                        'compactador' => 'Compactador',
                        'motoniveladora' => 'Motoniveladora',
                        'rodillo' => 'Rodillo',
                        'tractor' => 'Tractor',
                        'camion' => 'Camion',
                        'volqueta' => 'Volqueta',
                        'otro' => 'Otro',
                    ])
                    ->label('Tipo')
                    ->searchable()
                    ->required(),

                Select::make('marca_id')
                    ->relationship('marcas', 'nombre')
                    ->label('Marca')
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
    


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable()
                    ->sortable(),

                //obtener la relacion de la tabla pivot maquina_marca
                Tables\Columns\TextColumn::make('marca_nombre')
                    ->searchable()
                    ->label('Marca'),


                Tables\Columns\TextColumn::make('modelo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('serie')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('arreglo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('foto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('fotoId')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        //obtener la relacion de la tabla pivot maquina_marca
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaquinas::route('/'),
            'create' => Pages\CreateMaquinas::route('/create'),
            'edit' => Pages\EditMaquinas::route('/{record}/edit'),
        ];
    }
}
