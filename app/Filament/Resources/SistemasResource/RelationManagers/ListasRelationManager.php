<?php

namespace App\Filament\Resources\SistemasResource\RelationManagers;

use App\Filament\Resources\ListasResource;
use App\Models\Lista;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\DetachAction;
use Illuminate\Database\Eloquent\Builder;

class ListasRelationManager extends RelationManager
{
    protected static string $relationship = 'listas';
    protected static ?string $title = 'Tipos de Artículos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Información de la lista')
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre')
                                    ->required()
                                    //poner en mayúsculas
                                    ->dehydrateStateUsing(fn(string $state): string => ucwords($state))
                                    // ->unique('listas', 'nombre', ignoreRecord: true)
                                    ->placeholder('Nombre de la lista'),
                                MarkdownEditor::make('definicion')
                                    ->label('Definición')
                                    ->placeholder('Definición de la lista'),
                               

                            ]),

                        ]),
                Group::make()
                    ->schema([
                        Section::make('Imagen de la lista')
                            ->schema([
                                FileUpload::make('foto')
                                ->image()
                                ->imageEditor()    
                                ->imagePreviewHeight('250')
                                ->loadingIndicatorPosition('left')
                                ->label('Foto')
                                ->panelAspectRatio('2:1')
                                ->panelLayout('integrated')
                                ->removeUploadedFileButtonPosition('right')
                                ->uploadButtonPosition('left')
                                ->uploadProgressIndicatorPosition('left'),
                            ]),
                    ]),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipo')->sortable()->label('Tipo'),
                Tables\Columns\TextColumn::make('nombre')->sortable()->label('Nombre'),
                Tables\Columns\TextColumn::make('definicion')
                    ->sortable()
                    ->label('Definición')
                    ->wrap(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Crear Tipo de Artículo')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['tipo'] = 'Tipo de artículo'; // 🔥 Asigna automáticamente el tipo
                        return $data;
                    })
                    ->after(function (Lista $record, RelationManager $livewire) {
                        $livewire->ownerRecord->listas()->attach($record->id); // 🔥 Asocia el nuevo tipo al sistema automáticamente
                    }),
                
                AttachAction::make()
                    ->label('Asociar Tipo de Artículo')
                    // ->multiple()
                    ->preloadRecordSelect()
                    ->recordTitleAttribute('nombre') // 🔥 Asegura que Filament use el valor real de 'nombre'
                    ->recordSelectOptionsQuery(fn(Builder $query) => $query->where('tipo', 'Tipo de artículo'))
                    ->recordSelectSearchColumns(['nombre', 'definicion']),
            ])
            

            ->actions([
                Action::make('Ver')
                ->icon('heroicon-o-eye')
                ->url(fn (Lista $record): string => ListasResource::getUrl('edit', ['record' => $record->id]))
                ->openUrlInNewTab(), // 🔥 Abre en una nueva pestaña
                EditAction::make()->slideOver(),
                DetachAction::make(), // Permite desasociar un tipo de artículo
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // ...
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}