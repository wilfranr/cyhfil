<?php

namespace App\Filament\Resources\MarcaResource\RelationManagers;

use App\Models\City;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Tables\View\TablesRenderHook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class TercerosRelationManager extends RelationManager
{
    protected static string $relationship = 'terceros';
    protected static ?string $title = 'Proveedores asociados a esta marca';

    public function form(Form $form): Form
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
                            ->relationship('maquinas', 'serie')
                            ->createOptionForm(function () {
                                return [
                                    Select::make('tipo')
                                        ->options([
                                            'excavadora' => 'Excavadora',
                                            'retroexcavadora' => 'Retroexcavadora',
                                            'bulldozer' => 'Bulldozer',
                                            'grua' => 'Grua',
                                            'montacargas' => 'Montacargas',
                                            'compactador' => 'Compactador',
                                            'motoniveladora' => 'Motoniveladora',
                                            'rodillo' => 'Rodillo',
                                            'tractor' => 'Tractor',
                                            'camion' => 'Camion',
                                            'volqueta' => 'Volqueta',
                                            'otro' => 'Otro',
                                        ])
                                        ->label('Tipo')
                                        ->searchable()
                                        ->required(),

                                    Select::make('marca_id')
                                        ->relationship('marcas', 'nombre')
                                        ->label('Marca')
                                        ->preload()
                                        ->live()
                                        ->searchable(),

                                    Forms\Components\TextInput::make('modelo')
                                        ->label('Modelo')
                                        ->required(),

                                    Forms\Components\TextInput::make('serie')
                                        ->label('Serie')
                                        ->required(),

                                    Forms\Components\TextInput::make('arreglo')
                                        ->label('Arreglo')
                                        ->required(),

                                    Forms\Components\FileUpload::make('foto')
                                        ->label('Foto'),

                                    Forms\Components\FileUpload::make('fotoId')
                                        ->label('FotoId')
                                ];
                            })
                            ->label('Máquina')
                            ->multiple()
                            ->preload()
                            ->live()
                            ->visible(fn(Get $get) => $get('tipo') === 'Cliente' || $get('tipo') === 'Ambos')
                            ->searchable(),

                        Section::make('Sistemas')
                            ->schema([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tercero_id')
            ->columns([
                Tables\Columns\TextColumn::make('tercero_id')->label('ID'),
                Tables\Columns\TextColumn::make('nombre')->label('Nombre'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->url(fn($record) => "https://wa.me/57{$record->telefono}")
                    ->openUrlInNewTab()
                    ->icon('ri-whatsapp-line')
                    ->color('success'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
