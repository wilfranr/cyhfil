<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferenciaResource\Pages;
use App\Filament\Resources\ReferenciaResource\RelationManagers;
use App\Models\Articulo;
use App\Models\Lista;
use App\Models\Referencia;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\GlobalSearch\Actions\Action;

class ReferenciaResource extends Resource
{
    protected static ?string $model = Referencia::class;

    protected static ?string $navigationIcon = 'heroicon-s-clipboard-document-list';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'referencia';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [

            'Artículo' =>  $articulo = Articulo::query()->where('id', $record->articulo_id)->pluck('definicion')->first(),
            'Marca' => $marca = Lista::query()->where('id', $record->marca_id)->pluck('nombre')->first(),
        ];
    }




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('referencia')
                    ->label('Referencia')
                    ->unique('referencias', 'referencia', ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('articulo')
                    ->label('Artículo')
                    ->placeholder('Seleccione un artículo relacionado')
                    ->options(function () {
                        // Obtener todas las referencias con sus artículos relacionados
                        return \App\Models\Referencia::with('articulo')
                            ->get()
                            ->mapWithKeys(function ($referencia) {
                                // Mostrar la referencia y el artículo relacionado
                                $articuloDefinicion = $referencia->articulo->definicion ?? 'Sin artículo';
                                return [$referencia->id => "{$referencia->referencia} - {$articuloDefinicion}"];
                            });
                    })
                    ->getOptionLabelUsing(function ($value) {
                        // Mostrar correctamente la concatenación en edición
                        $referencia = \App\Models\Referencia::with('articulo')->find($value);
                        if ($referencia) {
                            $articuloDefinicion = $referencia->articulo->definicion ?? 'Sin artículo';
                            return "{$referencia->referencia} - {$articuloDefinicion}";
                        }
                        return $value; // Retorna el valor si no se encuentra
                    })
                    ->reactive() // Detecta cambios en tiempo real
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            // Asocia automáticamente el artículo relacionado con la referencia seleccionada
                            $referencia = \App\Models\Referencia::find($state);
                            if ($referencia) {
                                $set('articulo_id', $referencia->articulo_id);
                            }
                        }
                    })
                    ->afterStateHydrated(function ($state, $set, $get) {
                        // Configura el estado inicial para mostrar la concatenación en edición
                        $articuloId = $get('articulo_id'); // Obtener el ID del artículo relacionado
                        $referencia = \App\Models\Referencia::where('articulo_id', $articuloId)->with('articulo')->first();

                        if ($referencia) {
                            $articuloDefinicion = $referencia->articulo->definicion ?? 'Sin artículo';
                            $set('articulo', $referencia->id); // Selecciona la referencia correspondiente
                        }
                    })
                    ->searchable()
                    ->preload()
                    ->live(),
                Forms\Components\Hidden::make('articulo_id')->required(),

                Forms\Components\Select::make('marca_id')
                    ->label('Marca')
                    ->options(
                        \App\Models\Lista::where('tipo', 'Marca')->pluck('nombre', 'id')->toArray()
                    )
                    ->createOptionForm(function () {
                        return [
                            TextInput::make('nombre')
                                ->label('Nombre')
                                ->required()
                                ->placeholder('Nombre del fabricante'),
                            FileUpload::make('foto')
                                ->label('Foto')
                                ->image()
                        ];
                    })
                    ->createOptionUsing(function ($data) {
                        $marca = Lista::create([
                            'tipo' => 'Marca',
                            'nombre' => ucwords($data['nombre']),
                            'foto' => $data['foto'] ?? null,
                        ]);

                        return $marca->id;
                    })
                    ->searchable()
            ]);
        //modal

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('referencia')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('articulo.descripcionEspecifica')
                    ->label('Artículo')
                    ->sortable()
                    ->searchable(),
                    //->url(fn($record) => \App\Filament\Resources\ArticulosResource::getUrl('edit', ['record' => $record->articulo_id]))
                    //->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
