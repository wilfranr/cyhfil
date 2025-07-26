<?php

namespace App\Filament\Logistica\Resources;

use App\Filament\Logistica\Resources\TransportadoraResource\Pages;
use App\Filament\Logistica\Resources\TransportadoraResource\RelationManagers;
use App\Models\City;
use App\Models\State;
use App\Models\Transportadora;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransportadoraResource extends Resource
{
    protected static ?string $model = Transportadora::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nit')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefono')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('direccion')
                    ->required()
                    ->maxLength(255),
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
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contacto')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('celular')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('observaciones')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('logo')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contacto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('observaciones')
                    ->searchable(),
                Tables\Columns\TextColumn::make('logo')
                    ->searchable(),
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
            'index' => Pages\ListTransportadoras::route('/'),
            'create' => Pages\CreateTransportadora::route('/create'),
            'edit' => Pages\EditTransportadora::route('/{record}/edit'),
        ];
    }
}
