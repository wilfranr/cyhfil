<?php

namespace App\Filament\Resources\TercerosResource\RelationManagers;

use App\Models\City;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class DireccionesRelationManager extends RelationManager
{
    protected static string $relationship = 'direcciones';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('direccion')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('En caso de no tener dirección, Escriba Reclama oficina'),
                Select::make('country_id')
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->label('País')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('state_id', null);
                        $set('city_id', null);
                    }),
                Select::make('state_id')
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
                Select::make('city_id')
                    ->options(fn(Get $get): Collection => City::query()
                        ->where('state_id', $get('state_id'))
                        ->pluck('name', 'id'))
                    ->label('Ciudad')
                    ->searchable()
                    ->live()
                    ->preload(),
                    Forms\Components\Toggle::make('principal')
                    ->label('Principal')
                    ->default(false)
                    ->reactive()
                    ->afterStateUpdated(function ($state, $get, $set) {
                        if ($state) {
                            // Si este contacto está marcado como principal, desmarca los demás
                            $this->getRelationship()->where('id', '!=', $get('id'))->update(['principal' => false]);
                        }
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('direccion')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('direccion'),
                Tables\Columns\TextColumn::make('country.name')->label('País'),
                Tables\Columns\TextColumn::make('state.name')->label('Departamento'),
                Tables\Columns\TextColumn::make('city.name')->label('Ciudad'),

                Tables\Columns\IconColumn::make('principal')
                ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
