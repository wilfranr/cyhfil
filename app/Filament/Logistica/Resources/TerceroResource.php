<?php

namespace App\Filament\Logistica\Resources;

use App\Filament\Logistica\Resources\TerceroResource\Pages;
use App\Filament\Resources\TercerosResource\RelationManagers;

use App\Models\{Tercero, City, Country, State, Maquina, Contacto};
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, Select, FileUpload, Section, Repeater, Toggle, Wizard};
use Illuminate\Support\Collection;
use Filament\Forms\{Get, Set};
use Illuminate\Database\Eloquent\Model;

class TerceroResource extends Resource
{
    protected static ?string $model = Tercero::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 7;

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
                                ->label('Tipo de Documento'),
                            TextInput::make('numero_documento')
                                ->label('Número de Documento')
                                ->required()
                                ->unique('terceros', 'numero_documento', ignoreRecord: true), // Validación única
                            TextInput::make('telefono')
                                ->label('Teléfono'),
                            TextInput::make('email')
                                ->email()
                                ->label('Correo Electrónico')
                                ->unique('terceros', 'email', ignoreRecord: true), // Validación única
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
                            TextInput::make('email_factura_electronica')->nullable()->label('Correo Factura Electrónica'),
                            TextInput::make('sitio_web')->nullable()->label('Sitio Web'),
                            Select::make('maquina_id')
                                ->label('Máquina')
                                ->relationship('maquinas', 'modelo')
                                ->options(function ($get) {
                                    // Obtenemos las máquinas relacionadas con el tercero
                                    return \App\Models\Maquina::all()->mapWithKeys(function ($maquina) {
                                        $tipo = \App\Models\Lista::find($maquina->tipo)->nombre ?? 'Sin tipo'; // Obtiene el nombre del tipo
                                        return [$maquina->id => "{$tipo} - {$maquina->modelo} - {$maquina->serie}"]; // Concatenamos tipo, modelo y serie
                                    });
                                })
                                ->createOptionForm(function () {
                                    return [
                                        Select::make('tipo')
                                            ->relationship('listas', 'nombre', fn($query) => $query->where('tipo', 'Tipo de Máquina')) // Usamos query() para filtrar
                                            ->label('Tipo')
                                            ->searchable()
                                            ->required()
                                            ->live()
                                            ->preload(),

                                        Select::make('marca_id')
                                            ->relationship('marcas', 'nombre')
                                            ->label('Fabricante')
                                            ->preload()
                                            ->live()
                                            ->searchable(),

                                        Forms\Components\TextInput::make('modelo')
                                            ->label('Modelo'),

                                        Forms\Components\TextInput::make('serie')
                                            ->label('Serie'),

                                        Forms\Components\TextInput::make('arreglo')
                                            ->label('Arreglo'),

                                        Forms\Components\FileUpload::make('foto')
                                            ->label('Foto'),

                                        Forms\Components\FileUpload::make('fotoId')
                                            ->label('FotoId'),
                                    ];
                                })
                                ->multiple()
                                ->preload()
                                ->live()
                                ->visible(fn(Get $get) => $get('tipo') === 'Cliente' || $get('tipo') === 'Ambos')
                                ->searchable(),


                            Section::make('Marcas y Sistemas')
                                ->schema([
                                    Select::make('marca_id')
                                        ->relationship('marcas', 'nombre')
                                        ->label('Fabricante')
                                        ->multiple()
                                        ->preload()
                                        ->live()
                                        ->searchable(),
                                    Select::make('sistema_id')
                                        ->relationship('sistemas', 'nombre')
                                        ->label('Sistema')
                                        ->multiple()
                                        ->preload()
                                        ->live()
                                        ->searchable(),
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
            'edit' => Pages\EditTerceros::route('/{record}/edit'),
        ];
    }
}
