<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PedidosResource;
use App\Models\Pedido;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class UltimosPedidos extends BaseWidget
{
    protected static ?string $heading = 'Últimos Pedidos';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Tables\Table $table): Tables\Table
    {
        $user = Auth::user();
        $rol = $user->roles->first()->name;

        // Definir la consulta según el rol del usuario
        $query = Pedido::query();

        if ($rol === 'Analista') {
            $query = $query->where('estado', 'Nuevo');
        } elseif ($rol === 'Logistica') {
            $query = $query->where('estado', 'Aprobado');
        } elseif ($rol === 'Vendedor') {
            $query = $query->where('user_id', $user->id);
        }

        return $table
            ->query(
                $query->latest('created_at')->take(10) // Obtiene los 10 pedidos más recientes
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
                Tables\Actions\Action::make('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Pedido $record): string => PedidosResource::getUrl('edit', ['record' => $record])),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
