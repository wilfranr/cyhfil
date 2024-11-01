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
        return $form
            ->schema([
                Placeholder::make('pedido_id')
                    ->label('Pedido')
                    ->content(fn(OrdenCompra $record): string => $record->pedido->id),
                Placeholder::make('proveedor')
                    ->label('Proveedor')
                    ->content(
                        fn(OrdenCompra $record): string =>
                        Tercero::find($record->proveedor_id)?->nombre ?? 'Proveedor no encontrado'
                    ),
                // Placeholder::make('cliente')
                //     ->content(fn(OrdenCompra $record): string => $record->tercero->nombre)
                //     ->label('Cliente'),
                Placeholder::make('referencia')
                    ->label('Referencia')
                    ->content(
                        fn(OrdenCompra $record): string => Referencia::find($record->referencia_id)?->referencia ?? 'Referecnia no encontrada'
                    ),
                Placeholder::make('direccion')
                ->label('Dirección')
                ->content(fn (OrdenCompra $record): string => Direccion::find($record->direccion)?->direccion ?? 'No registra dirección' ),
                // Forms\Components\TextInput::make('cotizaciones_id')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('estado')
                //     ->required()
                //     ->maxLength(255),
                Forms\Components\DatePicker::make('fecha_expedicion')
                    ->required(),
                Forms\Components\DatePicker::make('fecha_entrega')
                    ->required(),
                Forms\Components\Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric(),
                // Forms\Components\TextInput::make('direccion')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('telefono')
                //     ->tel()
                //     ->required()
                //     ->maxLength(255),
                Forms\Components\TextInput::make('valor_unitario')
                    ->required()
                    ->numeric(),
                // Forms\Components\TextInput::make('valor_total')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('valor_iva')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('valor_descuento')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('guia')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\ColorPicker::make('color')
                //     ->required(),
                Select::make('color')
                ->label('Estado')
                ->options([
                    '#FFFF00' => 'En proceso',
                    '#00ff00' => 'Entregado',
                    '#ff0000' => 'Cancelado',
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\ColorColumn::make('color')
                    ->label(''),
                Tables\Columns\TextColumn::make('tercero.nombre')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pedido_id')
                    ->label('Pedido')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('cotizaciones_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('referencia_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('estado')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('fecha_expedicion')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_entrega')
                    ->date()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('cantidad')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('telefono')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('valor_unitario')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('valor_total')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('valor_iva')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('valor_descuento')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('guia')
                //     ->searchable(),

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
