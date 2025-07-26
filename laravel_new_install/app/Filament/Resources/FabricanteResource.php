<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FabricanteResource\Pages;
use App\Filament\Resources\FabricanteResource\RelationManagers;
use App\Models\Fabricante;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Markdown;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FabricanteResource extends Resource
{
    protected static ?string $model = Fabricante::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?string $label = 'Fabricante';
    protected static ?string $pluralLabel = 'Fabricantes';
    protected static ?string $navigationLabel = 'Fabricantes';




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Fabricante')
                    ->columns(1)
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre')
                            ->unique('fabricantes', 'nombre', ignoreRecord: true)
                            ->dehydrateStateUsing(fn(string $state): string => ucwords($state))
                            ->required(),
                        MarkdownEditor::make('descripcion')
                            ->label('Descripción')
                            ->nullable()
                            ->dehydrateStateUsing(fn(string $state): string => ucwords($state))
                            ->required()
                            ->maxLength(500),
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->imageEditor(),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\ImageColumn::make('logo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            'terceros' => RelationManagers\TercerosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFabricantes::route('/'),
            'create' => Pages\Createfabricante::route('/create'),
            'edit' => Pages\EditFabricante::route('/{record}/edit'),
        ];
    }
}
