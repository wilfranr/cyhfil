<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidosResource\Pages;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Models\{Pedido, Tercero, Articulo, Contacto, Maquina, Fabricante, Referencia, Sistema, TRM, PedidoReferenciaProveedor, User, Lista};
use App\Notifications\PedidoCreadoNotification;
use Filament\Notifications\Notification as FilamentNotification;
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

    //Funcion para enviar notificacion de pedido creado
    // public static  function getNavigationBadge(): ?string
    // {
    //     $user = Auth::user();
    //     $rol = $user->roles->first()->name;
    //     if ($rol == 'Logistica') {
    //         return Pedido::query()->where('estado', 'Aprobado')->count();
    //     } else {
    //         return Pedido::query()->where('estado', 'Nuevo')->count();
    //     }
    // }


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
        } elseif($rol == 'Vendedor'){
            return parent::getEloquentQuery()->where('user_id', $user->id);
        }
        else {
            return parent::getEloquentQuery();
        }
    }



    public static function form(Form $form): Form
    {
        //funcion para guardar el usuario logueado
        $user = auth()->user()->id;

        return $form
            ->schema([
                Section::make('Información de pedido')
                    ->columns(4)
                    ->schema([
                        Components\Hidden::make('user_id')->default(auth()->user()->id),
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
                            ->content(fn(Pedido $record): string => $record->motivo_rechazo??'')
                            ->hiddenOn('create')
                            ->label('Motivo de rechazo')
                            ->visible(fn(Get $get) => $get('estado') === 'Rechazado'),
                        Placeholder::make('comentarios_rechazo')
                            ->content(fn(Pedido $record): string => $record->comentarios_rechazo??'')
                            ->hiddenOn('create')
                            ->label('Comentario de rechazo')
                            ->visible(fn(Get $get) => $get('estado') === 'Rechazado'),
                    ])->collapsed()->hiddenOn('create'),

                Section::make('Información de cliente')
                    ->columns(4)
                    ->schema([
                        Placeholder::make('cliente')
                            ->content(fn(Pedido $record): string => $record->tercero->nombre)
                            ->hiddenOn('create')
                            ->label('Cliente'),
                        Placeholder::make('direccion')
                            ->content(fn(Pedido $record): string => $record->tercero->direccion)
                            ->hiddenOn('create')
                            ->label('Dirección'),
                        Placeholder::make('telefono')
                            ->content(fn(Pedido $record): string => $record->tercero->telefono)
                            ->hiddenOn('create')
                            ->label('Telefono'),
                        Placeholder::make('email')
                            ->content(fn(Pedido $record): string => $record->tercero->email)
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
                            ->content(
                                fn(Pedido $record): string => $record->maquina
                                    ? "{$record->maquina->listas->nombre} - {$record->maquina->modelo} - {$record->maquina->serie} - {$record->maquina->fabricantes->nombre}"
                                    : 'Sin máquina asociada'
                            )
                            ->hiddenOn('create')
                            ->label('Máquina')

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
                                    ->live()
                                    ->createOptionForm([
                                        TextInput::make('nombre')
                                            ->label('Nombre')
                                            ->required(),
                                        TextInput::make('cargo')
                                            ->label('Cargo')
                                            ->required(),
                                        TextInput::make('telefono')
                                            ->label('Telefono')
                                            ->required(),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->required(),
                                    ])
                                    ->createOptionUsing(function ($data, $get) {
                                        $terceroId = $get('tercero_id');
                                        $contacto = Contacto::create([
                                            'nombre' => $data['nombre'],
                                            'cargo' => $data['cargo'],
                                            'telefono' => $data['telefono'],
                                            'email' => $data['email'],
                                            'tercero_id' => $terceroId,
                                        ]);
                                        return $contacto->id;
                                    }),

                                Select::make('maquina_id')
                                    ->label('Máquinas')
                                    ->options(function ($get) {
                                        // Obtenemos el 'tercero_id' seleccionado en el formulario
                                        $terceroId = $get('tercero_id');

                                        if ($terceroId) {
                                            // Filtramos las máquinas asociadas al tercero
                                            return Maquina::whereHas('terceros', function ($query) use ($terceroId) {
                                                $query->where('tercero_id', $terceroId);
                                            })
                                                ->get()
                                                ->mapWithKeys(function ($maquina) {
                                                    // Concatenamos tipo, modelo y serie
                                                    $tipo = Lista::find($maquina->tipo)->nombre;  // Obtenemos el nombre del tipo de máquina desde la relación
                                                    $fabricanteNombre = Fabricante::find($maquina->fabricante_id)->nombre;
                                                    return [$maquina->id => "{$tipo} - {$maquina->modelo} - {$maquina->serie} - {$fabricanteNombre}"];
                                                });
                                        }

                                        return [];  // Si no hay 'tercero_id', devolvemos una lista vacía
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required()
                                    ->createOptionForm([
                                        Select::make('tipo')
                                            ->label('Tipo de Máquina')
                                            ->options(function () {
                                                return Lista::where('tipo', 'Tipo de Máquina')
                                                    ->pluck('nombre', 'id');
                                            })
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        Select::make('fabricante_id')
                                            ->label('Fabricante')
                                            ->options(function () {
                                                return Fabricante::pluck('nombre', 'id');
                                            })
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        TextInput::make('modelo')
                                            ->label('Modelo')
                                            ->required(),
                                        TextInput::make('serie')
                                            ->label('Serie')
                                            ->required(),
                                        TextInput::make('arreglo')
                                            ->label('Arreglo'),
                                        FileUpload::make('foto')
                                            ->label('Foto')
                                            ->image(),
                                    ])
                                    ->createOptionUsing(function ($data, $get) {
                                        // Obtenemos el 'tercero_id' directamente desde el formulario principal usando $get
                                        $terceroId = $get('tercero_id');

                                        // Creamos la máquina con los datos proporcionados
                                        $maquina = Maquina::create([
                                            'tipo' => $data['tipo'],  // Guardamos el tipo de máquina como una relación
                                            'fabricante_id' => $data['fabricante_id'],  // Guardamos la marca seleccionada
                                            'modelo' => $data['modelo'],
                                            'serie' => $data['serie'],
                                            'arreglo' => $data['arreglo'],
                                            'foto' => $data['foto'],
                                        ]);

                                        // Si hay un cliente seleccionado, asociamos la máquina con ese cliente
                                        if ($terceroId) {
                                            $maquina->terceros()->attach($terceroId);  // Asociamos la máquina con el cliente
                                        }

                                        return $maquina->id;
                                    })
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state) {
                                            // Obtenemos la máquina seleccionada
                                            $maquina = Maquina::find($state);

                                            if ($maquina) {
                                                // Actualizamos el fabricante_id en el formulario principal
                                                $set('fabricante_id', $maquina->fabricante_id);
                                            }
                                        }
                                    })->visible(fn(Get $get) => $get('tercero_id') !== null),

                                Hidden::make('fabricante_id'),
                            ])->hiddenOn('edit'),

                        // Referencias
                        Step::make('Referencias')
                            ->icon('heroicon-s-clipboard-document-list')
                            ->schema([
                                Repeater::make('referencias')
                                    ->relationship()
                                    ->schema([
                                        // Textinput::make('referencia'),
                                        Select::make('referencia_id')
                                            ->relationship(name: 'referencia', titleAttribute: 'referencia')
                                            ->label('Referencia')
                                            ->options(Referencia::query()->pluck('referencia', 'id'))
                                            ->createOptionForm([
                                                TextInput::make('referencia')
                                                    ->label('Referencia')
                                                    ->unique('referencias', 'referencia', ignoreRecord: true)
                                                    ->required()
                                                    ->maxLength(255),
                                                Select::make('articulo')
                                                    ->label('Artículo')
                                                    ->placeholder('Seleccione un artículo relacionado')
                                                    ->options(function () {
                                                        // Obtener todas las referencias con sus artículos relacionados
                                                        return \App\Models\Referencia::with('articulo')
                                                            ->get()
                                                            ->mapWithKeys(function ($referencia) {
                                                                // Mostrar la referencia y el artículo relacionado
                                                                $articuloDefinicion = $referencia->articulo->definicion ?? 'Sin artículo';
                                                                return [$referencia->id => "{$referencia->referencia} - {$articuloDefinicion}"];
                                                            });
                                                    })
                                                    ->getOptionLabelUsing(function ($value) {
                                                        // Mostrar correctamente la concatenación en edición
                                                        $referencia = \App\Models\Referencia::with('articulo')->find($value);
                                                        if ($referencia) {
                                                            $articuloDefinicion = $referencia->articulo->definicion ?? 'Sin artículo';
                                                            return "{$referencia->referencia} - {$articuloDefinicion}";
                                                        }
                                                        return $value; // Retorna el valor si no se encuentra
                                                    })
                                                    ->reactive() // Detecta cambios en tiempo real
                                                    ->afterStateUpdated(function ($state, $set) {
                                                        if ($state) {
                                                            // Asocia automáticamente el artículo relacionado con la referencia seleccionada
                                                            $referencia = \App\Models\Referencia::find($state);
                                                            if ($referencia) {
                                                                $set('articulo_id', $referencia->articulo_id);
                                                            }
                                                        }
                                                    })
                                                    ->afterStateHydrated(function ($state, $set, $get) {
                                                        // Configura el estado inicial para mostrar la concatenación en edición
                                                        $articuloId = $get('articulo_id'); // Obtener el ID del artículo relacionado
                                                        $referencia = \App\Models\Referencia::where('articulo_id', $articuloId)->with('articulo')->first();

                                                        if ($referencia) {
                                                            $articuloDefinicion = $referencia->articulo->definicion ?? 'Sin artículo';
                                                            $set('articulo', $referencia->id); // Selecciona la referencia correspondiente
                                                        }
                                                    })
                                                    ->searchable()
                                                    ->preload()
                                                    ->live(),
                                                    Hidden::make('articulo_id')->required(),

                                                    Select::make('marca_id')
                                                        ->label('Marca')
                                                        ->options(
                                                            \App\Models\Lista::where('tipo', 'Marca')->pluck('nombre', 'id')->toArray()
                                                        )
                                                        ->createOptionForm(function () {
                                                            return [
                                                                TextInput::make('nombre')
                                                                    ->label('Nombre')
                                                                    ->required()
                                                                    ->placeholder('Nombre del fabricante'),
                                                                FileUpload::make('foto')
                                                                    ->label('Foto')
                                                                    ->image()
                                                            ];
                                                        })
                                                        ->createOptionUsing(function ($data) {
                                                            $marca = Lista::create([
                                                                'tipo' => 'Marca',
                                                                'nombre' => ucwords($data['nombre']),
                                                                'foto' => $data['foto'] ?? null,
                                                            ]);
                                    
                                                            return $marca->id;
                                                        })
                                                        ->searchable()
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
                                                        \App\Models\Lista::where('tipo', 'Marca')->pluck('nombre', 'id')->toArray()
                                                    )
                                                    ->live()
                                                    ->searchable()
                                                    ->preload(),

                                            ])
                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                $referencia = Referencia::find($get('referencia_id'));
                                                if (!$referencia) {
                                                    $set('articulo_definicion', null);
                                                    $set('articulo_descripcionEspecifica', null);
                                                    $set('articulo_id', null);
                                                    $set('peso', null);
                                                    $set('marca_id', null);
                                                } else {
                                                    $articulo = Articulo::find($referencia->articulo_id);
                                                    $marca = Lista::find($referencia->marca_id);
                                                    if (!$articulo) {
                                                        $set('articulo_definicion', null);
                                                        $set('articulo_id', null);
                                                        $set('peso', null);
                                                        return;
                                                    }
                                                    $set('articulo_definicion', $articulo->definicion);
                                                    $set('articulo_descripcionEspecifica', $articulo->descripcionEspecifica);
                                                    $set('articulo_id', $articulo->id);
                                                    $set('peso', $articulo->peso);
                                                    $set('marca_id', $marca->id);
                                                }
                                            })
                                            ->afterStateHydrated(function (Set $set, Get $get) {
                                                $referencia = Referencia::find($get('referencia_id'));

                                                if (!$referencia) {
                                                    $set('articulo_definicion', null);
                                                    $set('articulo_descripcionEspecifica', null);
                                                    $set('articulo_id', null);
                                                    $set('peso', null);
                                                } else {
                                                    $articulo = Articulo::find($referencia->articulo_id);
                                                    $marca = Lista::find($referencia->marca_id);
                                                    if (!$articulo) {
                                                        $set('articulo_definicion', null);
                                                        $set('articulo_descripcionEspecifica', null);
                                                        $set('articulo_id', null);
                                                        $set('peso', null);
                                                        return;
                                                    }
                                                    $set('articulo_definicion', $articulo->definicion);
                                                    $set('articulo_descripcionEspecifica', $articulo->descripcionEspecifica);
                                                    $set('articulo_id', $articulo->id);
                                                    $set('peso', $articulo->peso);
                                                    // $set('marca_id', $marca->id);
                                                    // $set('marca_seleccionada', $marca->id);
                                                    $set('referencia_seleccionada', $referencia->id);
                                                }
                                            })
                                            ->live()
                                            ->searchable()
                                            ->preload('referencia')
                                            ->placeholder('Sin referencia'),
                                        Hidden::make('articulo_id')->disabled(),
                                        TextInput::make('articulo_definicion')->label('Artículo')->disabled(),
                                        TextInput::make('articulo_descripcionEspecifica')->label('Descripción')->disabled(),

                                        TextInput::make('peso')->label('Peso (gr)')->disabled(),
                                        Select::make('sistema_id')
                                            ->label('Sistema')
                                            ->searchable()
                                            ->preload()
                                            ->options(
                                                Sistema::query()->pluck('nombre', 'id')
                                            )
                                            ->createOptionForm([
                                                TextInput::make('nombre')
                                                    ->label('Nombre')
                                                    ->required()
                                                    ->unique(ignoreRecord: true)
                                                    ->placeholder('Nombre del sistema'),
                                                TextInput::make('descripcion')
                                                    ->label('Descripción'),
                                                FileUpload::make('imagen')
                                                    ->label('Imagen')
                                                    ->image()
                                                    ->imageEditor(),
                                            ])
                                            ->createOptionUsing(function ($data) {
                                                return Sistema::create($data)->id;
                                            }),
                                        Select::make('marca_id')
                                            ->label('Marca')
                                            ->options(
                                                Lista::where('tipo', 'Marca')->pluck('nombre', 'id')->toArray()
                                            )
                                            ->preload()
                                            ->searchable(),




                                        TextInput::make('cantidad')->label('Cantidad')->numeric()->minValue(1)->required()->default(1),
                                        TextInput::make('comentario')->label('Comentario'),
                                        FileUpload::make('imagen')->label('Imagen')->image()->imageEditor(),
                                        Toggle::make('mostrar_referencia')
                                            ->label('Mostrar nombre de referencia en cotización')
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
                                        ToggleButtons::make('estado')
                                            ->label('Estado de referencia')
                                            ->options([
                                                1 => 'Activo',
                                                0 => 'Inactivo',
                                            ])
                                            ->colors([
                                                1 => 'success',
                                                0 => 'danger',
                                            ])
                                            ->icons([
                                                1 => 'heroicon-o-check-circle',
                                                0 => 'heroicon-o-x-circle',
                                            ])
                                            ->default(1)
                                            ->inline(),
                                        //Referencias de proveedores
                                        Section::make()
                                            ->schema([
                                                Repeater::make('referenciasProveedor')->label('Proveedores')
                                                    ->relationship()
                                                    ->schema([

                                                        Select::make('proveedor_id')
                                                            ->options(function (Get $get) {
                                                                // Obtén los valores necesarios desde el formulario
                                                                $sistemaId = $get('../../sistema_id');
                                                                $cantidad = $get('../../cantidad');
                                                                $fabricanteId = $get('../../../../fabricante_id'); // Obtén directamente el valor del formulario
                                                                // dd($fabricanteId);

                                                                if (!$fabricanteId || !$sistemaId) {
                                                                    return []; // Devuelve un array vacío si falta algún valor
                                                                }

                                                                // Consulta para obtener los proveedores según los filtros
                                                                return Tercero::query()
                                                                    ->whereHas('fabricantes', function ($query) use ($fabricanteId) {
                                                                        $query->where('fabricante_id', $fabricanteId);
                                                                    })
                                                                    ->whereHas('sistemas', function ($query) use ($sistemaId) {
                                                                        $query->where('sistema_id', $sistemaId);
                                                                    })
                                                                    ->pluck('nombre', 'id');
                                                            })
                                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                                $proveedor = Tercero::find($get('proveedor_id'));
                                                                if (!$proveedor) {
                                                                    // $set('cantidad', null);
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
                                                                $set('cantidad', $get('cantidad'));
                                                            })
                                                            ->live()
                                                            ->reactive()
                                                            ->label('Proveedores')
                                                            ->searchable(),
                                                        TextInput::make('cantidad')
                                                            ->label('Cantidad')
                                                            ->numeric()
                                                            ->required(),
                                                        TextInput::make('ubicacion')
                                                            ->label('Ubicación')
                                                            ->readOnly(),
                                                        Select::make('marca_id')
                                                            ->options(
                                                                Fabricante::query()->pluck('nombre', 'id')->toArray()
                                                            )
                                                            ->label('Fabricante')
                                                            ->searchable(),
                                                        Select::make('Entrega')
                                                            ->options([
                                                                'Inmediata' => 'Inmediata',
                                                                'Programada' => 'Programada',
                                                            ])
                                                            ->live()
                                                            ->required(),
                                                        TextInput::make('dias_entrega')
                                                            ->label('Días de entrega')
                                                            ->default(0)
                                                            ->numeric()
                                                            ->visible(fn(Get $get) => $get('Entrega') === 'Programada'),
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
                                                            ->required()
                                                            ->live()
                                                            ->numeric()
                                                            ->prefix('%')
                                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                                $costo_unidad = $get('costo_unidad');
                                                                $utilidad = $get('utilidad');
                                                                $cantidad = $get('cantidad');
                                                                $trm = TRM::query()->first()->trm;
                                                                if ($get('ubicacion') == 'Internacional') {
                                                                    $costo_total = $costo_unidad * $cantidad;
                                                                    $costo_total = $costo_total * $trm;
                                                                    $costo_total = $costo_total + (($utilidad * $costo_total) / 100);
                                                                    $valor_total = $costo_total;
                                                                    $valor_unidad = $valor_total / $cantidad;
                                                                    $set('valor_total', $costo_total);
                                                                    $set('valor_unidad', $valor_unidad);
                                                                } else {
                                                                    $costo_total = $costo_unidad + (($utilidad * $costo_unidad) / 100);
                                                                    $costo_total = ($costo_unidad + (($utilidad * $costo_unidad) / 100)) * $cantidad;
                                                                    $valor_total = $costo_total;
                                                                    $valor_unidad = $valor_total / $cantidad;
                                                                    $set('valor_total', $costo_total);
                                                                    $set('valor_unidad', $valor_unidad);
                                                                }
                                                            }),
                                                        TextInput::make('valor_unidad')
                                                            ->label('Valor Unidad')
                                                            ->prefix('$')
                                                            ->numeric()
                                                            ->readOnly(),
                                                        TextInput::make('valor_total')
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
                                                            'fabricante_id' => $get('fabricante_id'), // Use relative path to access parent repeater fields
                                                            'sistema_id' => $get('../../sistema_id'),
                                                        ];
                                                    })
                                                    ->extraItemActions([
                                                        Action::make('verProveedor')
                                                            ->tooltip('Ver proveedor')
                                                            ->icon('heroicon-o-eye')
                                                            ->url(function (array $arguments, Repeater $component): ?string {
                                                                // Usamos $component->getRawItemState() para obtener el estado crudo del ítem
                                                                $itemData = $component->getRawItemState($arguments['item']);

                                                                // Obtenemos el proveedor_id del estado
                                                                $proveedorId = $itemData['proveedor_id'] ?? null;

                                                                // Verificamos si proveedor_id existe
                                                                if (! $proveedorId) {
                                                                    return null;
                                                                }

                                                                // Buscamos el proveedor en la base de datos
                                                                $proveedor = Tercero::find($proveedorId);

                                                                // Verificamos si el proveedor fue encontrado
                                                                if (! $proveedor) {
                                                                    return null;
                                                                }

                                                                // Retornamos la ruta para editar el proveedor
                                                                return TercerosResource::getUrl('edit', ['record' => $proveedor->id]);
                                                            }, shouldOpenInNewTab: true)
                                                            ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['proveedor_id'])),
                                                        Action::make('EnviarWhatsapp')
                                                            ->tooltip('Enviar mensaje por whatsapp')
                                                            ->icon('ri-whatsapp-line')
                                                            ->url(function (array $arguments, Repeater $component): ?string {
                                                                // Usamos $component->getRawItemState() para obtener el estado crudo del ítem
                                                                $itemData = $component->getRawItemState($arguments['item']);

                                                                // Obtenemos el proveedor_id del estado
                                                                $proveedorId = $itemData['proveedor_id'] ?? null;

                                                                // Verificamos si proveedor_id existe
                                                                if (! $proveedorId) {
                                                                    return null;
                                                                }

                                                                // Buscamos el proveedor en la base de datos
                                                                $proveedor = Tercero::find($proveedorId);

                                                                // Verificamos si el proveedor fue encontrado
                                                                if (! $proveedor) {
                                                                    return null;
                                                                }

                                                                // Retornamos la ruta para editar el proveedor
                                                                return "https://wa.me/$proveedor->telefono";
                                                            }, shouldOpenInNewTab: true)
                                                    ])
                                                    ->hiddenOn('create')
                                                    ->columns(3)->itemLabel(function (array $state): ?string {
                                                        $proveedor = Tercero::find($state['proveedor_id']);
                                                        return $proveedor ? $proveedor->nombre : null;
                                                    }),
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
                                    ])->columns(3)->itemLabel(function (array $state): ?string {
                                        $referencia = Referencia::find($state['referencia_id']);
                                        return $referencia ? $referencia->referencia : null;
                                    })->collapsed()
                                    ->extraItemActions([
                                        Action::make('openreference')
                                            ->tooltip('Abrir referencia')
                                            ->icon('heroicon-m-arrow-top-right-on-square')
                                            ->url(function (array $arguments, Repeater $component): ?string {
                                                // Usamos $component->getRawItemState() para obtener el estado crudo del ítem
                                                $itemData = $component->getRawItemState($arguments['item']);

                                                // Obtenemos el referencia_id del estado
                                                $referenciaId = $itemData['referencia_id'] ?? null;

                                                // Verificamos si referencia_id existe
                                                if (! $referenciaId) {
                                                    return null;
                                                }

                                                // Buscamos la referencia en la base de datos
                                                $referencia = Referencia::find($referenciaId);

                                                // Verificamos si la referencia fue encontrada
                                                if (! $referencia) {
                                                    return null;
                                                }

                                                // Retornamos la ruta para editar la referencia
                                                return ReferenciaResource::getUrl('edit', ['record' => $referencia->id]);
                                            }, shouldOpenInNewTab: true)
                                            ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['referencia_id'])),
                                    ]),
                            ]),
                        Step::make('Referencias_Masivas')
                            ->icon('heroicon-s-clipboard-document-list')
                            ->schema([
                                Textarea::make('referencias_copiadas')
                                    ->label('Copiar Referencias')
                                    ->helperText('Pega las referencias y cantidades desde Excel en el siguiente formato: REFERENCIA (TAB) CANTIDAD (nueva línea para cada referencia)')
                                    ->placeholder("Ejemplo:\nREF123[TAB]10\nREF456[TAB]5")
                                    ->rows(5)
                                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                        // Procesar las referencias pegadas en formato tabulado desde Excel
                                        $referenciasArray = explode("\n", $state); // Separar por líneas (cada línea es una referencia)

                                        foreach ($referenciasArray as $referencia) {
                                            // Asegurarse de que la referencia contiene tanto el código como la cantidad
                                            $referenciaData = explode("\t", $referencia); // Separar por tabulación (TAB)

                                            if (count($referenciaData) === 2) {
                                                list($codigoReferencia, $cantidad) = $referenciaData;

                                                // Aquí buscamos la referencia por código
                                                $referenciaModel = \App\Models\Referencia::firstOrCreate(
                                                    ['referencia' => trim($codigoReferencia)], // Criterio de búsqueda
                                                    ['referencia' => trim($codigoReferencia)] // Datos para crear si no existe
                                                );

                                                // Agregar la referencia al Repeater de referencias con la cantidad indicada
                                                $set('referencias', array_merge($get('referencias'), [
                                                    [
                                                        'referencia_id' => $referenciaModel->id,
                                                        'cantidad' => (int) $cantidad,
                                                        'comentario' => '',
                                                    ]
                                                ]));
                                            }
                                        }
                                    }),
                            ])->visibleOn('create'),

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
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
            ])
            ->defaultSort('id', 'desc');
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
