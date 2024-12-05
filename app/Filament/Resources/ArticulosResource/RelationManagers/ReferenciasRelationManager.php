<?php

namespace App\Filament\Resources\ArticulosResource\RelationManagers;

use App\Filament\Resources\ReferenciaResource;
use App\Models\ArticuloReferencia;
use App\Models\Lista;
use App\Models\Referencia;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use League\CommonMark\Reference\Reference;

class ReferenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'ArticuloReferencia';
    protected static ?string $title = 'Referencias Cruzadas';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('referencia.referencia') // Accede al campo de la relación
            ->columns([
                    // Tables\Columns\TextColumn::make('referencia.id') // Relación seguida por el campo
                    //     ->searchable()
                    //     ->label('Id')
                    //     ->sortable(),
                    Tables\Columns\TextColumn::make('referencia.referencia') // Relación seguida por el campo
                        ->searchable()
                        ->label('Referencia')
                        ->sortable(),
                    Tables\Columns\TextColumn::make('referencia.marca.nombre') // Accede a la relación y al campo de la marca
                        ->label('Marca')
                        ->sortable()
                        ->searchable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('Asociar Referencia')
                    ->icon('heroicon-o-link')
                    ->tooltip('Asociar una referencia existente a este artículo')
                    ->action(function (array $data) {
                        // Obtén el registro principal del recurso
                        $articulo = $this->getOwnerRecord();

                        // Actualiza el articulo_id de la referencia seleccionada
                        ArticuloReferencia::create([
                            'articulo_id' => $articulo->id,
                            'referencia_id' => $data['referencia_id'],
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Referencia asociada correctamente')
                            ->success()
                            ->send();
                    })
                    ->form([
                        Select::make('referencia_id')
                            ->label('Referencia')
                            ->options(
                                Referencia::query()
                                    ->whereDoesntHave('ArticuloReferencia') // Excluye las referencias ya asociadas a cualquier artículo
                                    ->pluck('referencia', 'id')
                            )->createOptionForm(function () {
                                return [
                                    TextInput::make('referencia')
                                        ->label('Referencia')
                                        ->placeholder('Referencia del artículo')
                                        ->unique('referencias', 'referencia', ignoreRecord: true),
                                    Select::make('marca_id')
                                        ->options(
                                            \App\Models\Lista::where('tipo', 'Marca')->pluck('nombre', 'id')->toArray()
                                        )
                                        ->createOptionForm(function () {
                                            return [
                                                TextInput::make('nombre')
                                                    ->label('Nombre')
                                                    ->placeholder('Nombre de la marca'),
                                                Hidden::make('tipo')
                                                    ->default('Marca'),
                                                TextArea::make('definicion')
                                                    ->label('Descripción')
                                                    ->placeholder('Definición de la marca'),
                                                FileUpload::make('foto')
                                                    ->label('Foto')
                                                    ->image()
                                                    ->imageEditor(),
                                            ];
                                        })
                                        ->createOptionUsing(function ($data) {
                                            $marca = Lista::create([
                                                'nombre' => $data['nombre'],
                                                'tipo' => 'Marca',
                                            ]);

                                            return $marca->id;
                                        })
                                        ->createOptionAction(function (Action $action) {
                                            $action->modalHeading('Crear Marca');
                                            $action->modalDescription('Crea una nueva marca y será asociada a la referencia automáticamente');
                                            $action->modalWidth('lg');
                                        })
                                        ->searchable()
                                        ->label('Marca'),
                                    Textarea::make('comentario')
                                        ->label('Comentario')
                                        ->maxLength(500),
                                ];
                            })
                            ->createOptionUsing(function ($data) {
                                $referencia = Referencia::create([
                                    'referencia' => $data['referencia'],
                                    'marca_id' => $data['marca_id'],
                                ]);

                                return $referencia->id;
                            })
                            ->createOptionAction(function (Action $action) {
                                $action->modalHeading('Crear Referencia');
                                $action->modalDescription('Crea una nueva referencia y será asociada al artículo automáticamente');

                                $action->modalWidth('lg');
                            })
                            ->searchable()
                            ->required(),
                    ])
                    ->modalHeading('Asociar Referencia')
                    ->modalWidth('xl')

            ])
            ->actions([
                Tables\Actions\Action::make('Desasociar')
                    ->icon('ri-link-unlink')
                    ->color('danger')
                    ->tooltip('Al desasociar, no se eliminará la referencia, solo se desvinculará del artículo')
                    ->action(function (ArticuloReferencia $record) {
                        $record->delete();

                        // Notificación de éxito
                        \Filament\Notifications\Notification::make()
                            ->title('Referencia desasociada correctamente')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(function (ArticuloReferencia $record): string {
                        return ReferenciaResource::getUrl('edit', ['record' => $record->referencia_id]);
                    })


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
