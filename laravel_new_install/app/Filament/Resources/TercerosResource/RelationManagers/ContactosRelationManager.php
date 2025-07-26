<?php

namespace App\Filament\Resources\TercerosResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactosRelationManager extends RelationManager
{
    protected static string $relationship = 'contactos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cargo')
                    ->maxLength(255),
                Forms\Components\Select::make('country_id')
                    ->label('País')
                    ->options(
                        \App\Models\Country::pluck('name', 'id')->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $country = \App\Models\Country::find($state);

                            if ($country) {
                                $set('indicativo', $country->phonecode);
                            }
                        }
                    })
                    ->default('48'),

                Forms\Components\Hidden::make('indicativo')
                    ->label('Indicativo')
                    ->required()
                    ->default('57'),

                Forms\Components\TextInput::make('telefono')
                    ->required()
                    ->tel()
                    ->maxLength(255)
                    ->placeholder('Ingrese el teléfono sin indicativo')
                    ->suffixIcon('ri-phone-line'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->placeholder('Ingrese el correo electrónico')
                    ->suffixIcon('ri-mail-line'),
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
            ->recordTitleAttribute('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('nombre'),
                Tables\Columns\TextColumn::make('cargo'),
                Tables\Columns\TextColumn::make('telefono'),
                Tables\Columns\TextColumn::make('email'),
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
