<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferenciaResource\Pages;
use App\Filament\Resources;
use App\Models\Articulo;
use App\Models\ArticuloReferencia;
use App\Models\Lista;
use App\Models\Referencia;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class ReferenciaResource extends Resource
{
    protected static ?string $model = Referencia::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'referencia';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('articulos');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('referencia')
                    ->label('Referencia')
                    ->unique('referencias', 'referencia', ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Select::make('articulo_id')
                    ->label('Artículo')
                    ->relationship('articulos', 'descripcionEspecifica')
                    ->options(
                        Articulo::query()
                            ->join('articulos_referencias', 'articulos.id', '=', 'articulos_referencias.articulo_id')
                            ->join('referencias', 'articulos_referencias.referencia_id', '=', 'referencias.id')
                            ->selectRaw("articulos.id, CONCAT(referencias.referencia, ' - ', articulos.descripcionEspecifica) as full_description")
                            ->pluck('full_description', 'id')
                    )
                    ->createOptionForm(function () {
                        return [
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
                                        Hidden::make('tipo')
                                            ->default('Definición de artículo')
                                            ->required(),
                                        TextInput::make('nombre')
                                            ->label('Nombre')
                                            ->placeholder('Nombre de la definición'),
                                        TextInput::make('definicion')
                                            ->label('Descripción de definición')
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
                                ->createOptionAction(function (ActionsAction $action) {
                                    $action->modalHeading('Nueva Definición de Artículo'); // Personaliza el encabezado
                                    $action->modalWidth('lg'); // Ajusta el ancho del modal (opcional)
                                })
                                ->searchable()
                                ->preload()
                                ->live()
                                ->required(),
                            FileUpload::make('foto_medida')
                                ->label('Foto de la medida')
                                ->image()
                                ->imageEditor(),
                            TextInput::make('descripcionEspecifica')
                                ->label('Decripción específica')
                                ->placeholder('Descripción específica del artículo')
                                ->required(),
                            TextInput::make('peso')
                                ->label('Peso (gr)')
                                ->placeholder('Peso del artículo en gramos')
                                ->numeric(),
                            Textarea::make('comentarios')
                                ->label('Comentarios')
                                ->placeholder('Comentarios del artículo'),
                            FileUpload::make('fotoDescriptiva')
                                ->label('Foto descriptiva')
                                ->image()
                                ->imageEditor()
                                ->openable(),
                        ];
                    })
                    ->createOptionUsing(function ($data) {

                        $articulo = Articulo::create($data);

                  
                        return $articulo->id;
                    })
                    ->createOptionAction(function (Action $action) {
                        $action->modalHeading('crear Artículo'); 
                        $action->modalWidth('lg');
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search): array {
                        return Articulo::query()
                            ->join('articulos_referencias', 'articulos.id', '=', 'articulos_referencias.articulo_id')
                            ->join('referencias', 'articulos_referencias.referencia_id', '=', 'referencias.id')
                            ->selectRaw("articulos.id, CONCAT(referencias.referencia, ' - ', articulos.descripcionEspecifica) as full_description")
                            ->where(function ($query) use ($search) {
                                $query->where('articulos.descripcionEspecifica', 'like', "%{$search}%")
                                    ->orWhere('referencias.referencia', 'like', "%{$search}%");
                            })
                            ->limit(50) // Limitar el número de resultados
                            ->pluck('full_description', 'articulos.id')
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        // Obtiene la etiqueta cuando ya hay un valor seleccionado
                        return Articulo::query()
                            ->join('articulos_referencias', 'articulos.id', '=', 'articulos_referencias.articulo_id')
                            ->join('referencias', 'articulos_referencias.referencia_id', '=', 'referencias.id')
                            ->selectRaw("CONCAT(referencias.referencia, ' - ', articulos.descripcionEspecifica) as full_description")
                            ->where('articulos.id', $value)
                            ->pluck('full_description')
                            ->first();
                    }),



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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('ID'),
                TextColumn::make('referencia')
                    ->searchable()
                    ->sortable()
                    ->label('Referencia'),
                TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('articulos.descripcionEspecifica')
                    ->label('Artículo Relacionado')
                    ->limit(50)
                    ->searchable()
                    ->sortable()
                    ->url(
                        fn($record) => $record->articuloReferencia->first()?->articulo_id
                            ? ArticulosResource::getUrl('edit', ['record' => $record->articuloReferencia->first()->articulo_id])
                            : null
                    )
                    ->placeholder('Sin artículo relacionado'), // Muestra este texto si no hay artículo asociado



                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Creado')
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Actualizado')
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferencias::route('/'),
            'create' => Pages\CreateReferencia::route('/create'),
            'edit' => Pages\EditReferencia::route('/{record}/edit'),
        ];
    }
}
