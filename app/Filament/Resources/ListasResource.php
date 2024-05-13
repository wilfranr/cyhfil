<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListasResource\Pages;
use App\Filament\Resources\ListasResource\RelationManagers;
use App\Models\Lista;
use Faker\Core\File;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ListasResource extends Resource
{
    protected static ?string $model = Lista::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'Fabricante' => 'Fabricante',
                        'Tipo de Máquina' => 'Tipo de máquina',
                        'Definición de artículo' => 'Definición de artículo',
                        'Unidad de medida' => 'Unidad de medida',
                        'Tipo de medida' => 'Tipo de medida',
                    ])
                    ->required(),
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->placeholder('Nombre de la lista'),
                MarkdownEditor::make('definicion')
                    ->label('Definición')
                    ->placeholder('Definición de la lista'),
                FileUpload::make('foto')
                    ->label('Foto')
                    ->image()
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
                    ->searchable(),
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto'),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->options([
                        'Fabricante' => 'Fabricante',
                        'Tipo de Máquina' => 'Tipo de máquina',
                        'definición de artículo' => 'Definición de artículo',
                        'unidad de medida' => 'Unidad de medida',
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
        return [
            //
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
