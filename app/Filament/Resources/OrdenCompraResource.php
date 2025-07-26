<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdenCompraResource\Pages;
use App\Filament\Resources\OrdenCompraResource\RelationManagers;
use App\Models\Direccion;
use App\Models\OrdenCompra;
use App\Models\Pedido;
use App\Models\Referencia;
use App\Models\Tercero;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdenCompraResource extends Resource
{
    protected static ?string $model = OrdenCompra::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 2;

    public static  function getNavigationBadge(): ?string
    {
        return OrdenCompra::where('color', '#FFFF00')->count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Referencias')
                        ->schema([
                            Forms\Components\View::make('filament.orden-compra.referencias')
                                ->label('Referencias')
                                ->visible(fn ($record) => $record && $record->referencias->count() > 0),
                        ]),
                    Forms\Components\Section::make('Observaciones')
                        ->schema([
                            Forms\Components\Textarea::make('observaciones')
                                ->label('') // Sin label para que ocupe todo el espacio
                                ->rows(4)
                                ->placeholder('Añade un comentario u observación a la orden de compra'),
                        ]),
                ])
                ->columnSpan(['lg' => 2]),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Detalles de la Orden')
                        ->schema([
                            Placeholder::make('pedido_id')
                                ->label('Pedido')
                                ->content(fn(OrdenCompra $record): string => '#' . $record->pedido->id),
                            Placeholder::make('proveedor')
                                ->label('Proveedor')
                                ->content(
                                    fn(OrdenCompra $record): string =>
                                    Tercero::find($record->proveedor_id)?->nombre ?? 'N/A'
                                ),
                            Placeholder::make('direccion')
                                ->label('Dirección de Entrega')
                                ->content(fn(OrdenCompra $record): string => Direccion::find($record->direccion)?->direccion ?? 'No disponible'),
                            Forms\Components\DatePicker::make('fecha_expedicion')
                                ->label('Fecha de Expedición')
                                ->required(),
                            Forms\Components\DatePicker::make('fecha_entrega')
                                ->label('Fecha de Entrega')
                                ->required(),
                            Select::make('color')
                                ->label('Estado')
                                ->options([
                                    '#FFFF00' => 'En proceso',
                                    '#00ff00' => 'Entregado',
                                    '#ff0000' => 'Cancelado',
                                ])
                                ->required(),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => 'OC-' . $state),

                Tables\Columns\ColorColumn::make('color')
                    ->label(''),
                Tables\Columns\TextColumn::make('tercero.nombre')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pedido_id')
                    ->label('Pedido')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_expedicion')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_entrega')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),

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
            'index' => Pages\ListOrdenCompras::route('/'),
            'create' => Pages\CreateOrdenCompra::route('/create'),
            'edit' => Pages\EditOrdenCompra::route('/{record}/edit'),
        ];
    }
}

