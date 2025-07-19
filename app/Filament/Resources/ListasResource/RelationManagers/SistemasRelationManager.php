<?php

namespace App\Filament\Resources\ListasResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SistemasRelationManager extends RelationManager
{
    protected static string $relationship = 'sistemas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('descripcion')
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('imagen')
                    ->image()
                    ->imageEditor()
                    ->nullable()
                    ->maxSize(1024)
                    ->openable()
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->sortable()
                    ->label('Nombre del Sistema'),
                Tables\Columns\TextColumn::make('descripcion')
                    ->sortable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    })
                    ->label('Descripción'),

            ])
            ->filters([
                TrashedFilter::make(), // Agrega un filtro para ver eliminados, normales o todos
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Asociar a un Sistema')
                    ->multiple()
                    ->preloadRecordSelect()
                    ->recordTitleAttribute('nombre') // Asegura que muestre el nombre correcto del sistema
                    ->recordSelectOptionsQuery(fn(Builder $query) => $query),
                CreateAction::make()->label('Crear Sistema'),
            ])
            ->actions([
                EditAction::make()->slideOver(),
                DetachAction::make()->label('Desasociar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}

