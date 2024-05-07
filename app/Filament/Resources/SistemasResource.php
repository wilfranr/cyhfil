<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SistemasResource\Pages;
use App\Filament\Resources\SistemasResource\RelationManagers;
use App\Models\Sistema;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SistemasResource extends Resource
{
    protected static ?string $model = Sistema::class;

    protected static ?string $navigationIcon = 'heroicon-c-wrench';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('Nombre del sistema'),
                TextInput::make('descripcion')
                    ->label('Descripción'),
                FileUpload::make('imagen')
                    ->label('Imagen')
                    ->image()
                    ->imageEditor(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
            'index' => Pages\ListSistemas::route('/'),
            'create' => Pages\CreateSistemas::route('/create'),
            'edit' => Pages\EditSistemas::route('/{record}/edit'),
        ];
    }
}
