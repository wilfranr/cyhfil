<?php

namespace App\Filament\Resources;

use App\Models\Articulo;
use App\Filament\Resources\ArticulosResource\Pages;
use App\Filament\Resources\ArticulosResource\RelationManagers;
use App\Models\Lista;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Referencia;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Columns\ImageColumn;
use Filament\Widgets\StatsOverviewWidget as WidgetsStatsOverviewWidget;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\SubNavigationPosition;
use Filament\Infolists\Components\ViewEntry;

class ArticulosResource extends Resource
{
    protected static ?string $model = Articulo::class;

    protected static ?string $navigationIcon = 'heroicon-m-cube-transparent';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'definicion';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            // 'cruces' => $record->referencias->pluck('referencia')->implode(', '),
            'juegos' => $record->juegos,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Group::make()
                    ->schema([
                        Select::make('definicion')
                            ->label('Tipo')
                            ->options(
                                Lista::query()
                                    ->where('tipo', 'Tipo de artículo')
                                    ->get()
                                    ->mapWithKeys(fn($definicion) => [$definicion->nombre => $definicion->nombre])
                                    ->toArray()
                            )
                            ->createOptionForm(function () {
                                return [
                                    Hidden::make('tipo')
                                        ->default('Tipo de Artículo')
                                        ->required(),
                                    TextInput::make('nombre')
                                        ->label('Nombre')
                                        ->placeholder('Nombre del tipo de artículo'),
                                    TextInput::make('definicion')
                                        ->label('Definición')
                                        ->placeholder('Definición del artículo'),
                                    FileUpload::make('foto')
                                        ->label('Foto')
                                        ->image()
                                        ->imageEditor(),
                                ];
                            })
                            ->createOptionUsing(function ($data) {
                                $definicion = Lista::create([
                                    'tipo' => 'Tipo de Artículo',
                                    'nombre' => $data['nombre'],
                                    'definicion' => $data['definicion'],
                                ]);

                                return $definicion->nombre;
                            })

                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),


                        TextInput::make('descripcionEspecifica')
                            ->label('Decripción')
                            ->placeholder('Descripción específica del artículo'),
                        TextInput::make('peso')
                            ->label('Peso (Kg)')
                            ->placeholder('Peso del artículo en Kilogramos')
                            ->numeric()
                            ->reactive()
                            ->hintIcon('heroicon-o-calculator')
                            ->hintAction(
                                Action::make('Conversor')
                                    ->modalHeading('Conversor de Peso')
                                    ->modalWidth('lg')
                                    ->form([
                                        Select::make('unidad_origen')
                                            ->label('Unidad de origen')
                                            ->options([
                                                'g' => 'Gramos (gr)',
                                                'lb' => 'Libras (lb)',
                                                'oz' => 'Onzas (oz)',
                                                't' => 'Toneladas (t)',
                                            ])
                                            ->default('g')
                                            ->required(),
                                        TextInput::make('valor_origen')
                                            ->label('Valor a convertir')
                                            ->numeric()
                                            ->required()
                                            ->placeholder('Ingrese el valor'),
                                    ])
                                    ->action(function (array $data, Set $set) {
                                        $valor = $data['valor_origen'];
                                        $unidad = $data['unidad_origen'];

                                        // Conversión a Kg
                                        $pesoKg = match ($unidad) {
                                            'g' => $valor / 1000,       // Gramos a Kg
                                            'lb' => $valor * 0.453592,  // Libras a Kg
                                            'oz' => $valor * 0.0283495, // Onzas a Kg
                                            't' => $valor * 1000,       // Toneladas a Kg
                                            default => $valor,          // Si ya está en Kg
                                        };

                                        // Redondeamos a 3 decimales y actualizamos el campo 'peso'
                                        $set('peso', round($pesoKg, 3));
                                    })
                            )
                            ->suffix('Kg'),


                        Textarea::make('comentarios')
                            ->label('Comentarios')
                            ->placeholder('Comentarios del artículo'),

                        Section::make('Referenicas Cruzadas')
                            ->schema([
                                Repeater::make('articuloReferencia')->label('')
                                    ->relationship()
                                    ->schema([
                                        Select::make('referencia_id')
                                            ->options(
                                                Referencia::query()
                                                    ->whereDoesntHave('ArticuloReferencia') // Excluye las referencias ya asociadas a cualquier artículo
                                                    ->pluck('referencia', 'id')
                                            )
                                            ->createOptionForm(function () {
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

                                            ->label('Referencia')
                                            ->searchable()
                                            ->live(onBlur: true)
                                            ->required(),
                                    ])
                                    ->columns(1)
                                    ->reorderable()
                            ])->visibleOn('create'),
                    ])->columns(4),
                Group::make()
                    ->schema([
                        FileUpload::make('fotoDescriptiva')
                            ->label('Foto de Referencia')
                            ->image()
                            ->imageEditor()
                            ->openable()
                            ->imagePreviewHeight('250')
                            ->loadingIndicatorPosition('left')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left'),
                        FileUpload::make('foto_medida')
                            ->label('Plano Esquemático')
                            ->image()
                            ->openable()
                            ->imageEditor()
                            ->imagePreviewHeight('150')
                            ->loadingIndicatorPosition('left')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left'),
                    ])->columns(2)
            ])->columns(1);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Id')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('fotoDescriptiva')
                    ->label('Foto'),

                TextColumn::make('definicion')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('descripcionEspecifica')
                    ->label('Descripción específica')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),
                TextColumn::make('peso')
                    ->label('Peso (Kg)')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('cruces')
                //     ->label('Cruces')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('juegos')
                //     ->label('Juegos')
                //     ->searchable()
                //     ->sortable(),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('articuloJuegos.referencia.marca');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Components\Split::make([
                            Components\Grid::make(3)
                                ->schema([
                                    Components\Group::make([
                                        Components\TextEntry::make('definicion')
                                            ->label('Tipo'),
                                        Components\TextEntry::make('descripcionEspecifica')
                                            ->label('Descripción específica'),
                                        Components\TextEntry::make('peso')
                                            ->label('Peso (Kg)'),
                                    ]),
                                    Components\Group::make([
                                        Components\ImageEntry::make('fotoDescriptiva')
                                            ->label('Foto Descriptiva')->width(200)->square()->height(200),
                                    ]),
                                    Components\Group::make([
                                        Components\ImageEntry::make('foto_medida')
                                            ->label('Plano Esquemático')->width(200)->height(200)->square(),
                                    ]),



                                ]),
                        ]),
                    ]),
                Components\Section::make()
                    ->schema([
                        ViewEntry::make('referencias')
                            ->view('filament.infolists.entries.referencias-table')
                            ->label('Referencias Cruzadas')
                            ->hidden(fn($record) => empty($record->referencias)), // Ocultar si no hay referencias

                        ViewEntry::make('articuloJuegos')
                            ->view('filament.infolists.entries.juegos-table')
                            ->label('Lista de Juegos')
                            ->hidden(fn($record) => empty($record->articuloJuegos)),
                    ])->columns(2),



            ]);
    }

    // public static function getRecordSubNavigation(Page $page): array    {
    //     return [
    //         Pages\ViewArticulos::class,
    //         Pages\EditArticulos::class,
    //     ];
    // }


    protected function getHeaderWidgets(): array
    {
        return [
            WidgetsStatsOverviewWidget::class

        ];
    }


    public static function getRelations(): array
    {
        return [
            RelationManagers\ReferenciasRelationManager::class,
            RelationManagers\MedidasRelationManager::class,
            RelationManagers\ArticuloReferenciasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticulos::route('/'),
            'create' => Pages\CreateArticulos::route('/create'),
            'edit' => Pages\EditArticulos::route('/{record}/edit'),
            'view' => Pages\ViewArticulos::route('/{record}'),
        ];
    }
}