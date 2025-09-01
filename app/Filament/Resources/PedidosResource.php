<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PedidosResource\Pages;
use App\Filament\Resources\PedidosResource\RelationManagers;
use App\Filament\Resources\PedidosResource\Forms\{ClienteForm, InfoSections, ReferenciasForm};
use App\Models\{Pedido, User};
use Filament\Forms\Form;
use Filament\Forms\Components\{Wizard, Hidden};
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Section;

class PedidosResource extends Resource
{
    protected static ?string $model = Pedido::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 0;

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        $rol = $user->roles->first()->name;

        if ($rol == 'Logistica') {
            return Pedido::query()->where('estado', 'Aprobado')->count();
        } elseif ($rol == 'Analista') {
            return Pedido::query()->where('estado', 'Nuevo')->count();
        } elseif ($rol == 'Vendedor') {
            return Pedido::query()->where('user_id', $user->id)->count();
        } else {
            return Pedido::query()->count();
        }
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $rol = $user->roles->first()->name;

        if ($rol == 'Analista') {
            return parent::getEloquentQuery()->where('estado', 'Nuevo');
        } elseif ($rol == 'Logistica') {
            return parent::getEloquentQuery()->where('estado', 'Aprobado');
        } elseif ($rol == 'Vendedor') {
            return parent::getEloquentQuery()->where('user_id', $user->id);
        } else {
            return parent::getEloquentQuery();
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')->default(auth()->user()->id),
                ...InfoSections::getSections(),
                Wizard::make([
                    ClienteForm::getStep(),
                    ReferenciasForm::getBulkStep(),
                    ReferenciasForm::getStep(),
                ])->columnSpanFull()->hiddenOn('edit'),
                Section::make('Referencias')
                    ->schema([
                        // Botones de selección masiva y comparación - Solo visibles al editar
                        \Filament\Forms\Components\Actions::make([
                            \Filament\Forms\Components\Actions\Action::make('selectAll')
                                ->label('Seleccionar todas las referencias')
                                ->action(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                                    $referencias = $get('referencias') ?? [];
                                    \Log::info('SelectAll action - Referencias encontradas:', ['count' => count($referencias)]);
                                    
                                    foreach ($referencias as $index => $item) {
                                        $set("referencias.{$index}.estado", true);
                                    }
                                    
                                    \Filament\Notifications\Notification::make()
                                        ->title('Referencias seleccionadas')
                                        ->body('Todas las referencias han sido seleccionadas.')
                                        ->success()
                                        ->send();
                                })
                                ->color('success')
                                ->button()
                                ->size('sm'),
                            
                            \Filament\Forms\Components\Actions\Action::make('deselectAll')
                                ->label('Deseleccionar todas las referencias')
                                ->action(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                                    $referencias = $get('referencias') ?? [];
                                    \Log::info('DeselectAll action - Referencias encontradas:', ['count' => count($referencias)]);
                                    
                                    foreach ($referencias as $index => $item) {
                                        $set("referencias.{$index}.estado", false);
                                    }
                                    
                                    \Filament\Notifications\Notification::make()
                                        ->title('Referencias deseleccionadas')
                                        ->body('Todas las referencias han sido deseleccionadas.')
                                        ->success()
                                        ->send();
                                })
                                ->color('danger')
                                ->button()
                                ->size('sm'),
                            
                            // Botón de comparación de proveedores
                            \Filament\Forms\Components\Actions\Action::make('compararProveedores')
                                ->label('📊 Comparar Proveedores')
                                ->icon('heroicon-o-chart-bar')
                                ->action(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                                    $referencias = $get('referencias') ?? [];
                                    $referenciasConMultiplesProveedores = self::getReferenciasConMultiplesProveedores($referencias);
                                    
                                    if (empty($referenciasConMultiplesProveedores)) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('No hay referencias para comparar')
                                            ->body('Selecciona referencias que tengan múltiples proveedores cotizando.')
                                            ->warning()
                                            ->send();
                                        return;
                                    }
                                    
                                    // Generar datos y mostrar notificación
                                    $datos = self::generarDatosComparativos($referenciasConMultiplesProveedores);
                                    
                                    // Guardar datos en sesión
                                    session(['cuadro_comparativo_data' => $datos]);
                                    
                                    \Filament\Notifications\Notification::make()
                                        ->title('Cuadro comparativo generado')
                                        ->body('Los datos han sido preparados. Usa el botón "Ver comparación" en la interfaz.')
                                        ->success()
                                        ->send();
                                })
                                ->color('info')
                                ->button()
                                ->size('sm')
                                ->visible(fn(\Filament\Forms\Get $get) => self::hayReferenciasConMultiplesProveedores($get('referencias'))),
                            
                            // Botón para abrir el modal de comparación
                            \Filament\Forms\Components\Actions\Action::make('verComparacion')
                                ->label('Ver comparación')
                                ->icon('heroicon-o-eye')
                                ->action(function () {
                                    // Verificar si hay datos en sesión
                                    if (!session('cuadro_comparativo_data')) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('No hay datos para mostrar')
                                            ->body('Primero genera el cuadro comparativo.')
                                            ->warning()
                                            ->send();
                                        return;
                                    }
                                    
                                    // Abrir modal usando JavaScript
                                    $this->js("
                                        window.dispatchEvent(new CustomEvent('open-modal', {
                                            detail: {
                                                id: 'cuadro-comparativo-modal',
                                                data: " . json_encode(session('cuadro_comparativo_data')) . "
                                            }
                                        }));
                                    ");
                                })
                                ->color('warning')
                                ->button()
                                ->size('sm')
                                ->visible(fn() => session('cuadro_comparativo_data') !== null),
                        ])
                        ->alignCenter()
                        ->columnSpanFull(),
                        
                        // Repeater de referencias
                        ReferenciasForm::getReferenciasRepeater(),
                    ])->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('tercero.nombre')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Vendedor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Nuevo' => 'info',
                        'En_Costeo' => 'warning',
                        'Cotizado' => 'success',
                        'Aprobado' => 'success',
                        'Rechazado' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPedidos::route('/'),
            'create' => Pages\CreatePedidos::route('/create'),
            'edit' => Pages\EditPedidos::route('/{record}/edit'),
        ];
    }
    
    // Métodos auxiliares para el cuadro comparativo de proveedores
    public static function hayReferenciasConMultiplesProveedores(array $referencias): bool
    {
        foreach ($referencias as $referencia) {
            if (isset($referencia['proveedores']) && count($referencia['proveedores']) > 1) {
                return true;
            }
        }
        return false;
    }
    
    public static function getReferenciasConMultiplesProveedores(array $referencias): array
    {
        return array_filter($referencias, function ($referencia) {
            return isset($referencia['proveedores']) && count($referencia['proveedores']) > 1;
        });
    }
    

    
    public static function generarDatosComparativos(array $referencias): array
    {
        $datos = [];
        
        foreach ($referencias as $referencia) {
            if (isset($referencia['proveedores']) && count($referencia['proveedores']) > 1) {
                $datos[] = [
                    'referencia_nombre' => $referencia['referencia']['nombre'] ?? 'N/A',
                    'proveedores' => collect($referencia['proveedores'])
                        ->map(function ($proveedor) {
                            return [
                                'marca' => $proveedor['marca'] ?? 'N/A',
                                'tiempo_entrega' => $proveedor['tiempo_entrega'] ?? 'N/A',
                                'cantidad' => $proveedor['cantidad'] ?? 'N/A',
                                'costo' => $proveedor['costo_cotizado'] ?? 0,
                                'estado' => $proveedor['estado_proveedor'] ?? false,
                            ];
                        })
                        ->sortBy('costo') // Ordenar por precio
                        ->values()
                        ->toArray()
                ];
            }
        }
        
        return $datos;
    }
}
