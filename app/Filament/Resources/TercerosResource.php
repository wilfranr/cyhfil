<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TercerosResource\{Pages, RelationManagers};

use App\Models\{Tercero, City, Country, State, Maquina, Contacto, Fabricante, Lista};
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, Select, FileUpload, Hidden, MarkdownEditor, Section, Repeater, Toggle, Wizard, TextArea};
use Illuminate\Support\Collection;
use Filament\Forms\{Get, Set};
use Illuminate\Database\Eloquent\Model;

class TercerosResource extends Resource
{
    protected static ?string $model = Tercero::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'nombre',
        ];
    }


    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'tipo' => $record->tipo,
            'numero_documento' => $record->numero_documento,
            'direccion' => $record->direccion,
            'telefono' => $record->telefono,
            'email' => $record->email,
        ];
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Información general')->icon('heroicon-o-information-circle')
                        ->schema([
                            TextInput::make('nombre')
                                ->required()->dehydrateStateUsing(fn(string $state): string => strtoupper($state))
                                ->label('Nombre'),

                            Select::make('tipo')
                                ->label('Tipo de Tercero')
                                ->required()
                                ->live()
                                ->options([
                                    'Cliente' => 'Cliente',
                                    'Proveedor' => 'Proveedor',
                                    'Ambos' => 'Ambos',
                                ]),
                            Select::make('tipo_documento')
                                ->live()
                                ->options([
                                    'cc' => 'Cédula de Ciudadanía',
                                    'ce' => 'Cédula de Extranjería',
                                    'nit' => 'NIT',
                                ])
                                ->label('Tipo de Documento')
                                ->required(),
                            TextInput::make('numero_documento')
                                ->label('Número de Documento')
                                ->required()
                                ->integer()
                                ->minValue(1000)
                                ->unique('terceros', 'numero_documento', ignoreRecord: true), // Validación única
                            TextInput::make('telefono')
                                ->label('Teléfono')
                                ->required()
                                ->tel()
                                ->suffixIcon('ri-phone-line'),
                            TextInput::make('email')
                                ->email()
                                ->label('Correo Electrónico')
                                ->unique('terceros', 'email', ignoreRecord: true)->suffixIcon('ri-mail-line'),
                            TextInput::make('dv')->nullable()
                                ->label('Dígito Verificador')
                                ->visible(fn(Get $get) => $get('tipo_documento') === 'nit'),
                            Select::make('forma_pago')
                                ->nullable()
                                ->label('Forma de Pago')
                                ->options([
                                    'contado' => 'Contado',
                                    'credito' => 'Crédito',
                                ]),
                            TextInput::make('email_factura_electronica')->nullable()->label('Correo Factura Electrónica')->suffixIcon('ri-mail-line')->visible(fn(Get $get) => $get('tipo') === 'Cliente' || $get('tipo') === 'Ambos'),
                            TextInput::make('sitio_web')->nullable()->label('Sitio Web')->prefix('https://')->suffixIcon('heroicon-m-globe-alt')->url(),
                            Select::make('maquina_id')
                                ->label('Máquina')
                                ->relationship('maquinas', 'modelo')
                                ->options(function ($get) {
                                    // Obtenemos las máquinas relacionadas con el tercero
                                    return \App\Models\Maquina::all()->mapWithKeys(function ($maquina) {
                                        $tipo = \App\Models\Lista::find($maquina->tipo)->nombre ?? 'Sin tipo'; // Obtiene el nombre del tipo
                                        $fabricanteNombre = Fabricante::find($maquina->fabricante_id)->nombre;

                                        return [$maquina->id => "{$tipo} - {$maquina->modelo} - {$maquina->serie} - {$fabricanteNombre}"]; // Concatenamos tipo, modelo y serie
                                    });
                                })
                                ->createOptionForm(function () {
                                    return [
                                        Select::make('tipo')
                                            ->relationship('Listas', 'nombre')
                                            ->createOptionForm(function () {
                                                return [
                                                    Hidden::make('tipo')
                                                        ->default('Tipo de Máquina')
                                                        ->required()
                                                        ->hidden(),
                                                    TextInput::make('nombre')
                                                        ->label('Nombre')
                                                        ->required()
                                                        ->placeholder('Ingrese el nombre del tipo de máquina'),
                                                    MarkdownEditor::make('definicion')
                                                        ->label('Descripción')
                                                        ->required()
                                                        ->placeholder('Proporcione una descripción del tipo de máquina'),
                                                    FileUpload::make('foto')
                                                        ->label('Foto')
                                                        ->image()
                                                ];
                                            })
                                            ->createOptionUsing(function ($data) {
                                                $tipo = Lista::create([
                                                    'nombre' => $data['nombre'],
                                                    'definicion' => $data['definicion'],
                                                    'tipo' => 'Tipo de Máquina',
                                                ]);

                                                return $tipo->id;
                                            })
                                            ->createOptionAction(function ($action) {
                                                $action->modalHeading('Crear Tipo de Máquina');
                                                $action->modalDescription('Crea un nuevo tipo de máquina y será asociado a la máquina automáticamente');
                                                $action->modalWidth('lg');
                                            })
                                            ->editOptionForm(function ($record) {
                                                return [
                                                    Hidden::make('tipo')
                                                        ->default('Tipo de Máquina')
                                                        ->hidden(),
                                                    TextInput::make('nombre')
                                                        ->label('Nombre')
                                                        ->required()
                                                        ->placeholder('Ingrese el nombre del tipo de máquina'),
                                                    MarkdownEditor::make('definicion')
                                                        ->label('Descripción')
                                                        ->required()
                                                        ->placeholder('Proporcione una descripción del tipo de máquina'),
                                                    FileUpload::make('foto')
                                                        ->label('Foto')
                                                        ->image(),
                                                ];
                                            })
                                            ->label('Tipo')
                                            ->live()
                                            ->preload()
                                            ->searchable()
                                            ->required(),

                                        Select::make('fabricante_id')
                                            ->relationship('fabricantes', 'nombre')
                                            ->label('Fabricante')
                                            ->preload()
                                            ->live()
                                            ->searchable()
                                            ->required()
                                            ->createOptionForm(function () {
                                                return [
                                                    TextInput::make('nombre')
                                                        ->label('Nombre')
                                                        ->required()
                                                        ->placeholder('Nombre del fabricante'),
                                                    MarkdownEditor::make('descripcion')
                                                        ->label('Descripción')
                                                        ->nullable()
                                                        ->dehydrateStateUsing(fn(string $state): string => ucwords($state))
                                                        ->required()
                                                        ->maxLength(500),
                                                    FileUpload::make('logo')
                                                        ->label('Logo')
                                                        ->image(),
                                                ];
                                            })
                                            ->editOptionForm(function ($record) {
                                                return [
                                                    TextInput::make('nombre')
                                                        ->label('Nombre')
                                                        ->required()
                                                        ->placeholder('Nombre del fabricante'),
                                                    MarkdownEditor::make('descripcion')
                                                        ->label('Descripción')
                                                        ->nullable()
                                                        ->dehydrateStateUsing(fn(string $state): string => ucwords($state))
                                                        ->required()
                                                        ->maxLength(500),
                                                    FileUpload::make('logo')
                                                        ->label('Logo')
                                                        ->image(),
                                                ];
                                            })
                                            ->createOptionUsing(function ($data) {
                                                $fabricante = Fabricante::create([
                                                    'nombre' => $data['nombre'],
                                                    'descripcion' => $data['descripcion'],
                                                    'logo' => $data['logo'],

                                                ]);

                                                return $fabricante->id;
                                            })
                                            ->createOptionAction(function ($action) {
                                                $action->modalHeading('Crear Fabricante');
                                                $action->modalDescription('Crea un nuevo fabricante y será asociado a la máquina automáticamente');
                                                $action->modalWidth('lg');
                                            }),

                                        TextInput::make('modelo')
                                            ->label('Modelo')
                                            ->required(),

                                        TextInput::make('serie')
                                            ->label('Serie')
                                            ->required(),

                                        TextInput::make('arreglo')
                                            ->label('Arreglo')
                                            ->required(),

                                        FileUpload::make('foto')
                                            ->label('Foto')
                                            ->image()
                                            ->imageEditor(),

                                        FileUpload::make('fotoId')
                                            ->label('FotoId')
                                            ->image()
                                            ->imageEditor(),
                                    ];
                                })
                                ->createOptionUsing(function ($data) {
                                    $maquina = Maquina::create([
                                        'tipo' => $data['tipo'],
                                        'fabricante_id' => $data['fabricante_id'],
                                        'modelo' => $data['modelo'],
                                        'serie' => $data['serie'],
                                        'arreglo' => $data['arreglo'],
                                        'foto' => $data['foto'],
                                        'fotoId' => $data['fotoId'],
                                    ]);

                                    return $maquina->id;
                                })
                                ->createOptionAction(function ($action) {
                                    $action->modalHeading('Crear Máquina');
                                    $action->modalDescription('Crea una nueva máquina y será asociada al tercero automáticamente');
                                    $action->modalWidth('xl');
                                })
                                ->multiple()
                                ->preload()
                                ->live()
                                ->visible(fn(Get $get) => $get('tipo') === 'Cliente' || $get('tipo') === 'Ambos')
                                ->searchable(),


                            Section::make('Fabricantes y Sistemas')
                                ->schema([
                                    Select::make('fabricante_id')
                                        ->relationship('fabricantes', 'nombre') // Relación con 'fabricantes' y el campo 'nombre'
                                        ->label('Fabricantes')
                                        ->multiple()
                                        ->preload()
                                        ->live()
                                        ->searchable()
                                        ->options(function () {
                                            // Obtenemos los fabricantes como un array [id => nombre]
                                            $fabricantes = \App\Models\Fabricante::pluck('nombre', 'id')->toArray();

                                            // Agregamos la opción 'Seleccionar todos' al inicio
                                            $options = ['all' => 'Seleccionar todos'];

                                            // Mantenemos las claves de los IDs intactas
                                            return $options + $fabricantes;
                                        })
                                        ->afterStateUpdated(function ($state, $set) {
                                            if (in_array('all', $state)) {
                                                // Obtener todos los IDs válidos de los fabricantes
                                                $allFabricantes = \App\Models\Fabricante::pluck('id')->toArray();

                                                // Establecer todos los IDs como seleccionados
                                                $set('fabricante_id', $allFabricantes);
                                            } else {
                                                // Filtrar 'all' del estado
                                                $state = array_filter($state, fn($value) => $value !== 'all');

                                                // Asegúrate de que los valores del estado sean los IDs correctos
                                                $set('fabricante_id', array_values($state));
                                            }
                                        })
                                        ->createOptionForm(function () {
                                            return [
                                                TextInput::make('nombre')
                                                    ->label('Nombre')
                                                    ->required()
                                                    ->placeholder('Nombre del fabricante'),
                                                MarkdownEditor::make('descripcion')
                                                    ->label('Descripción')
                                                    ->nullable()
                                                    ->dehydrateStateUsing(fn(string $state): string => ucwords($state))
                                                    ->required()
                                                    ->maxLength(500),
                                                FileUpload::make('logo')
                                                    ->label('Logo')
                                                    ->image(),
                                            ];
                                        })
                                        ->editOptionForm(function ($record) {
                                            return [
                                                TextInput::make('nombre')
                                                    ->label('Nombre')
                                                    ->required()
                                                    ->placeholder('Nombre del fabricante'),
                                                MarkdownEditor::make('descripcion')
                                                    ->label('Descripción')
                                                    ->nullable()
                                                    ->dehydrateStateUsing(fn(string $state): string => ucwords($state))
                                                    ->required()
                                                    ->maxLength(500),
                                                FileUpload::make('logo')
                                                    ->label('Logo')
                                                    ->image(),
                                            ];
                                        })
                                        ->createOptionUsing(function ($data) {
                                            $fabricante = Fabricante::create([
                                                'nombre' => $data['nombre'],
                                                'descripcion' => $data['descripcion'],
                                                'logo' => $data['logo'],

                                            ]);

                                            return $fabricante->id;
                                        })
                                        ->createOptionAction(function ($action) {
                                            $action->modalHeading('Crear Fabricante');
                                            $action->modalDescription('Crea un nuevo fabricante y será asociado a la máquina automáticamente');
                                            $action->modalWidth('lg');
                                        }),




                                    Select::make('sistema_id')
                                        ->relationship('sistemas', 'nombre') // Relación con 'sistemas' y el campo 'nombre'
                                        ->label('Sistema')
                                        ->multiple()
                                        ->preload()
                                        ->live()
                                        ->searchable()
                                        ->options(function () {
                                            // Agregamos la opción "Seleccionar todos" al inicio
                                            $sistemas = \App\Models\Sistema::pluck('nombre', 'id')->toArray(); // Obtenemos las opciones de la relación

                                            // Preservamos las claves para que los IDs de sistemas no se vean afectados
                                            return ['all' => 'Seleccionar todos'] + $sistemas;
                                        })
                                        ->afterStateUpdated(function ($state, $set) {
                                            if (in_array('all', $state)) {
                                                // Si selecciona "Seleccionar todos", seleccionamos todos los IDs válidos de los sistemas
                                                $allSistemas = \App\Models\Sistema::pluck('id')->toArray();

                                                // Filtrar valores inválidos y asegurarnos de que sean IDs válidos
                                                $allSistemas = array_filter($allSistemas, fn($id) => $id > 0);
                                                $set('sistema_id', $allSistemas); // Establecemos todas las opciones seleccionadas
                                            } else {
                                                // Filtramos "all" del estado y aseguramos que los valores sean consistentes
                                                $state = array_filter($state, fn($value) => $value !== 'all');
                                                $set('sistema_id', array_values($state));
                                            }
                                        })
                                        ->createOptionForm(function () {
                                            return [
                                                TextInput::make('nombre')
                                                    ->label('Nombre')
                                                    ->required()
                                                    ->unique(ignoreRecord: true)
                                                    ->placeholder('Nombre del sistema'),
                                                MarkdownEditor::make('descripcion')
                                                    ->label('Descripción')
                                                    ->nullable()
                                                    ->dehydrateStateUsing(fn(string $state): string => ucwords($state)),
                                                FileUpload::make('imagen')
                                                    ->label('Imagen')
                                                    ->image()
                                                    ->imageEditor(),
                                            ];
                                        })
                                        ->createOptionAction(function ($action) {
                                            $action->modalHeading('Crear Sistema');
                                            $action->modalDescription('Crea un nuevo sistema y será asociado al tercero automáticamente');
                                            $action->modalWidth('lg');
                                        })

                                ])->columns(2)->visible(fn(Get $get) => $get('tipo') === 'Proveedor' || $get('tipo') === 'Ambos'),




                        ])->columns(3),
                    Wizard\Step::make('Ubicación')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            TextInput::make('direccion')
                                ->label('Dirección'),
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
                        ])->columns(3),


                    Wizard\Step::make('Documentos')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            FileUpload::make('rut')
                                ->disk('s3')
                                ->directory('form-attachments')
                                ->visibility('private')
                                ->label('Adjuntar Rut'),
                            FileUpload::make('certificacion_bancaria')
                                ->disk('s3')
                                ->directory('form-attachments')
                                ->visibility('private')
                                ->label('Adjuntar Certificación Bancaria'),
                            FileUpload::make('camara_comercio')
                                ->disk('s3')
                                ->directory('form-attachments')
                                ->visibility('private')
                                ->label('Adjuntar Cámara de Comercio'),
                            FileUpload::make('cedula_representante_legal')
                                ->disk('s3')
                                ->directory('form-attachments')
                                ->visibility('private')
                                ->label('Adjuntar Cédula Representante Legal'),
                        ])->columns(2)
                ])->skippable()->columnSpan('full')




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {

                            'Cliente' => 'primary',
                            'Proveedor' => 'info',
                            'Ambos' => 'success',
                        }
                    ),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->wrap()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_documento')
                    ->label('Tipo Doc')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('numero_documento')
                    ->label('Número Documento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->label('Dirección')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->getStateUsing(function ($record) {
                        // Buscar el contacto principal asociado al tercero
                        $contactoPrincipal = $record->contactos()->where('principal', true)->first();

                        if ($contactoPrincipal) {
                            // Si hay un contacto principal, mostrar su teléfono
                            return $contactoPrincipal->telefono;
                        }

                        // Si no hay contacto principal, mostrar "Sin Contacto"
                        return 'Sin Contacto';
                    })
                    ->url(function ($record) {
                        // Buscar el contacto principal asociado al tercero
                        $contactoPrincipal = $record->contactos()->where('principal', true)->first();

                        if ($contactoPrincipal) {
                            // Retornar la URL de WhatsApp con el teléfono del contacto principal
                            return "https://wa.me/{$contactoPrincipal->telefono}";
                        }

                        // No generar URL si no hay contacto principal
                        return null;
                    })
                    ->openUrlInNewTab()
                    ->icon('ri-whatsapp-line')
                    ->color('success'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('forma_pago')
                    ->label('Forma de Pago')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_factura_electronica')
                    ->label('Correo Factura Electrónica')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),



            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_documento')
                    ->options([
                        'cc' => 'Cédula de Ciudadanía',
                        'ce' => 'Cédula de Extranjería',
                        'nit' => 'NIT',
                    ])
                    ->label('Tipo Documento'),
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                    ])
                    ->label('Estado'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
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
            // 'maquinas' => RelationManagers\MaquinasRelationManager::class,
            'contactos' => RelationManagers\ContactosRelationManager::class,
            'direcciones' => RelationManagers\DireccionesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTerceros::route('/'),
            'create' => Pages\CreateTerceros::route('/create'),
            'edit' => Pages\EditTerceros::route('/{record}/edit'),
        ];
    }
}