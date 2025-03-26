<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SistemasResource\Pages;
use App\Filament\Resources\SistemasResource\RelationManagers;
use App\Models\Sistema;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Resources\Resource;
use Filament\Support\Markdown;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SistemasResource extends Resource
{
    protected static ?string $model = Sistema::class;

    protected static ?string $navigationIcon = 'heroicon-c-wrench';

    protected static ?int $navigationSort = 8;

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Información del sistema')
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Nombre del sistema'),
                                MarkdownEditor::make('descripcion')
                                    ->label('Descripción')
                                    ->nullable()
                                    ->dehydrateStateUsing(fn(string $state): string => ucwords($state)),
                            ])->columns('1')->compact(),


                    ])->columnSpan(['lg' => 1]),
                Group::make()
                    ->schema([
                        Section::make('Imagen del sistema')
                            ->schema([
                                FileUpload::make('imagen')
                                    ->image()
                                    ->imageEditor()
                                    ->imagePreviewHeight('250')
                                    ->loadingIndicatorPosition('left')
                                    ->panelAspectRatio('2:1')
                                    ->panelLayout('integrated')
                                    ->removeUploadedFileButtonPosition('right')
                                    ->uploadButtonPosition('left')
                                    ->uploadProgressIndicatorPosition('left')
                                    ,
                            ]),
                    ])->columnSpan(['lg' => 2]),



            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('imagen')
                    ->label('Imagen'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns([
                Components\TextEntry::make('id')
                    ->label('ID'),
                Components\TextEntry::make('nombre')
                    ->label('Nombre'),
                Components\TextEntry::make('descripcion')
                    ->label('Descripción'),
                Components\ImageEntry::make('imagen')
                    ->label('Imagen'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            'listas' => RelationManagers\ListasRelationManager::class,
            'terceros' => RelationManagers\TercerosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSistemas::route('/'),
            'create' => Pages\CreateSistemas::route('/create'),
            'edit' => Pages\EditSistemas::route('/{record}/edit'),
            'view' => Pages\ViewSistemas::route('/{record}'),
        ];
    }
    
}