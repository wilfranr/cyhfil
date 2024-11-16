<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferenciaResource\Pages;
use App\Filament\Resources\ReferenciaResource\RelationManagers;
use App\Models\Articulo;
use App\Models\Lista;
use App\Models\Marca;
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
            'Marca' => $marca = Marca::query()->where('id', $record->marca_id)->pluck('nombre')->first(),
        ];
    }

    
   

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('referencia')
                    ->label('Referencia')
                    ->unique('referencias', 'referencia', ignoreRecord:true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('articulo_id')
                    ->label('Articulo')
                    ->placeholder('Seleccione un artículo o cree uno nuevo')
                    ->options(
                        \App\Models\Articulo::all()->pluck('descripcionEspecifica', 'id')->toArray()
                    )
                    ->createOptionForm(function () {
                        return [
                            Select::make('definicion')
                                    ->label('Definición')
                                    ->options(
                                        Lista::where('tipo', 'Definición de artículo')->pluck('nombre', 'id')
                                    )
                                    ->createOptionForm(function () {
                                        return [
                                            TextInput::make('definicion')
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
                                            // Define la lógica para crear una nueva opción
                                            // Aquí, creamos un nuevo registro en la tabla 'lista'
                                            $lista = Lista::create([
                                                'tipo' => 'Definición de artículo',
                                                'nombre' => ucwords($data['nombre']),
                                                'definicion' => $data['definicion'] ?? null,
                                                'foto' => $data['foto'] ?? null,
                                            ]);
                    
                                            // Devuelve el ID de la nueva opción creada
                                            return $lista->id;
                                        })
                                    
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required(),
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
                                    ->imageEditor(),
                                FileUpload::make('fotoMedidas')
                                    ->label('Foto de Medidas')
                                    ->image()
                                    ->imageEditor(),
                        ];
                    }
                        
                    )
                    ->createOptionUsing(function ($data) {
                        $articulo = Articulo::create([
                            'definicion' => ucwords($data['definicion']),
                            'descripcionEspecifica' => $data['descripcionEspecifica'] ?? null,
                            'peso' => $data['peso'] ?? null,
                            'comentarios' => $data['comentarios'] ?? null,
                            'fotoDescriptiva' => $data['fotoDescriptiva'] ?? null,
                            'fotoMedidas' => $data['fotoMedidas'] ?? null,
                        ]);

                        return $articulo->id;
                    })
                    ->searchable(),
                    
                Forms\Components\Select::make('marca_id')
                    ->label('Fabricante')
                    ->options(
                        \App\Models\Marca::all()->pluck('nombre', 'id')->toArray()
                    )
                    ->createOptionForm(function () {
                        return [
                            TextInput::make('nombre')
                                ->label('Nombre')
                                ->placeholder('Nombre del fabricante'),
                            FileUpload::make('logo')
                                ->label('Logo')
                                ->image()
                                ->imageEditor(),
                        ];
                    })
                    ->createOptionUsing(function ($data) {
                        $marca = Marca::create([
                            'nombre' => ucwords($data['nombre']),
                            'logo' => $data['logo'] ?? null,
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
                Tables\Columns\TextColumn::make('referencia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('articulo.descripcionEspecifica')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Fabricante')
                    ->numeric()
                    ->sortable(),
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
