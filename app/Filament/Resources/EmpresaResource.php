<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpresaResource\Pages;
use App\Filament\Resources\EmpresaResource\RelationManagers;
use App\Models\City;
use App\Models\Empresa;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmpresaResource extends Resource
{
    protected static ?string $model = Empresa::class;

    protected static ?string $navigationGroup = 'Sistema';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->unique('empresas', 'nombre', ignoreRecord: true)
                    ->maxLength(300),
                Forms\Components\TextInput::make('siglas')
                    ->maxLength(10)
                    ->default(null),
                Forms\Components\TextInput::make('direccion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefono')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('celular')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->unique('empresas', 'email', ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('logo_light')
                    ->label('Logo para modo oscuro')
                    ->image()
                    ->imageEditor(),
                Forms\Components\FileUpload::make('logo_dark')
                    ->label('Logo para modo claro')
                    ->image()
                    ->imageEditor(),
                Forms\Components\TextInput::make('nit')
                    ->unique('empresas', 'nit', ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('representante')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('country_id')
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->label('País')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        // $set('state_id', null);
                        $set('city_id', null);
                    }),
                // Forms\Components\Select::make('state_id')
                //     ->options(fn (Get $get): Collection => State::query()
                //         ->where('country_id', $get('country_id'))
                //         ->pluck('name', 'id'))
                //     ->label('Departamento')
                //     ->searchable()
                //     ->preload()
                //     ->live()
                //     ->afterStateUpdated(function (Set $set) {
                //         $set('city_id', null);
                //     }),
                Forms\Components\Select::make('city_id')
                    ->options(fn(Get $get): Collection => City::query()
                        ->where('country_id', $get('country_id'))
                        ->pluck('name', 'id'))
                    ->label('Ciudad')
                    ->searchable()
                    ->live()
                    ->preload(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('siglas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('logo_light')
                    ->label('Logo Light')
                    ->circular(),
                Tables\Columns\ImageColumn::make('logo_dark')
                    ->label('Logo Dark')
                    ->circular(),
                Tables\Columns\TextColumn::make('nit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('representante')
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('estado')
                    ->label('Estado')
                    ->toggleable()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('country_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpresas::route('/'),
            'create' => Pages\CreateEmpresa::route('/create'),
            'edit' => Pages\EditEmpresa::route('/{record}/edit'),
        ];
    }
}
