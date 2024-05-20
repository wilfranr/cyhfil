<?php

namespace App\Filament\Resources;

use App\Models\Articulo;
use App\Filament\Resources\ArticulosResource\Pages;
use App\Filament\Resources\ArticulosResource\RelationManagers;
use App\Models\Lista;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Modal\Actions\ButtonAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Models\Referencia;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Columns\ImageColumn;
use Filament\Widgets\StatsOverviewWidget as WidgetsStatsOverviewWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArticulosResource extends Resource
{
    protected static ?string $model = Articulo::class;

    protected static ?string $navigationIcon = 'heroicon-m-cube-transparent';

    protected static ?int $navigationSort = 1;

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
                                            ->mapWithKeys(fn ($definicion) => [$definicion->nombre => $definicion->nombre])
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
                                    // ->relationship('listas')
                                    // ->createOptionForm(function () {
                                    //     return [
                                    //         TextInput::make('tipo')
                                    //             ->default('Definición de artículo')
                                    //             ->readonly()
                                    //             ->required(),
                                    //         TextInput::make('definicion')
                                    //             ->default('Definición de artículo')
                                    //             ->readonly()
                                    //             ->required(),
                                    //         TextInput::make('nombre')
                                    //             ->label('Nombre')
                                    //             ->placeholder('Nombre de la definición'),
                                    //         TextInput::make('definicion')
                                    //             ->label('Definición')
                                    //             ->placeholder('Definición del artículo'),
                                    //         FileUpload::make('foto')
                                    //             ->label('Foto')
                                    //             ->image()
                                    //             ->imageEditor(),

                                    //     ];
                                    // })
                                    // ->afterStateUpdated(function (Set $set, Get $get) {
                                    //     $lista = Lista::find($get('definicion'));
                                    //     // dd($lista);
                                    //     // dd($lista->fotoMedida);
                                    //     $set('fotoMedida', $lista->fotoMedida);

                                    // })
                                    // ->afterStateHydrated(function (Set $set, Get $get) {
                                    //     //obtener el id de eta lista
                                    //     $idLista = Lista::find($get('definicion'));
                                    //     dd($idLista);
                                    // })
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
                                    ->label('Peso')
                                    ->placeholder('Peso del artículo'),
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
                                    
                                        ->simple(
                                        Select::make('referencia')
                                        ->label('Referencia')
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
                                        ->searchable(),
                                        )->grid(3),
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
                                            ->searchable(),
                                        TextInput::make('cantidad')
                                            ->label('Cantidad'),
                                        TextInput::make('comentario')
                                            ->label('Comentario'),
                                    ])->grid(3),
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
