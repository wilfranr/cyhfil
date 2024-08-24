<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidosResource;
use App\Filament\Resources\CotizacionResource\Pages;
use App\Filament\Resources\CotizacionResource\RelationManagers;
use App\Models\Cotizacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CotizacionResource extends Resource
{
    protected static ?string $model = Cotizacion::class;

    protected static ?string $label = 'Cotizaciones';
    protected static ?string $navigationIcon = 'ri-currency-fill';



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tercero.nombre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pedido_id')
                    ->label('Pedido')
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
                Tables\Columns\TextColumn::make('estado')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => PedidosResource::getUrl('edit', ['record' => $record->pedido_id])),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
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
            'index' => Pages\ListCotizacions::route('/'),
            // 'create' => Pages\CreateCotizacion::route('/create'),
            // 'edit' => Pages\EditCotizacion::route('/{record}/edit'),
        ];
    }
}
