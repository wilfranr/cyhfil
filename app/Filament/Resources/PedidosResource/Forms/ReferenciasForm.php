<?php

namespace App\Filament\Resources\PedidosResource\Forms;

use App\Models\{Articulo, Lista, Pedido, Referencia, Sistema, Tercero, Empresa, Fabricante};
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferenciasForm
{
    public static function getReferenciasRepeater(): Repeater
    {
        return Repeater::make("referencias")
            ->relationship()
            ->schema(self::getReferenciasSchema())
            ->columns(12)
            ->columnSpanFull()
            ->collapsible()
            ->itemLabel(
                fn(array $state): ?string => $state["referencia_id"]
                    ? Referencia::find($state["referencia_id"])->referencia
                    : null,
            )
            ->defaultItems(1);
    }

    public static function getStep(): Step
    {
        return Step::make("Referencias")
            ->icon("heroicon-s-clipboard-document-list")
            ->schema([self::getReferenciasRepeater()])
            ->columns(12);
    }

    public static function getBulkStep(): Step
    {
        return Step::make('Referencias Masivas')
            ->icon('heroicon-s-clipboard-document-list')
            ->schema([
                Textarea::make('referencias_copiadas')
                    ->label('Copiar Referencias')
                    ->helperText('Pega: CANTIDAD [TAB] REFERENCIA por línea')
                    ->placeholder("10\tREF123\n5\tREF456")
                    ->rows(5)
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        if (!is_string($state) || trim($state) === '') return;
                        $items = $get('referencias') ?? [];
                        $lineas = preg_split('/\r?\n/', $state);
                        $agregadas = 0;
                        foreach ($lineas as $linea) {
                            if (trim($linea) === '') continue;
                            // Aceptar TAB o uno o más espacios entre cantidad y referencia
                            if (!preg_match('/^\s*(\d+)\s+(.+?)\s*$/u', $linea, $m)) {
                                Log::warning('Bulk referencias: línea inválida (no coincide con CANTIDAD [WS] REFERENCIA)', [
                                    'linea' => $linea,
                                ]);
                                continue;
                            }
                            $cantidad = (int) $m[1];
                            $codigo = strtoupper(trim($m[2]));
                            if ($codigo === '' || $cantidad <= 0) continue;
                            try {
                                Log::info('Bulk referencias: intentando crear/buscar', ['codigo' => $codigo]);
                                $ref = DB::transaction(function () use ($codigo) {
                                    return Referencia::firstOrCreate(
                                        ['referencia' => $codigo],
                                        ['referencia' => $codigo]
                                    );
                                });
                                Log::info('Bulk referencias: resultado firstOrCreate', [
                                    'codigo' => $codigo,
                                    'id' => $ref->id,
                                    'wasRecentlyCreated' => $ref->wasRecentlyCreated,
                                ]);
                                $items[] = [
                                    'referencia_id' => $ref->id,
                                    'cantidad' => $cantidad,
                                    'comentario' => '',
                                ];
                                $agregadas++;
                            } catch (\Throwable $e) {
                                Log::error('Bulk referencias: error creando referencia', [
                                    'codigo' => $codigo,
                                    'ex' => $e->getMessage(),
                                ]);
                            }
                        }
                        // Evitar duplicados en el repeater por referencia_id + cantidad
                        $items = collect($items)
                            ->map(fn($i) => [
                                'key' => $i['referencia_id'] . '|' . $i['cantidad'] . '|' . ($i['comentario'] ?? ''),
                                'item' => $i,
                            ])
                            ->unique('key')
                            ->values()
                            ->map(fn($r) => $r['item'])
                            ->toArray();
                        Log::info('Bulk referencias: items agregados', ['count' => $agregadas]);
                        $set('referencias', $items);
                    })->columnSpanFull(),
            ])
            ->columns(12)
            ->visibleOn('create');
    }

    private static function getReferenciasSchema(): array
    {
        return [
            Select::make("sistema_id")
                ->label("Sistema")
                ->searchable()
                ->preload()
                ->options(Sistema::query()->pluck("nombre", "id"))
                ->createOptionForm(self::getSistemaForm())
                ->createOptionUsing(fn($data) => Sistema::create($data)->id)
                ->hintIcon(
                    fn(Get $get) => $get("sistema_id")
                        ? "heroicon-o-question-mark-circle"
                        : null,
                )
                ->hintAction(fn(Get $get) => self::getInfoSistemaAction($get))
                ->live()
                ->columnSpan(2),
            Select::make("definicion")
                ->label("Tipo de Artículo")
                ->options(
                    Articulo::whereNotNull("definicion")
                        ->pluck("definicion", "id")
                        ->toArray(),
                )
                ->searchable()
                ->preload()
                ->visible(fn(Get $get) => $get("articulo_id") == null)
                ->hintIcon(
                    fn(Get $get) => $get("definicion")
                        ? "heroicon-o-question-mark-circle"
                        : null,
                )
                ->hintAction(fn(Get $get) => self::getInfoTipoAction($get))
                ->columnSpan(2),
            TextInput::make("articulo_definicion")
                ->label("Artículo")
                ->disabled()
                ->visible(fn(Get $get) => $get("articulo_id") == !null)
                ->columnSpan(2)
                ->hintIcon("heroicon-o-question-mark-circle")
                ->hintAction(
                    Action::make("infoArticulo")
                        ->label("Info")
                        ->modalHeading("Información del Artículo")
                        ->modalContent(
                            fn(Get $get) => self::getInfoArticuloView($get),
                        )
                        ->modalSubmitAction(false),
                ),
            TextInput::make("cantidad")
                ->label("Cantidad")
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->columnSpan(1),
            Select::make("referencia_id")
                ->searchable()
                ->relationship("referencia", "referencia")
                ->options(self::getReferenciaOptions())
                ->getSearchResultsUsing(
                    fn(string $search) => self::searchReferencias($search),
                )
                ->getOptionLabelUsing(
                    fn($value) => self::getReferenciaOptionLabel($value),
                )
                ->createOptionForm(self::getReferenciaCreateForm())
                ->createOptionUsing(
                    fn(array $data) => self::createReferencia($data),
                )
                ->editOptionForm(self::getReferenciaEditForm())
                ->createOptionAction(
                    fn(Action $action) => $action
                        ->modalHeading("Crear Referencia")
                        ->modalDescription(
                            "Crea una nueva referencia y será asociada a este pedido automáticamente",
                        )
                        ->modalWidth("lg"),
                )
                ->afterStateUpdated(
                    fn(Set $set, Get $get) => self::updateArticuloFields(
                        $set,
                        $get,
                    ),
                )
                ->afterStateHydrated(
                    fn(Set $set, Get $get) => self::updateArticuloFields(
                        $set,
                        $get,
                    ),
                )
                ->live()
                ->placeholder("Seleccione una referencia")
                ->preload()
                ->columnSpan(4),
            Hidden::make("articulo_id")->disabled(),
            Select::make("marca_id")
                ->options(function () {
                    // Asegurar que todos los fabricantes existan como listas de tipo 'Marca'
                    $fabricantes = Fabricante::query()->select('nombre', 'descripcion')->get();
                    foreach ($fabricantes as $fab) {
                        Lista::firstOrCreate(
                            ['tipo' => 'Marca', 'nombre' => $fab->nombre],
                            ['definicion' => $fab->descripcion ?? null]
                        );
                    }

                    // Construir opciones de marcas sin duplicados por nombre (case-insensitive)
                    return Lista::query()
                        ->where('tipo', 'Marca')
                        ->orderBy('nombre')
                        ->get()
                        ->unique(fn($item) => mb_strtolower($item->nombre))
                        ->pluck('nombre', 'id')
                        ->toArray();
                })
                ->createOptionForm(self::getMarcaForm())
                ->createOptionUsing(
                    fn($data) => Lista::create(
                        array_merge($data, ["tipo" => "Marca"]),
                    )->id,
                )
                ->createOptionAction(
                    fn(Action $action) => $action
                        ->modalHeading("Crear Marca")
                        ->modalDescription(
                            "Crea una nueva marca y será asociada a la referencia automáticamente",
                        )
                        ->modalWidth("lg"),
                )
                ->searchable()
                ->columnSpan(2),
            TextInput::make("articulo_descripcionEspecifica")
                ->label("Descripción")
                ->disabled()
                ->Visible(fn(Get $get) => $get("articulo_id") == !null)
                ->columnSpan(3),
            TextInput::make("peso")
                ->label("Peso (gr)")
                ->disabled()
                ->visible(fn(Get $get) => $get("articulo_id") !== null)
                ->afterStateHydrated(function (Set $set, Get $get) {
                    $articuloId = $get("articulo_id");
                    if ($articuloId) {
                        $articulo = Articulo::find($articuloId);
                        if ($articulo) {
                            $set("peso", $articulo->peso);
                        }
                    }
                })
                ->reactive()
                ->columnSpan(1),
            FileUpload::make("imagen")->label("Imagen")->image()->imageEditor()->columnSpan(4),
            Textarea::make("comentario")->label("Comentario")->columnSpan(4),
            Toggle::make("mostrar_referencia")
                ->label("Mostrar nombre de referencia en cotización")
                ->default(true)
                ->hidden(fn($get) => self::shouldHideCotizacionFields($get))
                ->visibleOn("edit"),
            ToggleButtons::make("estado")
                ->label("Estado de referencia")
                ->options([1 => "Activo", 0 => "Inactivo"])
                ->colors([1 => "success", 0 => "danger"])
                ->icons([
                    1 => "heroicon-o-check-circle",
                    0 => "heroicon-o-x-circle",
                ])
                ->default(1)
                ->inline()
                ->hidden(fn($get) => self::shouldHideCotizacionFields($get))
                ->visibleOn("edit"),
            Section::make()->schema([
                TableRepeater::make("proveedores")
                    ->label("Proveedores")
                    ->relationship()
                    ->columnSpanFull()
                    ->extraAttributes([
                        'class' => 'custom-table-repeater',
                    ])
                    ->headers([
                        Header::make("estado")
                            ->label("Seleccionar")
                            ->align(Alignment::Center)
                            ->width("100px"),
                        Header::make("proveedor_id")
                            ->label("Proveedor")
                            ->align(Alignment::Center)
                            ->width("300px"),
                        Header::make("cantidad")
                            ->label("Cant.")
                            ->align(Alignment::Center)
                            ->width("80px"),
                        Header::make("ubicacion")
                            ->label("Ubicación")
                            ->width("120px"),
                        Header::make("marca_id")
                            ->label("Marca")
                            ->align(Alignment::Center)
                            ->width("150px"),
                        Header::make("dias_entrega")
                            ->label("Días Entrega")
                            ->align(Alignment::Center)
                            ->width("120px"),
                        Header::make("costo_unidad")
                            ->label("Costo Unidad")
                            ->align(Alignment::Center)
                            ->width("130px"),
                        Header::make("utilidad %")
                            ->label("Utilidad %")
                            ->align(Alignment::Center)
                            ->width("100px"),
                        Header::make("valor_unidad")
                            ->label("Valor Unidad $")
                            ->align(Alignment::Center)
                            ->width("130px"),
                        Header::make("valor_total")
                            ->label("Valor Total $")
                            ->align(Alignment::Center)
                            ->width("130px"),
                    ])
                    ->schema(self::getProveedoresSchema())
                    ->extraAttributes(
                        fn(Get $get) => [
                            "fabricante_id" => $get("fabricante_id"),
                            "sistema_id" => $get("../../sistema_id"),
                        ],
                    )
                    ->extraItemActions([
                        Action::make("verProveedor")
                            ->tooltip("Ver proveedor")
                            ->icon("heroicon-o-eye")
                            ->url(
                                fn(
                                    array $arguments,
                                    Repeater $component,
                                ): ?string => self::getVerProveedorUrl(
                                    $arguments,
                                    $component,
                                ),
                            ),
                    ]),
            ])->hiddenOn("create"),
        ];
    }

    private static function getProveedoresSchema(): array
    {
        return [
            Toggle::make("estado")->label("Seleccionar")->default(true),
            Select::make("proveedor_id")
                ->options(function (Get $get) {
                    $sistemaId = $get("../../sistema_id");
                    $fabricanteId = $get("../../../../fabricante_id");
                    if (!$fabricanteId || !$sistemaId) {
                        return [];
                    }
                    return Tercero::query()
                        ->whereHas(
                            "fabricantes",
                            fn($query) => $query->where(
                                "fabricante_id",
                                $fabricanteId,
                            ),
                        )
                        ->whereHas(
                            "sistemas",
                            fn($query) => $query->where(
                                "sistema_id",
                                $sistemaId,
                            ),
                        )
                        ->pluck("nombre", "id");
                })
                ->afterStateUpdated(
                    fn(Set $set, Get $get) => self::updateProveedorFields(
                        $set,
                        $get,
                    ),
                )
                ->live()
                ->reactive()
                ->label("Proveedores")
                ->searchable(),
            TextInput::make("cantidad")
                ->label("Cantidad Cotizadas")
                ->numeric()
                ->required()
                ->live()
                ->reactive()
                ->default(fn(Get $get) => $get("../../cantidad"))
                ->afterStateUpdated(function (Set $set, Get $get) {
                    // Solo ejecutar si tenemos todos los valores necesarios
                    $costo_unidad = $get('costo_unidad');
                    $utilidad = $get('utilidad');
                    $ubicacion = $get('ubicacion');
                    
                    if (!empty($costo_unidad) && !empty($utilidad) && !empty($ubicacion)) {
                        self::calculateValorTotal($set, $get);
                    }
                }),
            TextInput::make("ubicacion")->label("Ubicación")->readOnly(),
            Select::make("marca_id")
                ->options(function () {
                    // Sincroniza fabricantes a Lista como tipo 'Marca'
                    $fabricantes = Fabricante::query()->select('nombre', 'descripcion')->get();
                    foreach ($fabricantes as $fab) {
                        Lista::firstOrCreate(
                            ['tipo' => 'Marca', 'nombre' => $fab->nombre],
                            ['definicion' => $fab->descripcion ?? null]
                        );
                    }
                    // Opciones unificadas sin duplicados (insensible a mayúsculas)
                    return Lista::query()
                        ->where('tipo', 'Marca')
                        ->orderBy('nombre')
                        ->get()
                        ->unique(fn($item) => mb_strtolower($item->nombre))
                        ->pluck('nombre', 'id')
                        ->toArray();
                })
                // ->createOptionForm(self::getMarcaForm())
                // ->createOptionUsing(
                //     fn($data) => Lista::create(
                //         array_merge($data, ["tipo" => "Marca"]),
                //     )->id,
                // )
                // ->createOptionAction(
                //     fn(Action $action) => $action
                //         ->modalHeading("Crear Marca")
                //         ->modalDescription(
                //             "Crea una nueva marca y será asociada a la referencia automáticamente",
                //         )
                //         ->modalWidth("lg"),
                // )
                ->searchable()
                ->label("Marca"),
            TextInput::make("dias_entrega")
                ->label("Días de entrega (hábiles)")
                ->default(0)
                ->numeric()
                ->minValue(0)
                ->step(1)
                ->required()
                //->suffix(fn(Get $get) => $get('dias_entrega') == 0 ? ' (Inmediata)' : ' días hábiles')
                ->afterStateUpdated(function (Get $get, Set $set) {
                    // Asegurar que el valor sea un entero >= 0
                    $valor = max(0, (int) $get('dias_entrega'));
                    $set('dias_entrega', $valor);
                }),
            TextInput::make("costo_unidad")
                ->label("Costo Unidad")
                ->live()
                ->reactive()
                ->numeric()
                ->afterStateUpdated(function (Set $set, Get $get) {
                    // Solo ejecutar si tenemos todos los valores necesarios
                    $cantidad = $get('cantidad');
                    $utilidad = $get('utilidad');
                    $ubicacion = $get('ubicacion');
                    
                    if (!empty($cantidad) && !empty($utilidad) && !empty($ubicacion)) {
                        self::calculateValorTotal($set, $get);
                    }
                }),
            TextInput::make("utilidad")
                ->label("Utilidad %")
                ->reactive()
                ->required()
                ->live()
                ->numeric()
                ->afterStateUpdated(function (Set $set, Get $get) {
                    // Solo ejecutar si tenemos todos los valores necesarios
                    $cantidad = $get('cantidad');
                    $costo_unidad = $get('costo_unidad');
                    $ubicacion = $get('ubicacion');
                    
                    if (!empty($cantidad) && !empty($costo_unidad) && !empty($ubicacion)) {
                        self::calculateValorTotal($set, $get);
                    }
                }),
            TextInput::make("valor_unidad")
                ->label("Valor Unidad $")
                ->numeric()
                ->readOnly(),
            TextInput::make("valor_total")
                ->live()
                ->readOnly()
                ->label("Valor Total $"),
        ];
    }

    private static function getSistemaForm(): array
    {
        return [
            TextInput::make("nombre")
                ->label("Nombre")
                ->required()
                ->unique(ignoreRecord: true)
                ->placeholder("Nombre del sistema"),
            Textarea::make("descripcion")->label("Descripción"),
            FileUpload::make("imagen")->label("Imagen")->image()->imageEditor(),
        ];
    }

    private static function getInfoSistemaAction(Get $get): ?Action
    {
        if (!$get("sistema_id")) {
            return null;
        }
        return Action::make("infoSistema")
            ->label("Info")
            ->modalHeading("Información del Sistema")
            ->modalContent(function () use ($get) {
                $sistema = Sistema::find($get("sistema_id"));
                if (!$sistema) {
                    return "No hay información del sistema seleccionada.";
                }
                return view("components.sistema-info", [
                    "hasImage" => $sistema->imagen !== null,
                    "imageUrl" => $sistema->imagen
                        ? Storage::url($sistema->imagen)
                        : null,
                    "sistema" => $sistema,
                ]);
            })
            ->modalSubmitAction(false);
    }

    private static function getInfoTipoAction(Get $get): ?Action
    {
        if (!$get("definicion")) {
            return null;
        }
        return Action::make("infoTipo")
            ->label("Ver detalle")
            ->modalHeading("Información del Tipo de Artículo")
            ->modalContent(function () use ($get) {
                $tipo = Articulo::with("referencias.marca")->find(
                    $get("definicion"),
                );
                if (!$tipo) {
                    return "No hay información del tipo seleccionado.";
                }
                return view("components.tipo-info", ["tipo" => $tipo]);
            })
            ->modalSubmitAction(false);
    }

    private static function getInfoArticuloView(Get $get)
    {
        $articulo = Articulo::with("referencias.marca")->find(
            $get("articulo_id"),
        );
        if (!$articulo) {
            return "No hay información del artículo seleccionado.";
        }
        return view("components.tipo-info", ["tipo" => $articulo]);
    }

    private static function getReferenciaOptions(): array
    {
        return Referencia::query()
            ->leftJoin(
                "articulos_referencias",
                "referencias.id",
                "=",
                "articulos_referencias.referencia_id",
            )
            ->leftJoin(
                "articulos",
                "articulos_referencias.articulo_id",
                "=",
                "articulos.id",
            )
            ->selectRaw(
                "referencias.id, CASE WHEN articulos.id IS NULL THEN referencias.referencia ELSE CONCAT(referencias.referencia, ' - ', articulos.descripcionEspecifica) END as full_description",
            )
            ->pluck("full_description", "referencias.id")
            ->toArray();
    }

    private static function searchReferencias(string $search): array
    {
        return Referencia::query()
            ->leftJoin(
                "articulos_referencias",
                "referencias.id",
                "=",
                "articulos_referencias.referencia_id",
            )
            ->leftJoin(
                "articulos",
                "articulos_referencias.articulo_id",
                "=",
                "articulos.id",
            )
            ->selectRaw(
                "referencias.id, CASE WHEN articulos.id IS NULL THEN referencias.referencia ELSE CONCAT(referencias.referencia, ' - ', articulos.descripcionEspecifica) END as full_description",
            )
            ->where(function ($query) use ($search) {
                $query
                    ->where("referencias.referencia", "like", "%{$search}%")
                    ->orWhere(
                        "articulos.descripcionEspecifica",
                        "like",
                        "%{$search}%",
                    );
            })
            ->limit(50)
            ->pluck("full_description", "referencias.id")
            ->toArray();
    }

    private static function getReferenciaOptionLabel($value): string
    {
        return Referencia::query()
            ->leftJoin(
                "articulos_referencias",
                "referencias.id",
                "=",
                "articulos_referencias.referencia_id",
            )
            ->leftJoin(
                "articulos",
                "articulos_referencias.articulo_id",
                "=",
                "articulos.id",
            )
            ->selectRaw(
                "CASE WHEN articulos.id IS NULL THEN referencias.referencia ELSE CONCAT(referencias.referencia, ' - ', articulos.descripcionEspecifica) END as full_description",
            )
            ->where("referencias.id", $value)
            ->pluck("full_description")
            ->first();
    }

    private static function getReferenciaCreateForm(): array
    {
        return [
            TextInput::make("referencia")
                ->label("Referencia")
                ->unique("referencias", "referencia", ignoreRecord: true)
                ->required()
                ->maxLength(255),
            Select::make("articulo_id")
                ->label("Artículo")
                ->options(
                    Articulo::query()->pluck("descripcionEspecifica", "id"),
                )
                ->createOptionForm(self::getArticuloForm())
                ->createOptionUsing(fn($data) => Articulo::create($data)->id)
                ->createOptionAction(
                    fn(Action $action) => $action
                        ->modalHeading("crear Artículo")
                        ->modalDescription(
                            "Crea un nuevo artículo y será asociada a esta referencia automáticamente",
                        )
                        ->modalWidth("lg"),
                )
                ->searchable(),
            Select::make("marca_id")
                ->options(function () {
                    // Sincroniza fabricantes a Lista como tipo 'Marca'
                    $fabricantes = Fabricante::query()->select('nombre', 'descripcion')->get();
                    foreach ($fabricantes as $fab) {
                        Lista::firstOrCreate(
                            ['tipo' => 'Marca', 'nombre' => $fab->nombre],
                            ['definicion' => $fab->descripcion ?? null]
                        );
                    }
                    // Opciones unificadas sin duplicados (insensible a mayúsculas)
                    return Lista::query()
                        ->where('tipo', 'Marca')
                        ->orderBy('nombre')
                        ->get()
                        ->unique(fn($item) => mb_strtolower($item->nombre))
                        ->pluck('nombre', 'id')
                        ->toArray();
                })
                ->createOptionForm(self::getMarcaForm())
                ->createOptionUsing(
                    fn($data) => Lista::create(
                        array_merge($data, ["tipo" => "Marca"]),
                    )->id,
                )
                ->createOptionAction(
                    fn(Action $action) => $action
                        ->modalHeading("Crear Marca")
                        ->modalDescription(
                            "Crea una nueva marca y será asociada a la referencia automáticamente",
                        )
                        ->modalWidth("lg"),
                )
                ->searchable()
                ->label("Marca"),
            Textarea::make("comentario")->label("Comentario")->maxLength(500),
        ];
    }

    private static function createReferencia(array $data): int
    {
        $referencia = Referencia::create($data);
        $referencia->articulos()->attach($data["articulo_id"]);
        return $referencia->id;
    }

    private static function getReferenciaEditForm(): array
    {
        return [
            TextInput::make("referencia")
                ->label("Referencia")
                ->unique("referencias", "referencia", ignoreRecord: true)
                ->required()
                ->maxLength(255),
            Select::make("articulo_id")
                ->label("Artículo")
                ->relationship("articulos", "descripcionEspecifica")
                ->options(
                    Articulo::query()->pluck("descripcionEspecifica", "id"),
                )
                ->createOptionForm(self::getArticuloForm())
                ->editOptionForm(self::getArticuloForm())
                ->createOptionUsing(fn($data) => Articulo::create($data)->id)
                ->createOptionAction(
                    fn(Action $action) => $action
                        ->modalHeading("crear Artículo")
                        ->modalWidth("lg"),
                )
                ->searchable()
                ->required()
                ->reactive()
                ->live()
                ->afterStateUpdated(function ($state, Set $set) {
                    $articulo = Articulo::find($state);
                    if ($articulo) {
                        $set("peso", $articulo->peso);
                    } else {
                        $set("peso", null);
                    }
                }),
            Select::make("marca_id")
                ->options(function () {
                    // Sincroniza fabricantes a Lista como tipo 'Marca'
                    $fabricantes = Fabricante::query()->select('nombre', 'descripcion')->get();
                    foreach ($fabricantes as $fab) {
                        Lista::firstOrCreate(
                            ['tipo' => 'Marca', 'nombre' => $fab->nombre],
                            ['definicion' => $fab->descripcion ?? null]
                        );
                    }
                    // Opciones unificadas sin duplicados (insensible a mayúsculas)
                    return Lista::query()
                        ->where('tipo', 'Marca')
                        ->orderBy('nombre')
                        ->get()
                        ->unique(fn($item) => mb_strtolower($item->nombre))
                        ->pluck('nombre', 'id')
                        ->toArray();
                })
                ->createOptionForm(self::getMarcaForm())
                ->createOptionUsing(
                    fn($data) => Lista::create(
                        array_merge($data, ["tipo" => "Marca"]),
                    )->id,
                )
                ->createOptionAction(
                    fn(Action $action) => $action
                        ->modalHeading("Crear Marca")
                        ->modalDescription(
                            "Crea una nueva marca y será asociada a la referencia automáticamente",
                        )
                        ->modalWidth("lg"),
                )
                ->searchable()
                ->required(),
            Textarea::make("comentario")->label("Comentario")->maxLength(500),
        ];
    }

    private static function getArticuloForm(): array
    {
        return [
            Select::make("definicion")
                ->label("Tipo de Artículo")
                ->options(
                    Lista::query()
                        ->where("tipo", "Tipo de artículo")
                        ->get()
                        ->mapWithKeys(
                            fn($definicion) => [
                                $definicion->nombre => $definicion->nombre,
                            ],
                        )
                        ->toArray(),
                )
                ->createOptionForm(self::getTipoArticuloForm())
                ->createOptionUsing(function ($data) {
                    $definicion = Lista::create(
                        array_merge($data, ["tipo" => "Tipo de Artículo"]),
                    );
                    return $definicion->nombre;
                })
                ->createOptionAction(
                    fn(Action $action) => $action
                        ->modalHeading("Nuevo Tipo de Artículo")
                        ->modalWidth("lg"),
                )
                ->searchable()
                ->preload()
                ->live()
                ->required(),
            FileUpload::make("foto_medida")
                ->label("Foto de la medida")
                ->image()
                ->imageEditor(),
            TextInput::make("descripcionEspecifica")
                ->label("Decripción específica")
                ->placeholder("Descripción específica del artículo")
                ->required(),
            TextInput::make("peso")
                ->label("Peso (Kg)")
                ->placeholder("Peso del artículo en Kilogramos")
                ->numeric(),
            Textarea::make("comentarios")
                ->label("Comentarios")
                ->placeholder("Comentarios del artículo"),
            FileUpload::make("fotoDescriptiva")
                ->label("Foto descriptiva")
                ->image()
                ->imageEditor()
                ->openable(),
        ];
    }

    private static function getTipoArticuloForm(): array
    {
        return [
            Hidden::make("tipo")->default("Tipo de Artículo")->required(),
            TextInput::make("nombre")
                ->label("Nombre")
                ->placeholder("Nombre del tipo de artículo"),
            TextInput::make("definicion")
                ->label("Descripción de definición")
                ->placeholder("Definición del artículo"),
            FileUpload::make("foto")->label("Foto")->image()->imageEditor(),
        ];
    }

    private static function getMarcaForm(): array
    {
        return [
            TextInput::make("nombre")
                ->label("Nombre")
                ->placeholder("Nombre de la marca"),
            Hidden::make("tipo")->default("Marca"),
            Textarea::make("definicion")
                ->label("Descripción")
                ->placeholder("Definición de la marca"),
            FileUpload::make("foto")->label("Foto")->image()->imageEditor(),
        ];
    }

    private static function updateArticuloFields(Set $set, Get $get): void
    {
        $referencia = Referencia::find($get("referencia_id"));
        if (!$referencia) {
            $set("articulo_definicion", null);
            $set("articulo_descripcionEspecifica", null);
            $set("articulo_id", null);
            $set("peso", null);
            $set("marca_id", null);
        } else {
            $articulo = $referencia->articuloReferencia()->first()?->articulo;
            $marca = Lista::find($referencia->marca_id);
            if (!$articulo) {
                $set("articulo_definicion", null);
                $set("articulo_id", null);
                $set("peso", null);
                return;
            }
            $set("articulo_definicion", $articulo->definicion);
            $set(
                "articulo_descripcionEspecifica",
                $articulo->descripcionEspecifica,
            );
            $set("articulo_id", $articulo->id);
            $set("peso", $articulo->peso);
            $set("marca_id", $marca->id);
        }
    }

    private static function shouldHideCotizacionFields(Get $get): bool
    {
        $pedido = Pedido::find($get("pedido_id"));
        return $pedido
            ? !in_array($pedido->estado, ["En_Costeo", "Cotizado"]) ||
            in_array(Auth::user()?->roles->first()?->name, [
                "Analista",
                "Logistica",
            ])
            : true;
    }

    private static function updateProveedorFields(Set $set, Get $get): void
    {
        $proveedor = Tercero::find($get("proveedor_id"));
        if (!$proveedor) {
            $set("dias_entrega", 0);
            $set("costo_unidad", null);
            $set("utilidad", null);
            $set("valor_total", null);
            $set("valor_unidad", null);
            return;
        }
        $set(
            "ubicacion",
            $proveedor->country_id == 48 ? "Nacional" : "Internacional",
        );
        // Establecer días de entrega del proveedor, con 0 como valor por defecto si no tiene
        $diasEntrega = $proveedor->dias_entrega ?? 0;
        $set("dias_entrega", max(0, (int) $diasEntrega));
        $set("costo_unidad", $proveedor->costo_unidad);
        $set("utilidad", $proveedor->utilidad);
        $set("cantidad", $get("cantidad"));
        
        // Ejecutar el cálculo automáticamente después de establecer los valores
        self::calculateValorTotal($set, $get);
    }

    private static function calculateValorTotal(Set $set, Get $get): void
    {
        $costo_unidad = $get("costo_unidad");
        $utilidad = $get("utilidad");
        $cantidad = $get("cantidad");
        $ubicacion = $get("ubicacion");
        

        
        // Validar que los valores requeridos existan y sean numéricos válidos
        if (empty($costo_unidad) || empty($utilidad) || empty($cantidad) || empty($ubicacion)) {
            $set("valor_unidad", null);
            $set("valor_total", null);
            return;
        }
        
        // Convertir a números para asegurar cálculos correctos
        $costo_unidad = (float) $costo_unidad;
        $utilidad = (float) $utilidad;
        $cantidad = (int) $cantidad;

        if ($ubicacion == "Internacional") {
            // Lógica para proveedores internacionales
            $peso = $get("../../peso");
            
            // Obtener empresa activa
            $empresa = Empresa::query()->where('estado', 1)->first();
            $trm = $empresa?->trm;
            $flete = $empresa?->flete;
            

            
            // Validar que tengamos todos los valores necesarios para internacional
            if (!is_numeric($peso) || !is_numeric($trm) || !is_numeric($flete)) {
                $set("valor_unidad", null);
                $set("valor_total", null);
                return;
            }
            
            // Paso 1: Convertir costo USD a pesos colombianos
            $costo_cop = $costo_unidad * $trm;
            
            // Paso 2: Agregar flete por peso (flete ya está en COP)
            $valor_base = $costo_cop + ($peso * 2.2 * $flete);
            
            // Paso 3: Aplicar utilidad sobre el valor base
            $valor_unidad = $valor_base + ($utilidad * $valor_base / 100);
            
            // Paso 4: Redondear a centenas
            $valor_unidad = round($valor_unidad, -2);
            
            // Paso 5: Calcular valor total
            $valor_total = $valor_unidad * $cantidad;
            

            
            $set("valor_total", $valor_total);
            $set("valor_unidad", $valor_unidad);
        } else {
            // Lógica para proveedores nacionales
            // 1. Calcular valor por unidad: costo + (costo × utilidad%)
            $valor_unidad = $costo_unidad + ($costo_unidad * $utilidad / 100);
            
            // 2. Calcular valor total: valor_unidad × cantidad
            $valor_total = $valor_unidad * $cantidad;
            

            
            $set("valor_unidad", $valor_unidad);
            $set("valor_total", $valor_total);
        }
    }

    private static function getVerProveedorUrl(
        array $arguments,
        Repeater $component,
    ): ?string {
        $itemData = $component->getRawItemState($arguments["item"]);
        $proveedorId = $itemData["proveedor_id"] ?? null;
        if (!$proveedorId) {
            return null;
        }
        return route("filament.admin.resources.terceros.edit", [
            "record" => $proveedorId,
        ]);
    }
}
