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
use App\Models\Lista;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;

class MaquinasResource extends Resource
{
    protected static ?string $model = Maquina::class;

    protected static ?string $navigationIcon = 'heroicon-m-cog';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'modelo';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'modelo',
            'serie',
            'arreglo',
            'marcas.nombre',
        ];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Tipo' => $tipo = Lista::query()->where('id', $record->tipo)->pluck('nombre')->first(),
            'Marca' => $record->marcas->nombre,
            'Modelo' => $record->modelo,
            'Serie' => $record->serie,
            'Arreglo' => $record->arreglo,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Select::make('tipo')
                    ->relationship('Listas', 'nombre')
                    ->createOptionForm(function () {
                        return [
                            TextInput::make('tipo')
                                ->default('Tipo de Máquina')
                                ->readonly()
                                ->required(),
                            TextInput::make('nombre')
                                ->label('Nombre')
                                ->required()
                                ->placeholder('Nombre de la lista'),
                            MarkdownEditor::make('definicion')
                                ->label('Definición')
                                ->required()
                                ->placeholder('Definición de la lista'),
                            FileUpload::make('foto')
                                ->label('Foto')
                                ->image()
                        ];
                    })
                    ->label('Tipo')
                    ->live()
                    ->preload()
                    ->searchable()
                    ->required(),

                Select::make('marca_id')
                    ->relationship('marcas', 'nombre')
                    ->label('Marca')
                    ->preload()
                    ->live()
                    ->searchable()
                    ->createOptionForm(function () {
                        return [
                            TextInput::make('nombre')
                                ->label('Nombre')
                                ->required()
                                ->placeholder('Nombre de la marca'),
                            FileUpload::make('foto')
                                ->label('Foto')
                                ->image()
                        ];
                    }),

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
                    ->label('Foto')
                    ->image()
                    ->imageEditor(),

                Forms\Components\FileUpload::make('fotoId')
                    ->label('FotoId')
                    ->image()
                    ->imageEditor(),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('foto')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('listas.nombre')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('marcas.nombre')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make('modelo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('serie')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('arreglo')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
          
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
