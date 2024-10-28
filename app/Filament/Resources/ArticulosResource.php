<?php

namespace App\Filament\Resources;

use App\Models\Articulo;
use App\Filament\Resources\ArticulosResource\Pages;
use App\Filament\Resources\ArticulosResource\RelationManagers;
use App\Models\Lista;
use Filament\Forms\Components\FileUpload;
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


class ArticulosResource extends Resource
{
    protected static ?string $model = Articulo::class;

    protected static ?string $navigationIcon = 'heroicon-m-cube-transparent';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'definicion';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'cruces' => $record->referencias->pluck('referencia')->implode(', '),
            'juegos' => $record->juegos,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Detalles de artículo')
                            ->schema([
                                Select::make('definicion')
                                    ->label('Definición')
                                    ->options(
                                        Lista::query()
                                            ->where('tipo', 'Definición de artículo')
                                            ->get()
                                            ->mapWithKeys(fn($definicion) => [$definicion->nombre => $definicion->nombre])
                                            ->toArray()
                                    )
                                    ->createOptionForm(function () {
                                        return [
                                            TextInput::make('tipo')
                                                ->default('Definición de artículo')
                                                ->readonly()
                                                ->required(),
                                            TextInput::make('nombre')
                                                ->label('Nombre')
                                                ->placeholder('Nombre de la definición'),
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
                                            'tipo' => 'Definición de artículo',
                                            'nombre' => $data['nombre'],
                                            'definicion' => $data['definicion'],
                                        ]);

                                        return $definicion->nombre;
                                    })

                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required(),
                                FileUpload::make('fotoMedida')
                                    ->label('Foto de la medida'),

                                TextInput::make('descripcionEspecifica')
                                    ->label('Decripción específica')
                                    ->placeholder('Descripción específica del artículo'),
                                TextInput::make('peso')
                                    ->label('Peso (gr)')
                                    ->placeholder('Peso del artículo en gramos'),
                                TextInput::make('comentarios')
                                    ->label('Comentarios')
                                    ->placeholder('Comentarios del artículo'),
                                FileUpload::make('fotoDescriptiva')
                                    ->label('Foto descriptiva')
                                    ->image()
                                    ->imageEditor()
                                    ->openable(),
                            ])->columns(2),


                        Tabs\Tab::make('Referencias Cruzadas')
                            ->schema([

                                Repeater::make('referencias')
                                    ->relationship('referencias')
                                    ->schema([
                                        Select::make('referencia')
                                            ->label('Referencia')
                                            ->options(
                                                Referencia::query()
                                                    ->whereNotNull('referencia') // Filtra registros nulos
                                                    ->pluck('referencia', 'id')
                                                    ->toArray()


                                            )
                                            ->createOptionForm(function () {
                                                return [
                                                    TextInput::make('referencia')
                                                        ->label('Referencia')
                                                        ->placeholder('Referencia del artículo'),
                                                    // Select::make('marca_id')
                                                    //     ->options(
                                                    //         \App\Models\Marca::pluck('nombre', 'id')

                                                    //     )
                                                    //     ->searchable()
                                                    //     ->label('Marca'),
                                                ];
                                            })
                                            ->createOptionUsing(function ($data) {
                                                $referencia = Referencia::create([
                                                    'referencia' => $data['referencia'],
                                                    // 'marca_id' => $data['marca_id'],
                                                ]);

                                                return $referencia->id;
                                            })
                                            ->searchable(),

                                        // Mantén el botón para agregar otras acciones si las necesitas
                                    ])
                                    ->columns(3)
                                    ->itemLabel(function (array $state): ?string {
                                        // Mostrar el nombre de la referencia como etiqueta
                                        $referencia = Referencia::find($state['referencia']);
                                        return $referencia ? $referencia->referencia : null;
                                    })
                                    ->collapsed()
                                    ->extraItemActions([
                                        Action::make('openreference')  // Asegúrate de usar la clase correcta aquí
                                            ->tooltip('Abrir referencia')
                                            ->icon('heroicon-m-arrow-top-right-on-square')
                                            ->url(function (array $arguments, Repeater $component): ?string {
                                                // Obtener el estado del ítem actual
                                                $itemData = $component->getRawItemState($arguments['item']);

                                                // Obtener el ID de la referencia
                                                $referenciaId = $itemData['referencia'] ?? null;

                                                // Verificar si existe
                                                if (! $referenciaId) {
                                                    return null;
                                                }

                                                // Buscar la referencia
                                                $referencia = Referencia::find($referenciaId);

                                                // Si no se encuentra, retornar null
                                                if (! $referencia) {
                                                    return null;
                                                }

                                                // Retornar la URL de edición
                                                return ReferenciaResource::getUrl('edit', ['record' => $referencia->id]);
                                            }, shouldOpenInNewTab: true)
                                            ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['referencia'])),
                                    ])

                            ]),
                        Tabs\Tab::make('Juegos')
                            ->schema([
                                Repeater::make('articuloJuegos')
                                    ->relationship()
                                    ->schema([
                                        Select::make('referencia_id')
                                            ->options(
                                                Referencia::query()->pluck('referencia', 'id')
                                            )
                                            ->createOptionForm(function () {
                                                return [
                                                    TextInput::make('referencia')
                                                        ->label('Referencia')
                                                        ->placeholder('Referencia del artículo'),
                                                    Select::make('marca_id')
                                                        ->options(
                                                            \App\Models\Marca::pluck('nombre', 'id')
                                                        )
                                                        ->searchable()
                                                        ->label('Marca'),
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
                                            ->live(onBlur: true),
                                        TextInput::make('cantidad')
                                            ->label('Cantidad'),
                                        TextInput::make('comentario')
                                            ->label('Comentario'),
                                    ])
                                    ->columns(3)
                                    ->grid(1)
                                    ->reorderable()
                                    ->collapsed()
                                    ->itemLabel(function (array $state): ?string {
                                        $referencia = Referencia::find($state['referencia_id']);
                                        return $referencia ? $referencia->referencia : null;
                                    })
                                    ->extraItemActions([
                                        Action::make('openreference')  // Asegúrate de usar la clase correcta aquí
                                            ->tooltip('Abrir referencia')
                                            ->icon('heroicon-m-arrow-top-right-on-square')
                                            ->url(function (array $arguments, Repeater $component): ?string {
                                                // Obtener el estado del ítem actual
                                                $itemData = $component->getRawItemState($arguments['item']);

                                                // Obtener el ID de la referencia
                                                $referenciaId = $itemData['referencia_id'] ?? null;

                                                // Verificar si existe
                                                if (! $referenciaId) {
                                                    return null;
                                                }

                                                // Buscar la referencia
                                                $referencia = Referencia::find($referenciaId);

                                                // Si no se encuentra, retornar null
                                                if (! $referencia) {
                                                    return null;
                                                }

                                                // Retornar la URL de edición
                                                return ReferenciaResource::getUrl('edit', ['record' => $referencia->id]);
                                            }, shouldOpenInNewTab: true)
                                            ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['referencia_id'])),
                                    ]),
                            ]),
                    ])->columnSpan('full')

            ]);
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
                    ->label('Definición')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('descripcionEspecifica')
                    ->label('Descripción específica')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),
                TextColumn::make('peso')
                    ->label('Peso')
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
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    protected function getHeaderWidgets(): array
    {
        return [
            WidgetsStatsOverviewWidget::class

        ];
    }


    public static function getRelations(): array
    {
        return [
            // 'referencias' => RelationManagers\ReferenciasRelationManager::class,
            'medidas' => RelationManagers\MedidasRelationManager::class,


        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticulos::route('/'),
            'create' => Pages\CreateArticulos::route('/create'),
            'edit' => Pages\EditArticulos::route('/{record}/edit'),
        ];
    }
}
