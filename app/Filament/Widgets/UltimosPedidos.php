<?php

namespace App\Filament\Widgets;

use App\Models\Pedido;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimosPedidos extends BaseWidget
{
    protected static ?string $heading = 'Últimos Pedidos';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                Pedido::query()->latest('created_at')->take(10) // Obtiene los 10 pedidos más recientes
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tercero.nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Nuevo' => 'primary',
                        'En_Costeo' => 'gray',
                        'Cotizado' => 'info',
                        'Aprobado' => 'warning',
                        'Enviado' => 'success',
                        'Entregado' => 'success',
                        'Cancelado' => 'danger',
                        'Rechazado' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'Nuevo' => 'heroicon-o-star',
                        'En_Costeo' => 'heroicon-c-list-bullet',
                        'Cotizado' => 'heroicon-o-currency-dollar',
                        'Aprobado' => 'ri-checkbox-line',
                        'Enviado' => 'heroicon-o-check-circle',
                        'Entregado' => 'heroicon-o-check-circle',
                        'Cancelado' => 'heroicon-o-x-circle',
                        'Rechazado' => 'heroicon-o-x-circle',
                    }),

                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime('d/m/Y H:i')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Ver'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
