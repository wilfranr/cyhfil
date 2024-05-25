<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Builder;
use App\Models\{Pedido, Tercero, Articulo, Maquina, Marca, Referencia, Sistema, TRM};
use App\Filament\Resources\PedidosResource\Pages;
use Filament\Forms\{Form, Get, Set};
use Filament\Tables;
use Filament\Tables\{Table, Grouping\Group, Filters\Filter};
use Filament\Forms\Components;
use Filament\Resources\Resource;

use Filament\Forms\Components\{Wizard, Wizard\Step, Textarea, ToggleButtons, ViewField, Select, Repeater, FileUpload, Hidden, Placeholder, DatePicker, Button, Actions\Action, Actions, Section, TextInput};


class PedidosResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 0;

    public static  function getNavigationBadge(): ?string
    {
        return Pedido::query()->where('estado', 'Nuevo')->count();
    }



    public static function form(Form $form): Form
    {
        //funcion para guardar el usuario logueado
        $user = auth()->user()->id;

        return $form
            ->schema([
                //guardar id de usuario logueado
                Components\Hidden::make('user_id')->default($user),
                //función para que se muestre solo si estoy editando
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
                        Placeholder::make('estado')
                            ->content(fn (Pedido $record): string => $record->estado)
                            ->hiddenOn('create')
                            ->label('Estado'),
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
                        Select::make('maquina_id')
                            ->label('Máquina')
                            ->options(Maquina::all()->pluck('serie', 'id')),
                        Select::make('estado')
                            ->label('Estado')
                            ->live()
                            ->options([
                                'Nuevo' => 'Nuevo',
                                'En_Costeo' => 'En Costeo',
                                'Cotizado' => 'Cotizado',
                                'Enviado' => 'Enviado',
                                'Entregado' => 'Entregado',
                                'Cancelado' => 'Cancelado',
                                'Rechazado' => 'Rechazado',
                            ])
                            ->required(),


                    ])->hiddenOn('create'),
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

                                Select::make('maquina_id')
                                    ->label('Máquina')
                                    ->relationship('maquina', 'serie')
                                    ->live()
                                    ->preload('tipo'),
                                TextInput::make('trm')
                                    ->label('TRM')
                                    ->numeric(),


                            ])->hiddenOn('edit'),

                        // Step::make('Artículos')
                        //     ->icon('heroicon-m-cube-transparent')
                        //     ->hiddenOn('create')
                        //     ->schema(
                        //         [
                        //             Repeater::make('articulos')
                        //                 ->relationship()
                        //                 ->schema([

                        //                     Select::make('articulo_id')
                        //                         ->label('Artículo')
                        //                         ->options(Articulo::query()->pluck('definicion', 'id'))
                        //                         ->createOptionForm([
                        //                             Select::make('definicion')
                        //                                 ->label('Definición')
                        //                                 ->searchable()
                        //                                 ->options(
                        //                                     Lista::query()
                        //                                         ->where('tipo', 'Definición de artículo')
                        //                                         ->get()
                        //                                         ->mapWithKeys(fn ($definicion) => [$definicion->nombre => $definicion->nombre])
                        //                                         ->toArray()
                        //                                 )
                        //                                 ->required(),
                        //                             TextInput::make('descripcionEspecifica')
                        //                                 ->label('Descripción Específica'),
                        //                             TextInput::make('peso')
                        //                                 ->label('Peso'),
                        //                             FileUpload::make('imagen')
                        //                                 ->label('Imagen')
                        //                                 ->image()
                        //                                 ->imageEditor(),
                        //                         ]),
                        //                     TextInput::make('cantidad')
                        //                         ->label('Cantidad')
                        //                         ->numeric(),
                        //                     TextInput::make('comentario')
                        //                         ->label('Comentario')
                        //                         ->nullable(),
                        //                     Select::make('sistema_id')
                        //                     ->label('Sistema')
                        //                     ->options(
                        //                         Sistema::query()->pluck('nombre', 'id')
                        //                     ),
                        //                     FileUpload::make('imagen')
                        //                         ->label('Imagen')
                        //                         ->image()
                        //                         ->imageEditor()
                        //                         ->nullable(),
                        //                 ])->columns(5),

                        //             Section::make()
                        //                 ->schema([
                        //                     TextArea::make('referencias')
                        //                     ->label('Agregar referencias')
                        //                     ->placeholder('Copie referencias en bloque desde excel')
                        //                     // ->afterStateUpdated(function ($state, $form) {
                        //                     //     // Código para procesar las referencias separadas por salto de línea
                        //                     //     $referencias = explode("\n", $state);

                        //                     //     // Procesar las referencias (guardarlas, validarlas, etc.)
                        //                     //     foreach ($referencias as $referencia) {
                        //                     //         // Buscar la referencia en la base de datos (opcional)
                        //                     //         $referenciaDB = Referencia::find($referencia); // Suponiendo que el valor de la referencia es un ID

                        //                     //         // Obtener el registro actual
                        //                     //         $pedido = $form->getModel();

                        //                     //         // Crear o actualizar la referencia
                        //                     //         $pedidoReferencia = new PedidoReferencia(); // O recupera la instancia existente
                        //                     //         $pedidoReferencia->valor = $referencia; // Asignar el valor de la referencia
                        //                     //         $pedidoReferencia->cantidad = 1; // Asignar la cantidad por defecto (opcional)
                        //                     //         $pedidoReferencia->comentario = ''; // Asignar un comentario vacío por defecto (opcional)

                        //                     //         // Si la referencia no existe en la base de datos, guardarla
                        //                     //         if (!$referenciaDB) {
                        //                     //             $pedidoReferencia->referencia_id = $referenciaDB->id; // Asignar el ID de la referencia
                        //                     //             $pedidoReferencia->save(); // Guardar la nueva referencia
                        //                     //         }

                        //                     //         // Asociar la referencia al registro actual
                        //                     //         $pedido->referencias()->attach($pedidoReferencia->id); // O utilizar sync() para sincronizar las referencias
                        //                     //     }
                        //                     // })
                        //                 ]),
                        //             // Section::make()
                        //             //     ->schema([
                        //             //         Components\TextInput::make('comentario')
                        //             //             ->label('Comentarios del pedido')
                        //             //     ])
                        //         ]
                        //     ),
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
                                        Section::make()
                                            ->schema([
                                                Repeater::make('proveedores')
                                                    ->schema([
                                                        Select::make('proveedores')
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
                                                                $proveedor = Tercero::find($get('proveedores'));
                                                                if (!$proveedor) {
                                                                    $set('dias_entrega', null);
                                                                    $set('costo_unidad', null);
                                                                    $set('utilidad', null);
                                                                    $set('valor_total', null);
                                                                    return;
                                                                }
                                                                if ($proveedor->country_id == 48) {
                                                                    $set('pais', 'Nacional');
                                                                } else {
                                                                    $set('pais', 'Internacional');
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
                                                        TextInput::make('pais')
                                                            ->hidden(),
                                                        Select::make('marca_id')
                                                            ->options(
                                                                Marca::query()->pluck('nombre', 'id')->toArray()
                                                            )
                                                            ->label('Marca')
                                                            ->searchable(),

                                                        TextInput::make('dias_entrega')
                                                            ->label('Días de entrega')
                                                            ->numeric(),
                                                        TextInput::make('costo_unidad')
                                                            ->label('Costo Unidad')
                                                            ->prefix(function (Get $get) {
                                                                if ($get('pais') == 'Nacional')
                                                                    return 'COP $';
                                                                else
                                                                    return 'USD $';
                                                            })
                                                            ->numeric(),
                                                        TextInput::make('utilidad')
                                                            ->label('Utilidad')
                                                            ->reactive()
                                                            ->live()
                                                            ->numeric(),
                                                        Placeholder::make('valor_total')
                                                            ->content(function (Get $get) {
                                                                $costo_unidad = $get('costo_unidad');
                                                                $cantidad = $get('../../cantidad');
                                                                $peso = $get('../../peso');
                                                                // $utilidad = $get('utilidad');
                                                                $trm = TRM::query()->first()->trm;
                                                                $valor_total = ($peso * 2.15 + $costo_unidad * $trm)*$cantidad;
                                                                return $valor_total;
                                                            })
                                                            ->label('Valor Total'),
                                                        // TextInput::make('valor_total')
                                                        //     ->label('Valor Total')
                                                        //     ->numeric()
                                                        //     ->inputMode('decimal'),
                                                        TextInput::make('trm')
                                                            ->label('TRM')
                                                            ->default(TRM::query()->first()->trm)
                                                            ->hidden()
                                                            ->numeric(),
                                                    ])

                                                    ->extraAttributes(function (Get $get) {
                                                        return [
                                                            'marca_id' => $get('../../marca_id'), // Use relative path to access parent repeater fields
                                                            'sistema_id' => $get('../../sistema_id'),
                                                        ];
                                                    })
                                                    ->hiddenOn('create')
                                                    ->columns(3),
                                            ])
                                    ])->columns(3)->collapsible(),
                            ])


                    ]
                )->skippable()->columnSpan('full'),
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
                        'En proceso' => 'warning',
                        'Enviado' => 'success',
                        'Entregado' => 'success',
                        'Cancelado' => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Nuevo' => 'heroicon-o-star',
                        'En_Costeo' => 'heroicon-c-list-bullet',
                        'Cotizado' => 'heroicon-o-currency-dollar',
                        'En proceso' => 'heroicon-o-clock',
                        'Enviado' => 'heroicon-o-check-circle',
                        'Entregado' => 'heroicon-o-check-circle',
                        'Cancelado' => 'heroicon-o-x-circle',
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
                Tables\Actions\EditAction::make(),
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
