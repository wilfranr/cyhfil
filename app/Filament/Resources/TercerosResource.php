<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TercerosResource\Pages;
use App\Filament\Resources\TercerosResource\RelationManagers;
use App\Filament\Resources\TercerosResource\RelationManagers\MaquinasRelationManager;
use App\Models\Tercero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use App\Models\Country;
use App\Models\City;
use App\Models\Maquina;
use App\Models\State;
use App\Models\TerceroContacto;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Collection;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class TercerosResource extends Resource
{
    protected static ?string $model = Tercero::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'nombre';
    
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
                                ->required()
                                ->label('Nombre'),
                            Select::make('tipo')
                                ->label('Tipo de Tercero')
                                ->required()
                                ->options([
                                    'cliente' => 'Cliente',
                                    'proveedor' => 'Proveedor',
                                ]),
                            Select::make('tipo_documento')
                                ->options([
                                    'cc' => 'Cédula de Ciudadanía',
                                    'ce' => 'Cédula de Extranjería',
                                    'nit' => 'NIT',
                                ])
                                ->label('Tipo de Documento'),
                            TextInput::make('numero_documento')
                                ->label('Número de Documento')
                                ->unique('terceros', 'numero_documento', ignoreRecord: true), // Validación única
                            TextInput::make('telefono')
                                ->label('Teléfono'),
                            TextInput::make('email')
                                ->email()
                                ->label('Correo Electrónico')
                                ->unique('terceros', 'email', ignoreRecord: true), // Validación única
                            TextInput::make('dv')->nullable()->label('Dígito Verificador'),
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
                                ->label('Máquina')
                                ->multiple()
                                ->preload()
                                ->live()
                                ->searchable(),
                      


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
                                ->options(fn (Get $get): Collection => State::query()
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
                                ->options(fn (Get $get): Collection => City::query()
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
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
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
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('tipo')
                    ->options([
                        'cliente' => 'Cliente',
                        'proveedor' => 'Proveedor',
                    ])
                    ->label('Tipo'),
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
