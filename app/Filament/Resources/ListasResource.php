<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListasResource\Pages;
use App\Filament\Resources\ListasResource\RelationManagers;
use Filament\Tables\Columns\Layout\Stack;
use App\Models\Lista;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ListasResource extends Resource
{
    protected static ?string $model = Lista::class;

    protected static ?string $navigationIcon = 'ri-list-check';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Información de la lista')
                            ->schema([
                                Select::make('tipo')
                                    ->label('Tipo')
                                    ->live()
                                    ->options([
                                        'Marca' => 'Marca',
                                        'Tipo de Máquina' => 'Tipo de Máquina',
                                        'Tipo de Artículo' => 'Tipo de Artículo',
                                        'Unidad de Medida' => 'Unidad de Medida',
                                        'Tipo de Medida' => 'Tipo de Medida',
                                        'Nombre de Medida' => 'Nombre de Medida',
                                    ])
                                    ->placeholder('Seleccione un tipo')
                                    ->searchable()
                                    ->required(),
                                TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    //poner en mayúsculas
                                    ->dehydrateStateUsing(fn(string $state): string => ucwords($state))
                                    ->unique('listas', 'nombre', ignoreRecord: true)
                                    ->placeholder('Nombre de la lista'),
                                MarkdownEditor::make('definicion')
                                    ->label('Definición')
                                    ->placeholder('Definición de la lista'),


                            ]),

                    ]),
                Group::make()
                    ->schema([
                        Section::make('Imagen de la lista')
                            ->schema([
                                FileUpload::make('foto')
                                    ->image()
                                    ->imageEditor()
                                    ->imagePreviewHeight('250')
                                    ->loadingIndicatorPosition('left')
                                    ->label('Foto')
                                    ->panelAspectRatio('2:1')
                                    ->panelLayout('integrated')
                                    ->removeUploadedFileButtonPosition('right')
                                    ->uploadButtonPosition('left')
                                    ->uploadProgressIndicatorPosition('left'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre'),
                Tables\Columns\TextColumn::make('definicion')
                    ->label('Definición')
                    ->wrap()
                    ->searchable()
                    ->visibleFrom('sm'),
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->visibleFrom('sm'),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->options([
                        'Marcas' => 'Marcas',
                        'Tipos de Máquina' => 'Tipo de Máquina',
                        'Tipos de artículo' => 'Tipo de Artículo',
                        'Unidades de medida' => 'Unidad de Medida',
                        'Tipos de medida' => 'Tipo de Medida',
                        'Nombres de medida' => 'Nombre de Medida',
                        'Pieza Estandar' => 'Pieza Estandar',
                    ])
                    ->label('Tipo'),
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
        $recordId = request()->route('record');

        if (!$recordId) {
            return [];
        }

        $record = Lista::find($recordId);

        if (!$record || $record->tipo !== 'Tipo de Artículo') {
            return []; // Si no existe o no es un Tipo de Artículo, no mostramos el Relation Manager
        }
        //
        return [
            RelationManagers\SistemasRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListListas::route('/'),
            'create' => Pages\CreateListas::route('/create'),
            'edit' => Pages\EditListas::route('/{record}/edit'),
        ];
    }
}

