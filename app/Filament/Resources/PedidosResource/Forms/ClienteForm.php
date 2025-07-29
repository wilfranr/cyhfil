<?php

namespace App\Filament\Resources\PedidosResource\Forms;

use App\Models\{Tercero, Contacto, Maquina, Fabricante, Lista, State, City};
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Collection;

class ClienteForm
{
    public static function getStep(): Step
    {
        return Step::make('Información del cliente')
            ->icon('heroicon-o-user')
            ->columns(2)
            ->schema([
                Select::make('tercero_id')
                    ->label('Cliente')
                    ->relationship('tercero', 'nombre', fn($query) => $query->whereIn('tipo', ['Cliente', 'Ambos']))
                    ->searchable()
                    ->searchPrompt('Buscar clientes por nombre')
                    ->preload()
                    ->live()
                    ->createOptionForm(self::getTerceroForm())
                    ->editOptionForm(self::getTerceroForm())
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $tercero = Tercero::find($get('tercero_id'));
                        if (!$tercero) {
                            $set('documento', null);
                            $set('direccion', null);
                            $set('telefono', null);
                            $set('email', null);
                            return;
                        }
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
                    ->createOptionForm(self::getContactoForm())
                    ->createOptionUsing(function ($data, $get) {
                        $terceroId = $get('tercero_id');
                        $contacto = Contacto::create(array_merge($data, ['tercero_id' => $terceroId]));
                        return $contacto->id;
                    })
                    ->createOptionAction(function ($action) {
                        $action->modalHeading('Crear Contacto');
                        $action->modalDescription('Crea un nuevo contacto y será asociado al tercero automáticamente');
                        $action->modalWidth('lg');
                    }),

                Select::make('maquina_id')
                    ->label('Máquinas')
                    ->options(function ($get) {
                        $terceroId = $get('tercero_id');
                        if ($terceroId) {
                            return Maquina::whereHas('terceros', function ($query) use ($terceroId) {
                                $query->where('tercero_id', $terceroId);
                            })
                                ->get()
                                ->mapWithKeys(function ($maquina) {
                                    $tipo = Lista::find($maquina->tipo)->nombre;
                                    $fabricanteNombre = Fabricante::find($maquina->fabricante_id)->nombre;
                                    return [$maquina->id => "{$tipo} - {$maquina->modelo} - {$maquina->serie} - {$fabricanteNombre}"];
                                });
                        }
                        return [];
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->createOptionForm(self::getMaquinaForm())
                    ->createOptionUsing(function ($data, $get) {
                        $terceroId = $get('tercero_id');
                        $maquina = Maquina::create($data);
                        if ($terceroId) {
                            $maquina->terceros()->attach($terceroId);
                        }
                        return $maquina->id;
                    })
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $maquina = Maquina::find($state);
                            if ($maquina) {
                                $set('fabricante_id', $maquina->fabricante_id);
                            }
                        }
                    })
                    ->visible(fn(Get $get) => $get('tercero_id') !== null),

                Hidden::make('fabricante_id'),
            ])->hiddenOn('edit');
    }

    private static function getTerceroForm(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Información general')->icon('heroicon-o-information-circle')
                    ->schema(self::getTerceroInfoGeneralSchema())->columns(3),
                Wizard\Step::make('Ubicación')->icon('heroicon-o-map-pin')
                    ->schema(self::getTerceroUbicacionSchema())->columns(3),
                Wizard\Step::make('Documentos')->icon('heroicon-o-document-text')
                    ->schema(self::getTerceroDocumentosSchema())->columns(2)
            ])->skippable()->columnSpan('full')
        ];
    }

    private static function getTerceroInfoGeneralSchema(): array
    {
        return [
            TextInput::make('nombre')->required()->dehydrateStateUsing(fn(string $state): string => strtoupper($state))->label('Nombre'),
            Select::make('tipo')->label('Tipo de Tercero')->required()->live()->options(['Cliente' => 'Cliente', 'Ambos' => 'Ambos']),
            Select::make('tipo_documento')->live()->options(['cc' => 'Cédula de Ciudadanía', 'ce' => 'Cédula de Extranjería', 'nit' => 'NIT'])->label('Tipo de Documento')->required(),
            TextInput::make('numero_documento')->label('Número de Documento')->required()->integer()->minValue(1000)->unique('terceros', 'numero_documento', ignoreRecord: true),
            TextInput::make('dv')->nullable()->label('Dígito Verificador')->visible(fn(Get $get) => $get('tipo_documento') === 'nit'),
            TextInput::make('telefono')->label('Teléfono')->required()->tel()->suffixIcon('ri-phone-line'),
            TextInput::make('email')->email()->label('Correo Electrónico')->unique('terceros', 'email', ignoreRecord: true)->suffixIcon('ri-mail-line'),
            Select::make('forma_pago')->nullable()->label('Forma de Pago')->options(['contado' => 'Contado', 'credito' => 'Crédito']),
            TextInput::make('email_factura_electronica')->nullable()->label('Correo Factura Electrónica')->suffixIcon('ri-mail-line')->visible(fn(Get $get) => $get('tipo') === 'Cliente' || $get('tipo') === 'Ambos'),
            TextInput::make('sitio_web')->nullable()->label('Sitio Web')->suffixIcon('heroicon-m-globe-alt')->url(),
            Select::make('maquina_id')->label('Máquina')->relationship('maquinas', 'modelo')->options(function ($get) {
                return \App\Models\Maquina::all()->mapWithKeys(function ($maquina) {
                    $tipo = \App\Models\Lista::find($maquina->tipo)->nombre ?? 'Sin tipo';
                    $fabricanteNombre = Fabricante::find($maquina->fabricante_id)->nombre;
                    return [$maquina->id => "{$tipo} - {$maquina->modelo} - {$maquina->serie} - {$fabricanteNombre}"];
                });
            })->createOptionForm(self::getMaquinaForm())->multiple()->preload()->live()->visible(fn(Get $get) => $get('tipo') === 'Cliente' || $get('tipo') === 'Ambos')->searchable(),
            Section::make('Fabricantes y Sistemas')->schema(self::getFabricantesSistemasSchema())->columns(2)->visible(fn(Get $get) => $get('tipo') === 'Proveedor' || $get('tipo') === 'Ambos'),
        ];
    }

    private static function getFabricantesSistemasSchema(): array
    {
        return [
            Select::make('fabricante_id')->relationship('fabricantes', 'nombre')->label('Fabricantes')->multiple()->preload()->live()->searchable()->options(function () {
                $fabricantes = \App\Models\Fabricante::pluck('nombre', 'id')->toArray();
                return ['all' => 'Seleccionar todos'] + $fabricantes;
            })->createOptionForm(self::getFabricanteForm())->afterStateUpdated(function ($state, $set) {
                if (in_array('all', $state)) {
                    $allFabricantes = \App\Models\Fabricante::pluck('id')->toArray();
                    $set('fabricante_id', $allFabricantes);
                } else {
                    $state = array_filter($state, fn($value) => $value !== 'all');
                    $set('fabricante_id', array_values($state));
                }
            }),
            Select::make('sistema_id')->relationship('sistemas', 'nombre')->label('Sistema')->multiple()->preload()->live()->searchable()->options(function () {
                $sistemas = \App\Models\Sistema::pluck('nombre', 'id')->toArray();
                return ['all' => 'Seleccionar todos'] + $sistemas;
            })->createOptionForm(self::getSistemaForm())->afterStateUpdated(function ($state, $set) {
                if (in_array('all', $state)) {
                    $allSistemas = \App\Models\Sistema::pluck('id')->toArray();
                    $allSistemas = array_filter($allSistemas, fn($id) => $id > 0);
                    $set('sistema_id', $allSistemas);
                } else {
                    $state = array_filter($state, fn($value) => $value !== 'all');
                    $set('sistema_id', array_values($state));
                }
            })
        ];
    }

    private static function getTerceroUbicacionSchema(): array
    {
        return [
            TextInput::make('direccion')->label('Dirección'),
            Select::make('country_id')->relationship('country', 'name')->label('País')->searchable()->preload()->live()->afterStateUpdated(function (Set $set) {
                $set('state_id', null);
                $set('city_id', null);
            }),
            Select::make('state_id')->options(fn(Get $get): Collection => State::query()->where('country_id', $get('country_id'))->pluck('name', 'id'))->label('Departamento')->searchable()->preload()->live()->afterStateUpdated(function (Set $set) {
                $set('city_id', null);
            }),
            Select::make('city_id')->options(fn(Get $get): Collection => City::query()->where('state_id', $get('state_id'))->pluck('name', 'id'))->label('Ciudad')->searchable()->live()->preload(),
        ];
    }

    private static function getTerceroDocumentosSchema(): array
    {
        return [
            FileUpload::make('rut')->disk('s3')->directory('form-attachments')->visibility('private')->label('Adjuntar Rut'),
            FileUpload::make('certificacion_bancaria')->disk('s3')->directory('form-attachments')->visibility('private')->label('Adjuntar Certificación Bancaria'),
            FileUpload::make('camara_comercio')->disk('s3')->directory('form-attachments')->visibility('private')->label('Adjuntar Cámara de Comercio'),
            FileUpload::make('cedula_representante_legal')->disk('s3')->directory('form-attachments')->visibility('private')->label('Adjuntar Cédula Representante Legal'),
        ];
    }

    private static function getContactoForm(): array
    {
        return [
            TextInput::make('nombre')->required()->maxLength(255),
            TextInput::make('cargo')->maxLength(255),
            Select::make('country_id')->label('País')->options(\App\Models\Country::pluck('name', 'id')->toArray())->searchable()->preload()->reactive()->afterStateUpdated(function ($state, $set) {
                if ($state) {
                    $country = \App\Models\Country::find($state);
                    if ($country) {
                        $set('indicativo', $country->phonecode);
                    }
                }
            })->default('48'),
            Hidden::make('indicativo')->label('Indicativo')->required()->default('57'),
            TextInput::make('telefono')->required()->tel()->maxLength(255)->placeholder('Ingrese el teléfono sin indicativo')->suffixIcon('ri-phone-line'),
            TextInput::make('email')->email()->maxLength(255)->placeholder('Ingrese el correo electrónico')->suffixIcon('ri-mail-line'),
        ];
    }

    private static function getMaquinaForm(): array
    {
        return [
            Select::make('tipo')->relationship('Listas', 'nombre')->createOptionForm(self::getTipoMaquinaForm())->createOptionUsing(function ($data) {
                return Lista::create(array_merge($data, ['tipo' => 'Tipo de Máquina']))->id;
            })->createOptionAction(function ($action) {
                $action->modalHeading('Crear Tipo de Máquina');
                $action->modalDescription('Crea un nuevo tipo de máquina y será asociado a la máquina automáticamente');
                $action->modalWidth('lg');
            })->editOptionForm(self::getTipoMaquinaForm())->label('Tipo')->live()->preload()->searchable()->required(),
            Select::make('fabricante_id')->relationship('fabricantes', 'nombre')->label('Fabricante')->preload()->live()->searchable()->createOptionForm(self::getFabricanteForm())->editOptionForm(self::getFabricanteForm())->createOptionUsing(function ($data) {
                return Fabricante::create($data)->id;
            })->createOptionAction(function ($action) {
                $action->modalHeading('Crear Fabricante');
                $action->modalDescription('Crea un nuevo fabricante y será asociado a la máquina automáticamente');
                $action->modalWidth('lg');
            }),
            TextInput::make('modelo')->label('Modelo')->required(),
            TextInput::make('serie')->label('Serie')->unique(table: 'maquinas', column: 'serie', ignoreRecord: true),
            TextInput::make('arreglo')->label('Arreglo')->unique(table: 'maquinas', column: 'arreglo', ignoreRecord: true),
            FileUpload::make('foto')->label('Foto')->image()->imageEditor(),
            FileUpload::make('fotoId')->label('FotoId')->image()->imageEditor(),
        ];
    }

    private static function getTipoMaquinaForm(): array
    {
        return [
            Hidden::make('tipo')->default('Tipo de Máquina')->required()->hidden(),
            TextInput::make('nombre')->label('Nombre')->required()->placeholder('Ingrese el nombre del tipo de máquina'),
            MarkdownEditor::make('definicion')->label('Descripción')->required()->placeholder('Proporcione una descripción del tipo de máquina'),
            FileUpload::make('foto')->label('Foto')->image()
        ];
    }

    private static function getFabricanteForm(): array
    {
        return [
            TextInput::make('nombre')->label('Nombre')->required()->placeholder('Nombre del fabricante'),
            \Filament\Forms\Components\Textarea::make('descripcion')->label('Descripción')->nullable()->dehydrateStateUsing(fn(string $state): string => ucwords($state))->maxLength(500),
            FileUpload::make('logo')->label('Logo')->image(),
        ];
    }

    private static function getSistemaForm(): array
    {
        return [
            TextInput::make('nombre')->label('Nombre')->required()->placeholder('Nombre del sistema'),
            \Filament\Forms\Components\Textarea::make('descripcion')->label('Descripción')->nullable()->dehydrateStateUsing(fn(string $state): string => ucwords($state))->required()->maxLength(500),
            FileUpload::make('imagen')->label('Imagen')->image(),
        ];
    }
}