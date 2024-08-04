<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidosResource\Pages;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Models\{Pedido, Tercero, Articulo, Contacto, Maquina, Marca, Referencia, Sistema, TRM, PedidoReferenciaProveedor};
use Filament\Forms\{Form, Get, Set};
use Filament\Tables;
use Filament\Tables\{Table, Grouping\Group, Filters\Filter};
use Filament\Forms\Components;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\{Wizard, Wizard\Step, Textarea, ToggleButtons, ViewField, Select, Repeater, FileUpload, Hidden, Placeholder, DatePicker, Button, Actions\Action, Actions, Section, TextInput, Toggle};

class PedidosResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 0;

    public static  function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        // dd($user);
        $rol = $user->roles->first()->name;
        // dd($rol);
        if ($rol == 'Logistica') {
            return Pedido::query()->where('estado', 'Aprobado')->count();
        } else {
            return Pedido::query()->where('estado', 'Nuevo')->count();
        }
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        // dd($user);
        $rol = $user->roles->first()->name;
        // dd($rol);
        if ($rol == 'Analista') {
            return parent::getEloquentQuery()->where('estado', 'Nuevo');
        } elseif ($rol == 'Logistica') {
            return parent::getEloquentQuery()->where('estado', 'Aprobado');
        } else {
            return parent::getEloquentQuery();
        }
    }



    public static function form(Form $form): Form
    {
        //funcion para guardar el usuario logueado
        $user = auth()->user()->id;

        return $form
            ->schema([
                //guardar id de usuario logueado
                Components\Hidden::make('user_id')->default($user),


                Section::make('Información de pedido')
                    ->columns(4)
                    ->schema([
                        Components\Hidden::make('user_id')->default(auth()->user()->id),
                        Placeholder::make('numero_pedido')
                            ->content(fn (Pedido $record): string => $record->id)
                            ->hiddenOn('create')
                            ->label('Número de pedido'),
                        Placeholder::make('created')
                            ->content(fn (Pedido $record): string => $record->created_at->toFormattedDateString())
                            ->hiddenOn('create')
                            ->label('Fecha de creación'),
                        Placeholder::make('updated')
                            ->content(fn (Pedido $record): string => $record->updated_at->toFormattedDateString())
                            ->hiddenOn('create')
                            ->label('Fecha de actualización'),
                        Placeholder::make('Vendedor')
                            ->content(fn (Pedido $record): string => $record->user->name)
                            ->hiddenOn('create')
                            ->label('Vendedor'),
                        Placeholder::make('cliente')
                            ->content(fn (Pedido $record): string => $record->tercero->nombre)
                            ->hiddenOn('create')
                            ->label('Cliente'),
                        Placeholder::make('direccion')
                            ->content(fn (Pedido $record): string => $record->tercero->direccion)
                            ->hiddenOn('create')
                            ->label('Dirección'),
                        Placeholder::make('telefono')
                            ->content(fn (Pedido $record): string => $record->tercero->telefono)
                            ->hiddenOn('create')
                            ->label('Telefono'),
                        Placeholder::make('email')
                            ->content(fn (Pedido $record): string => $record->tercero->email)
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
                        Select::make('maquina_id')
                            ->label('Máquina')
                            ->options(Maquina::all()->mapWithKeys(function ($maquina) {
                                return [$maquina->id => "{$maquina->tipo}, {$maquina->modelo}, {$maquina->serie}"];
                            }))
                            ->searchable()
                            ->live()
                            ->preload('tipo'),
                        Placeholder::make('motivo_rechazo')
                            ->content(fn (Pedido $record): string => $record->motivo_rechazo)
                            ->hiddenOn('create')
                            ->label('Motivo de rechazo')
                            ->visible(fn (Get $get) => $get('estado') === 'Rechazado'),
                        Placeholder::make('comentarios_rechazo')
                            ->content(fn (Pedido $record): string => $record->comentarios_rechazo)
                            ->hiddenOn('create')
                            ->label('Comentario de rechazo')
                            ->visible(fn (Get $get) => $get('estado') === 'Rechazado'),
                    ])->hidden(function () {
                        $user = Auth::user();
                        if ($user != null) {
                            $rol = $user->roles->first()->name;
                            return $rol == 'Analista';
                        }
                    }),
                Wizard::make(
                    [
                        Step::make('Información del cliente')
                            ->icon('heroicon-o-user')
                            ->columns(2)
                            ->schema([

                                Components\Select::make('tercero_id')
                                    ->label('Cliente')
                                    ->relationship('tercero', 'nombre')
                                    ->searchable()
                                    ->searchPrompt('Buscar clientes por nombre')
                                    ->preload()
                                    ->live()
                                    ->createOptionForm([

                                        TextInput::make('nombre')
                                            ->label('Nombre')
                                            ->required(),
                                        Select::make('tipo_documento')
                                            ->label('Tipo Documento')
                                            ->options([
                                                'CC' => 'Cédula de ciudadanía',
                                                'CE' => 'Cédula de extranjería',
                                                'NIT' => 'NIT',
                                                'PAS' => 'Pasaporte',
                                                'RC' => 'Registro civil',
                                                'TI' => 'Tarjeta de identidad',
                                            ])
                                            ->default('CC')
                                            ->required(),
                                        TextInput::make('numero_documento')
                                            ->label('No Documento')
                                            ->required(),
                                        TextInput::make('direccion')
                                            ->label('Dirección')
                                            ->required(),
                                        TextInput::make('telefono')
                                            ->label('Telefono')
                                            ->required(),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->required(),

                                    ])
                                    ->editOptionForm([

                                        TextInput::make('nombre')
                                            ->label('Nombre')
                                            ->required(),
                                        Select::make('tipo_documento')
                                            ->label('Tipo Documento')
                                            ->options([
                                                'CC' => 'Cédula de ciudadanía',
                                                'CE' => 'Cédula de extranjería',
                                                'NIT' => 'NIT',
                                                'PAS' => 'Pasaporte',
                                                'RC' => 'Registro civil',
                                                'TI' => 'Tarjeta de identidad',
                                            ])
                                            ->required(),
                                        TextInput::make('numero_documento')
                                            ->label('No Documento')
                                            ->required(),
                                        TextInput::make('direccion')
                                            ->label('Dirección')

                                            ->required(),
                                        TextInput::make('telefono')
                                            ->label('Telefono')
                                            ->required(),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->required(),

                                    ])
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        // Retrieve the related 'tercero' record
                                        $tercero = Tercero::find($get('tercero_id'));
                                        if (!$tercero) {
                                            $set('documento', null);
                                            $set('direccion', null);
                                            $set('telefono', null);
                                            $set('email', null);
                                            return;
                                        }
                                        // dd($tercero);
                                        // Update the 'documento' field with the related 'tercero' record's 'documento' attribute
                                        $set('documento', $tercero->numero_documento);
                                        $set('direccion', $tercero->direccion);
                                        $set('telefono', $tercero->telefono);
                                        $set('email', $tercero->email);
                                    })

                                    ->required(),

                                TextInput::make('documento')
                                    ->label('No Documento')
                                    ->disabled(),

                                TextInput::make('direccion')
                                    ->label('Dirección')

                                    ->disabled(),
                                TextInput::make('telefono')
                                    ->label('Telefono')
                                    ->disabled(),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->disabled(),
                                Select::make('contacto_id')
                                    ->label('Contacto')
                                    ->options(function (callable $get) {
                                        $terceroId = $get('tercero_id');
                                        if (!is_null($terceroId)) {
                                            return Contacto::where('tercero_id', $terceroId)->pluck('nombre', 'id');
                                        }
                                        return [];
                                    })
                                    ->searchable()
                                    ->searchPrompt('Buscar contactos por nombre')
                                    ->preload()
                                    ->live(),


                                // Select::make('maquina_id')
                                //     ->label('Máquina')
                                //     ->relationship('maquina', 'serie')
                                //     ->live()
                                //     ->preload('tipo'),


                            ])->hiddenOn('edit'),

                        Step::make('Referencias')
                            ->icon('heroicon-s-clipboard-document-list')
                            ->schema([
                                Repeater::make('referencias')
                                    ->relationship()
                                    ->schema([
                                        Select::make('referencia_id')
                                            ->relationship(name: 'referencia', titleAttribute: 'referencia')
                                            ->label('Referencia')
                                            ->options(Referencia::query()->pluck('referencia', 'id'))
                                            ->createOptionForm([
                                                TextInput::make('referencia')
                                                    ->required()
                                                    ->maxLength(255),
                                                Select::make('articulo_id')
                                                    ->label('Articulo')
                                                    ->options(
                                                        \App\Models\Articulo::all()->pluck('definicion', 'id')->toArray()
                                                    ),
                                                Select::make('marca_id')
                                                    ->label('Marca')
                                                    ->options(
                                                        \App\Models\Marca::all()->pluck('nombre', 'id')->toArray()
                                                    ),
                                            ])
                                            ->editOptionForm([
                                                TextInput::make('referencia')
                                                    ->required()
                                                    ->maxLength(255),
                                                Select::make('articulo_id')
                                                    ->label('Articulo')
                                                    ->options(
                                                        \App\Models\Articulo::all()->pluck('definicion', 'id')->toArray()
                                                    )
                                                    ->preload()
                                                    ->live()
                                                    ->searchable(),
                                                Select::make('marca_id')
                                                    ->label('Marca')
                                                    ->options(
                                                        \App\Models\Marca::all()->pluck('nombre', 'id')->toArray()
                                                    )
                                                    ->live()
                                                    ->searchable()
                                                    ->preload(),

                                            ])
                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                $referencia = Referencia::find($get('referencia_id'));
                                                if (!$referencia) {
                                                    $set('articulo_definicion', null);
                                                    $set('articulo_id', null);
                                                    $set('peso', null);
                                                    $set('marca_id', null);
                                                } else {
                                                    $articulo = Articulo::find($referencia->articulo_id);
                                                    $marca = Marca::find($referencia->marca_id);
                                                    if (!$articulo) {
                                                        $set('articulo_definicion', null);
                                                        $set('articulo_id', null);
                                                        $set('peso', null);
                                                        return;
                                                    }
                                                    $set('articulo_definicion', $articulo->definicion);
                                                    $set('articulo_id', $articulo->id);
                                                    $set('peso', $articulo->peso);
                                                    $set('marca_id', $marca->id);
                                                }
                                            })
                                            ->afterStateHydrated(function (Set $set, Get $get) {
                                                $referencia = Referencia::find($get('referencia_id'));

                                                if (!$referencia) {
                                                    $set('articulo_definicion', null);
                                                    $set('articulo_id', null);
                                                    $set('peso', null);
                                                } else {
                                                    $articulo = Articulo::find($referencia->articulo_id);
                                                    $marca = Marca::find($referencia->marca_id);
                                                    if (!$articulo) {
                                                        $set('articulo_definicion', null);
                                                        $set('articulo_id', null);
                                                        $set('peso', null);
                                                        return;
                                                    }
                                                    $set('articulo_definicion', $articulo->definicion);
                                                    $set('articulo_id', $articulo->id);
                                                    $set('peso', $articulo->peso);
                                                    $set('marca_id', $marca->id);
                                                    $set('marca_seleccionada', $marca->id);
                                                    $set('referencia_seleccionada', $referencia->id);
                                                }
                                            })
                                            ->required()
                                            ->live()
                                            ->searchable()
                                            ->preload('referencia'),
                                        Hidden::make('articulo_id')->disabled(),
                                        TextInput::make('articulo_definicion')->label('Artículo')->disabled(),
                                        TextInput::make('peso')->label('Peso')->disabled(),
                                        Select::make('sistema_id')
                                            ->label('Sistema')
                                            ->options(
                                                Sistema::query()->pluck('nombre', 'id')
                                            ),
                                        Select::make('marca_id')
                                            ->label('Marca')
                                            ->options(
                                                Marca::query()->pluck('nombre', 'id')->toArray()
                                            )
                                            ->live(),
                                        TextInput::make('cantidad')->label('Cantidad')->numeric()->minValue(1)->required(),
                                        TextInput::make('comentario')->label('Comentario'),
                                        FileUpload::make('imagen')->label('Imagen')->image()->imageEditor(),
                                        Toggle::make('mostrar_referencia')
                                            ->label('Mostrar referencia en cotización')
                                            ->default(true)
                                            ->hidden(function () {
                                                $user = Auth::user();
                                                if ($user != null) {
                                                    $rol = $user->roles->first()->name;
                                                    if ($rol == 'Analista') {
                                                        return $rol == 'Analista';
                                                    } elseif ($rol == 'Logistica') {
                                                        return $rol == 'Logistica';
                                                    }
                                                }
                                            }),
                                        Section::make()
                                            ->schema([
                                                Repeater::make('referenciasProveedor')->label('Proveedores')
                                                    ->relationship()
                                                    ->schema([

                                                        Select::make('proveedor_id')
                                                            ->options(function (Get $get, $set) {
                                                                $marcaId = $get('../../marca_id'); // Use relative path to access parent repeater fields
                                                                $sistemaId = $get('../../sistema_id');

                                                                $terceros = Tercero::query()
                                                                    ->whereHas('marcas', function ($query) use ($marcaId) {
                                                                        $query->where('marca_id', $marcaId);
                                                                    })
                                                                    ->whereHas('sistemas', function ($query) use ($sistemaId) {
                                                                        $query->where('sistema_id', $sistemaId);
                                                                    })
                                                                    ->pluck('nombre', 'id');

                                                                return $terceros;
                                                            })
                                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                                $proveedor = Tercero::find($get('proveedor_id'));
                                                                if (!$proveedor) {
                                                                    $set('dias_entrega', null);
                                                                    $set('costo_unidad', null);
                                                                    $set('utilidad', null);
                                                                    $set('valor_total', null);
                                                                    return;
                                                                }
                                                                if ($proveedor->country_id == 48) {
                                                                    // dd($proveedor->country_id);
                                                                    $set('ubicacion', 'Nacional');
                                                                } else {
                                                                    $set('ubicacion', 'Internacional');
                                                                }
                                                                $set('dias_entrega', $proveedor->dias_entrega);
                                                                $set('costo_unidad', $proveedor->costo_unidad);
                                                                $set('utilidad', $proveedor->utilidad);
                                                                $set('valor_total', $proveedor->valor_total);
                                                            })
                                                            ->live()
                                                            ->reactive()
                                                            ->label('Proveedores')
                                                            ->searchable(),
                                                        TextInput::make('ubicacion')
                                                            ->label('Ubicación')
                                                            ->readOnly(),

                                                        Select::make('marca_id')
                                                            ->options(
                                                                Marca::query()->pluck('nombre', 'id')->toArray()
                                                            )
                                                            ->label('Marca')
                                                            ->searchable(),
                                                        // TextInput::make('cantidad_proveedor')
                                                        //         ->label('Cantidad')
                                                        //         ->numeric(),
                                                        Select::make('Entrega')
                                                            ->options([
                                                                'Inmediata' => 'Inmediata',
                                                                'Programada' => 'Programada',
                                                            ])
                                                            ->live(),
                                                        TextInput::make('dias_entrega')
                                                            ->label('Días de entrega')
                                                            ->default(0)
                                                            ->numeric()
                                                            ->visible(fn (Get $get) => $get('Entrega') === 'Programada'),
                                                        TextInput::make('costo_unidad')
                                                            ->label('Costo Unidad')
                                                            ->prefix(function (Get $get) {
                                                                if ($get('ubicacion') == 'Internacional')
                                                                    return 'USD $';
                                                                else
                                                                    return 'COP $';
                                                            })
                                                            ->numeric(),
                                                        TextInput::make('utilidad')
                                                            ->label('Utilidad')
                                                            ->reactive()
                                                            ->live()
                                                            ->numeric()
                                                            ->prefix('%')
                                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                                $costo_unidad = $get('costo_unidad');
                                                                $utilidad = $get('utilidad');
                                                                $cantidad = $get('../../cantidad');
                                                                $trm = TRM::query()->first()->trm;
                                                                if ($get('ubicacion') == 'Internacional') {
                                                                    $costo_total = $costo_unidad * $cantidad;
                                                                    $costo_total = $costo_total * $trm;
                                                                    $costo_total = $costo_total + (($utilidad * $costo_total) / 100);
                                                                    $set('valor_total', $costo_total);
                                                                } else {
                                                                    $costo_total = $costo_unidad + (($utilidad * $costo_unidad) / 100);
                                                                    $costo_total = ($costo_unidad + (($utilidad * $costo_unidad) / 100)) * $cantidad;
                                                                    $set('valor_total', $costo_total);
                                                                }
                                                            }),
                                                        TextInput::make('valor_total')
                                                            // ->content(function (Set $set, Get $get) {
                                                            //     $pais = $get('pais');
                                                            //     $costo_unidad = $get('costo_unidad');
                                                            //     $cantidad = $get('../../cantidad');
                                                            //     $peso = $get('../../peso');
                                                            //     $costo_total = $costo_unidad * $cantidad;
                                                            //     $trm = TRM::query()->first()->trm;
                                                            //     if ($pais == 'Internacional'){

                                                            //         $costo_total = $costo_total*$trm;
                                                            //         $utilidad = (($get('utilidad')*$costo_total)/100);
                                                            //         $valor_total = ((($peso * 2.15 + $utilidad) + $costo_total));
                                                            //     }
                                                            //     else {
                                                            //     $utilidad = (($get('utilidad')*$costo_total)/100);
                                                            //     $valor_total = $costo_total+$utilidad;
                                                            //     }

                                                            //     return $valor_total;
                                                            // })
                                                            ->live()
                                                            ->prefix('$')
                                                            ->readOnly()
                                                            ->label('Valor Total'),
                                                        Toggle::make('estado')
                                                            ->label('Seleccionar')
                                                            ->default(true),
                                                    ])

                                                    ->extraAttributes(function (Get $get) {
                                                        return [
                                                            'marca_id' => $get('../../marca_id'), // Use relative path to access parent repeater fields
                                                            'sistema_id' => $get('../../sistema_id'),
                                                        ];
                                                    })
                                                    ->hiddenOn('create')
                                                    ->columns(3),
                                            ])->hidden(function () {
                                                $user = Auth::user();
                                                if ($user != null) {
                                                    $rol = $user->roles->first()->name;
                                                    if ($rol == 'Analista') {
                                                        return $rol == 'Analista';
                                                    } elseif ($rol == 'logistica') {
                                                        return $rol == 'logistica';
                                                    }
                                                }
                                            }),
                                    ])->columns(3)->collapsible(),
                            ])


                    ]
                )->skippable()->columnSpan('full'),
                Section::make('Estado de pedido')
                    ->columns(1)
                    ->schema([
                        ToggleButtons::make('estado')
                            ->options(function () {
                                $user = Auth::user();
                                if ($user != null) {
                                    $rol = $user->roles->first()->name;
                                    if ($rol == 'Analista') {
                                        return [
                                            'Nuevo' => 'Nuevo',
                                            'En_Costeo' => 'En Costeo',
                                        ];
                                    } elseif ($rol == 'Logistica') {
                                        return [
                                            'Aprobado' => 'Aprobado',
                                            'Enviado' => 'Enviado',
                                        ];
                                    } else {
                                        return [
                                            'Nuevo' => 'Nuevo',
                                            'En_Costeo' => 'En Costeo',
                                            'Cotizado' => 'Cotizado',
                                            'Aprobado' => 'Aprobado',
                                            'Enviado' => 'Enviado',
                                            'Entregado' => 'Entregado',
                                            'Cancelado' => 'Cancelado',
                                            'Rechazado' => 'Rechazado',
                                        ];
                                    }
                                }
                            })
                            ->default('Nuevo')
                            ->icons([
                                'Nuevo' => 'heroicon-o-star',
                                'En_Costeo' => 'heroicon-c-list-bullet',
                                'Cotizado' => 'heroicon-o-currency-dollar',
                                'Aprobado' => 'ri-checkbox-line',
                                'Enviado' => 'heroicon-o-truck',
                                'Entregado' => 'heroicon-o-check-circle',
                                'Cancelado' => 'heroicon-o-x-circle',
                                'Rechazado' => 'heroicon-o-x-circle',
                            ])
                            ->required()
                            ->inline(),
                    ])->columnSpan('full'),
            ]);
    }




    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tercero.nombre')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Nuevo' => 'primary',
                        'En_Costeo' => 'gray',
                        'Cotizado' => 'info',
                        'Aprobado' => 'warning',
                        'Enviado' => 'success',
                        'Entregado' => 'success',
                        'Cancelado' => 'danger',
                        'Rechazado' => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Nuevo' => 'heroicon-o-star',
                        'En_Costeo' => 'heroicon-c-list-bullet',
                        'Cotizado' => 'heroicon-o-currency-dollar',
                        'Aprobado' => 'ri-checkbox-line',
                        'Enviado' => 'heroicon-o-check-circle',
                        'Entregado' => 'heroicon-o-check-circle',
                        'Cancelado' => 'heroicon-o-x-circle',
                        'Rechazado' => 'heroicon-o-x-circle',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->searchable()
                    ->sortable(),
            ])
            ->groups([
                Group::make('created_at')
                    ->label('Fecha de creación')
            ])
            ->filters([
                Filter::make('Fecha de creación')
                    ->form([
                        DatePicker::make('created_from')->label('Desde'),
                        DatePicker::make('created_until')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),


            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()->label('Ver'),
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
            // 'referencia' => RelationManagers\ReferenciasRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPedidos::route('/'),
            'create' => Pages\CreatePedidos::route('/create'),
            'edit' => Pages\EditPedidos::route('/{record}/edit'),
        ];
    }
}
