<?php

namespace App\Filament\Resources\MaquinasResource\RelationManagers;

use App\Filament\Resources\TercerosResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TercerosRelationManager extends RelationManager
{
    protected static string $relationship = 'terceros';
    protected static ?string $title = 'Clientes propietarios de esta máquina';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                ->url(fn ($record) => TercerosResource::getUrl('edit', ['record' => $record]))  // Utilizamos TercerosResource para obtener la URL de edición
                ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->actions([ 
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
