<?php

namespace App\Filament\Resources\PedidosResource\Forms;

use App\Models\{Pedido, Contacto};
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Actions\Action;

class InfoSections
{
    public static function getSections(): array
    {
        return [
            Section::make('Información de pedido')
                ->columns(5)
                ->schema([
                    Placeholder::make('numero_pedido')
                        ->content(fn(Pedido $record): string => $record->id)
                        ->hiddenOn('create')
                        ->label('Número de pedido'),
                    Placeholder::make('created')
                        ->content(fn(Pedido $record): string => $record->created_at->toFormattedDateString())
                        ->hiddenOn('create')
                        ->label('Fecha de creación'),
                    Placeholder::make('updated')
                        ->content(fn(Pedido $record): string => $record->updated_at->toFormattedDateString())
                        ->hiddenOn('create')
                        ->label('Fecha de actualización'),
                    Placeholder::make('Vendedor')
                        ->content(fn(Pedido $record): string => $record->user->name)
                        ->hiddenOn('create')
                        ->label('Vendedor'),
                    Placeholder::make('motivo_rechazo')
                        ->content(fn(Pedido $record): string => $record->motivo_rechazo ?? 'Sin motivo de rechazo')
                        ->hiddenOn('create')
                        ->label('Motivo de rechazo')
                        ->visible(fn(Get $get) => $get('estado') === 'Rechazado'),
                    Placeholder::make('comentarios_rechazo')
                        ->content(fn(Pedido $record): string => $record->comentarios_rechazo ?? 'Sin comnetarios de rechazo')
                        ->hiddenOn('create')
                        ->label('Comentario de rechazo')
                        ->visible(fn(Get $get) => $get('estado') === 'Rechazado'),
                    Placeholder::make('estado')
                        ->content(fn(Pedido $record): string => $record->estado)
                        ->hiddenOn('create')
                        ->label('Estado'),
                ])->collapsed()->hiddenOn('create'),

            Section::make('Información de cliente')
                ->columns(4)
                ->schema([
                    Placeholder::make('cliente')
                        ->content(fn(Pedido $record): string => $record->tercero->nombre)
                        ->hiddenOn('create')
                        ->label('Cliente'),
                    Placeholder::make('direccion')
                        ->content(fn(Pedido $record): string => $record->tercero?->direccion ?? 'N/A')
                        ->hiddenOn('create')
                        ->label('Dirección'),
                    Placeholder::make('telefono')
                        ->content(fn(Pedido $record): string => $record->tercero->telefono ?? 'N/A')
                        ->hiddenOn('create')
                        ->label('Telefono'),
                    Placeholder::make('email')
                        ->content(fn(Pedido $record): string => $record->tercero->email ?? 'N/A')
                        ->hiddenOn('create')
                        ->label('Email'),
                    Placeholder::make('contacto')
                        ->content(function (Pedido $record) {
                            $contacto = Contacto::find($record->contacto_id);
                            if ($contacto != null) {
                                return $contacto->nombre;
                            }
                        })
                        ->hiddenOn('create')
                        ->label('Contacto'),
                    Placeholder::make('Teléfono de contacto')
                        ->content(function (Pedido $record) {
                            $contacto = Contacto::find($record->contacto_id);
                            if ($contacto != null) {
                                return $contacto->telefono;
                            }
                        })
                        ->hiddenOn('create')
                        ->label('Teléfono de contacto'),
                    Placeholder::make('email_contacto')
                        ->content(function (Pedido $record) {
                            $contacto = Contacto::find($record->contacto_id);
                            if ($contacto != null) {
                                return $contacto->email;
                            }
                        })
                        ->hiddenOn('create')
                        ->label('Email de contacto'),
                    Placeholder::make('cargo')
                        ->content(function (Pedido $record) {
                            $contacto = Contacto::find($record->contacto_id);
                            if ($contacto != null) {
                                return $contacto->cargo;
                            }
                        })
                        ->hiddenOn('create')
                        ->label('Cargo de contacto'),
                ])
                ->collapsed()
                ->hiddenOn('create')
                ->hidden(function () {
                    $user = Auth::user();
                    if ($user !== null) {
                        $rol = $user->roles->first()->name;
                        return $rol == 'Analista';
                    }
                    return true;
                }),

            Section::make('Información de máquina')
                ->schema([
                    Placeholder::make('maquina')
                        ->key('maquina')
                        ->content(
                            fn(Pedido $record): string => $record->maquina
                                ? "{$record->maquina->listas->nombre} - {$record->maquina->modelo} - {$record->maquina->serie} - {$record->maquina->arreglo} -{$record->maquina->fabricantes->nombre}"
                                : 'Sin máquina asociada'
                        )
                        ->hiddenOn('create')
                        ->label('Máquina')
                        ->hintIcon('heroicon-o-question-mark-circle')
                        ->hintAction(
                            Action::make('info')
                                ->modalHeading('Foto de la máquina')
                                ->modalContent(function (Pedido $record) {
                                    return view('components.maquina-foto', [
                                        'hasImage' => $record->maquina && $record->maquina->foto,
                                        'imageUrl' => $record->maquina && $record->maquina->foto ? Storage::url($record->maquina->foto) : null,
                                        'maquina' => $record->maquina,
                                    ]);
                                })
                                ->modalSubmitAction(false)
                        ),
                ])->hiddenOn('create'),
        ];
    }
}