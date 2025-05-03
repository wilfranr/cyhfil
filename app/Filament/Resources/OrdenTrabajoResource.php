<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdenTrabajoResource\Pages;
use App\Filament\Resources\OrdenTrabajoResource\RelationManagers;
use App\Models\Direccion;
use App\Models\Transportadora;
use App\Models\OrdenTrabajo;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Collection;
use App\Models\City;
use App\Models\State;


class OrdenTrabajoResource extends Resource
{
    protected static ?string $model = OrdenTrabajo::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 3;


    public static  function getNavigationBadge(): ?string
    {
        return OrdenTrabajo::where('estado', 'Pendiente')->count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('id')
                    ->label('')
                    ->content(fn($record) => $record->id ? "OT-$record->id" : 'N/A')
                    ->columnSpan(2),
                Placeholder::make('cliente')
                    ->label('Cliente')
                    ->content(fn($record) => $record->tercero->nombre ?? 'N/A'),

                Placeholder::make('maquina')
                    ->label('Máquina')
                    ->content(fn($record) => $record->pedido->maquina->modelo ?? 'N/A'),

                Placeholder::make('pedido')
                    ->label('Pedido')
                    ->content(fn($record) => $record->pedido->id ? $record->pedido->id : 'N/A'),

                Placeholder::make('cotizacion')
                    ->label('Cotización')
                    ->content(fn($record) => $record->cotizacion->id ? 'COT-' . $record->cotizacion->id : 'N/A'),

                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'En Proceso' => 'En Proceso',
                        'Completado' => 'Completado',
                        'Cancelado' => 'Cancelado',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('fecha_ingreso')
                    ->label('Fecha de Ingreso')
                    ->required(),

                Forms\Components\DatePicker::make('fecha_entrega')
                    ->label('Fecha de Entrega'),

                Forms\Components\Textarea::make('observaciones')
                    ->label('Observaciones'),

                Select::make('direccion_id')
                    ->label('Dirección')
                    ->options(function ($get) {
                        $terceroId = $get('tercero_id'); // Obtener el tercero_id del formulario actual

                        // Verificar si existe un tercero_id
                        if ($terceroId) {
                            // Filtra las direcciones solo del cliente (tercero)
                            return Direccion::where('tercero_id', $terceroId)
                                ->pluck('direccion', 'id')
                                ->toArray();
                        }

                        // Si no hay cliente asignado aún, retorna un array vacío
                        return [];
                    })
                    ->required()
                    ->preload()
                    ->searchable()
                    ->placeholder('Selecciona una dirección'),




                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('guia')
                    ->label('Guía')
                    ->maxLength(255)
                    ->default(null),

                Forms\Components\Select::make('transportadora_id')
                    ->label('Transportadora')
                    ->relationship('transportadora', 'nombre')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nit')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('telefono')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('direccion')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('country_id')
                            ->relationship(name: 'country', titleAttribute: 'name')
                            ->label('País')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('state_id', null);
                                $set('city_id', null);
                            }),
                        Forms\Components\Select::make('state_id')
                            ->options(fn(Get $get): Collection => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id'))
                            ->label('Departamento')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('city_id', null);
                            }),
                            Forms\Components\Select::make('city_id')
                            ->options(fn(Get $get): Collection => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name', 'id'))
                            ->label('Ciudad')
                            ->searchable()
                            ->live()
                            ->preload(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contacto')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('celular')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('observaciones')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\TextInput::make('logo')
                            ->maxLength(255)
                            ->default(null),
                    ])
                    ->createOptionUsing(function (array $data): Transportadora {
                        return Transportadora::create($data);
                    })
                    ->preload()
                    ->searchable(),

                FileUpload::make('archivo')
                    ->label('Archivo'),

                Placeholder::make('motivo_cancelacion')
                    ->label('Motivo de Cancelación')
                    ->content(fn($record) => $record->motivo_cancelacion ?? 'N/A')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state) => 'OT-' . $state),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pendiente' => 'warning',    // Amarillo
                        'En Proceso' => 'info',      // Azul
                        'Completado' => 'success',   // Verde
                        'Cancelado' => 'danger',     // Rojo
                        default => 'secondary',      // Gris para cualquier otro estado
                    })
                    ->icon(fn(string $state): ?string => match (strtolower(trim($state))) {
                        'pendiente' => 'heroicon-o-clock',
                        'en proceso' => 'ri-refresh-line',
                        'completado' => 'heroicon-o-check-circle',
                        'cancelado' => 'heroicon-o-x-circle',
                        default => null
                    }),

                Tables\Columns\TextColumn::make('tercero.nombre')
                    ->label('Cliente')
                    ->sortable(),

                Tables\Columns\TextColumn::make('transportadora.nombre')
                    ->label('Transportadora')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('referencias')
                    ->label('Referencia')
                    ->getStateUsing(function ($record) {
                        return $record->referencias->map(function ($referencia) {
                            return $referencia->referencia->referencia; // Ajusta según el campo de referencia
                        })->implode(', ');
                    })
                    ->sortable()
                    ->searchable()
                    ->limit(50),


                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Puedes agregar filtros aquí si es necesario
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('print')
                    ->label('Imprimir Guia')
                    ->icon('heroicon-o-printer')
                    ->action(function (OrdenTrabajo $ordenTrabajo) {
                        return redirect()->route('ordenTrabajo.pdf', $ordenTrabajo->id);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }


    public static function getRelations(): array
    {
        return [
           RelationManagers\ReferenciasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrdenTrabajos::route('/'),
            'edit' => Pages\EditOrdenTrabajo::route('/{record}/edit'),
            'view' => Pages\ViewOrdenTrabajo::route('/{record}/view'),

        ];
    }
}
