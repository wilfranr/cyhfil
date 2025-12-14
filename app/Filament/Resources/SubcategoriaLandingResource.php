<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubcategoriaLandingResource\Pages;
use App\Filament\Resources\SubcategoriaLandingResource\RelationManagers;
use App\Models\SubcategoriaLanding;
use App\Models\CategoriaLanding;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\ValidationException;

class SubcategoriaLandingResource extends Resource
{
    protected static ?string $model = SubcategoriaLanding::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationLabel = 'Subcategorías Landing';
    
    protected static ?string $modelLabel = 'Subcategoría';
    
    protected static ?string $pluralModelLabel = 'Subcategorías Landing';
    
    protected static ?string $navigationGroup = 'Landing Page';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('categoria_id')
                    ->label('Categoría')
                    ->options(CategoriaLanding::all()->pluck('nombre', 'id'))
                    ->required()
                    ->searchable(),
                    
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre de la subcategoría')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                    
                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpanFull(),
                    
                FileUpload::make('imagen')
                    ->label('Imagen de la subcategoría')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->disk('public')
                    ->directory('images')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->helperText('Sube una imagen para esta subcategoría. Máximo 2MB.')
                    ->columnSpanFull(),
                    
                Forms\Components\Section::make('Configuración del Navbar')
                    ->description('Configura si esta subcategoría se muestra en el menú desplegable del navbar')
                    ->schema([
                        Forms\Components\Toggle::make('mostrar_en_navbar')
                            ->label('Mostrar en Navbar')
                            ->helperText('Solo 4 subcategorías por categoría pueden estar en el navbar')
                            ->reactive()
                            ->default(false)
                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set, $livewire) {
                                if ($state) {
                                    // Si se está activando, verificar si ya hay 4 subcategorías en navbar para esta categoría
                                    $categoriaId = $get('categoria_id');
                                    $recordId = $livewire->record?->id;
                                    
                                    if ($categoriaId) {
                                        $count = SubcategoriaLanding::where('categoria_id', $categoriaId)
                                            ->where('mostrar_en_navbar', true)
                                            ->when($recordId, fn($q) => $q->where('id', '!=', $recordId))
                                            ->count();
                                        
                                        if ($count >= 4) {
                                            // Revertir el cambio
                                            $set('mostrar_en_navbar', false);
                                            
                                            // Mostrar notificación
                                            Notification::make()
                                                ->warning()
                                                ->title('Límite alcanzado')
                                                ->body('Ya hay 4 subcategorías en el navbar para esta categoría. Debes desactivar una antes de agregar otra.')
                                                ->persistent()
                                                ->send();
                                        } else {
                                            // Asignar automáticamente el siguiente orden disponible
                                            $set('orden_navbar', $count + 1);
                                        }
                                    }
                                } else {
                                    // Si se desactiva, limpiar el orden
                                    $set('orden_navbar', null);
                                }
                            }),
                            
                        Forms\Components\TextInput::make('orden_navbar')
                            ->label('Orden en Navbar (1-4)')
                            ->helperText('Define la posición en el menú desplegable (1 = primero)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4)
                            ->default(null)
                            ->visible(fn (Forms\Get $get) => $get('mostrar_en_navbar')),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('categoria.nombre')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Subcategoría')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\ImageColumn::make('imagen_url')
                    ->label('Imagen')
                    ->defaultImageUrl(url('images/no-image.png'))
                    ->size(50)
                    ->square(),
                    
                Tables\Columns\ToggleColumn::make('mostrar_en_navbar')
                    ->label('En Navbar')
                    ->sortable()
                    ->beforeStateUpdated(function ($record, $state) {
                        // Si se está intentando activar, verificar límite
                        if ($state) {
                            $count = SubcategoriaLanding::where('categoria_id', $record->categoria_id)
                                ->where('mostrar_en_navbar', true)
                                ->where('id', '!=', $record->id)
                                ->count();
                            
                            if ($count >= 4) {
                                // Enviar notificación
                                Notification::make()
                                    ->warning()
                                    ->title('Límite alcanzado')
                                    ->body('Ya hay 4 subcategorías en el navbar para esta categoría. Debes desactivar una antes de agregar otra.')
                                    ->persistent()
                                    ->send();
                                
                                // Detener la actualización
                                throw new \Exception('Límite de subcategorías alcanzado');
                            }
                        }
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        // Si se activó, asignar orden automáticamente
                        if ($state) {
                            $count = SubcategoriaLanding::where('categoria_id', $record->categoria_id)
                                ->where('mostrar_en_navbar', true)
                                ->where('id', '!=', $record->id)
                                ->count();
                            
                            $record->orden_navbar = $count + 1;
                            $record->save();
                            
                            Notification::make()
                                ->success()
                                ->title('Subcategoría agregada al navbar')
                                ->body("Posición asignada: {$record->orden_navbar}")
                                ->send();
                        } else {
                            // Si se desactivó, limpiar el orden
                            $record->orden_navbar = null;
                            $record->save();
                            
                            Notification::make()
                                ->info()
                                ->title('Subcategoría removida del navbar')
                                ->send();
                        }
                    }),
                    
                Tables\Columns\TextColumn::make('orden_navbar')
                    ->label('Orden')
                    ->numeric()
                    ->sortable()
                    ->default('-'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria_id')
                    ->label('Categoría')
                    ->options(CategoriaLanding::all()->pluck('nombre', 'id'))
                    ->searchable(),
                    
                Tables\Filters\TernaryFilter::make('mostrar_en_navbar')
                    ->label('Mostrar en Navbar')
                    ->placeholder('Todos')
                    ->trueLabel('Sí')
                    ->falseLabel('No'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('categoria_id', 'asc');
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
            'index' => Pages\ListSubcategoriaLandings::route('/'),
            'create' => Pages\CreateSubcategoriaLanding::route('/create'),
            'edit' => Pages\EditSubcategoriaLanding::route('/{record}/edit'),
        ];
    }
}
