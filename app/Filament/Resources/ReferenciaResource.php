<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferenciaResource\Pages;
use App\Filament\Resources\ReferenciaResource\RelationManagers;
use App\Models\Referencia;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\GlobalSearch\Actions\Action;

class ReferenciaResource extends Resource
{
    protected static ?string $model = Referencia::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'referencia';

    
   

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('referencia')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('articulo_id')
                    ->label('Articulo')
                    ->options(
                        \App\Models\Articulo::all()->pluck('definicion', 'id')->toArray()
                    ),
                    
                Forms\Components\Select::make('marca_id')
                    ->label('Marca')
                    ->options(
                        \App\Models\Marca::all()->pluck('nombre', 'id')->toArray()
                    ),
            ]);
            //modal
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('referencia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('articulo.definicion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('marca.nombre')
                    ->numeric()
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
            'index' => Pages\ListReferencias::route('/'),
            'create' => Pages\CreateReferencia::route('/create'),
            'edit' => Pages\EditReferencia::route('/{record}/edit'),
        ];
    }
}
