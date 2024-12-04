<?php

namespace App\Filament\Resources\ArticulosResource\RelationManagers;

use App\Filament\Resources\ReferenciaResource;
use App\Models\Referencia;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use League\CommonMark\Reference\Reference;

class ReferenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'referencias';
    

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('referencia')
                    ->label('Referencia')
                    ->unique('referencias', 'referencia', ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Select::make('marca_id')
                    ->label('Marca')
                    ->options(fn () => \App\Models\Lista::where('tipo', 'Marca')->pluck('nombre', 'id')->toArray())
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('referencia')
            ->columns([
                Tables\Columns\TextColumn::make('referencia')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('marca.nombre')->label('Marca')->sortable()->searchable(),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Crear Referencia')->icon('heroicon-o-plus')->tooltip('Crear una nueva referencia y la asocia a este artículo'),
                Tables\Actions\Action::make('Asociar Referencia')->icon('heroicon-o-link')->tooltip('Asociar una referencia existente a este artículo')
                ->action(function (array $data) {
                    // Actualiza el articulo_id de la referencia seleccionada
                    Referencia::findOrFail($data['referencia_id'])->update([
                        'articulo_id' => $this->ownerRecord->id, // Asocia al artículo actual
                    ]);
                })
                ->form([
                    Select::make('referencia_id')
                        ->label('Referencia')
                        ->options(Referencia::whereNull('articulo_id')->pluck('referencia', 'id')) // Solo referencias no asociadas
                        ->searchable()
                        ->required(),
                ])
                ->modalHeading('Asociar Referencia')->color('secondary'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn(Referencia $record): string => ReferenciaResource::getUrl('edit', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
