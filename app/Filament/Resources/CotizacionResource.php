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
    protected static ?int $navigationSort = 1;

    public static  function getNavigationBadge(): ?string
    {
        return Cotizacion::where('estado', 'Enviada')->count();
    }



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
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => match ($record->estado) {
                        'Pendiente' => 'warning',
                        'Aprobada' => 'success',
                        'Rechazada' => 'danger',
                        default => 'primary',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\Action::make('imprimir')
                    ->label('Imprimir')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('pdf.cotizacion', ['id' => $record->id]))
                    ->openUrlInNewTab(),

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
