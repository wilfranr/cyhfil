<?php

namespace App\Filament\Resources\ArticulosResource\RelationManagers;

use App\Filament\Resources\ReferenciaResource;
use App\Models\ArticuloJuego;
use App\Models\ArticuloReferencia;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class ArticuloReferenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'articuloJuegos'; // Debe coincidir con el método en Articulo
    protected static ?string $title = 'Juegos';
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return is_subclass_of($pageClass, EditRecord::class);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('referencia_id')
                    ->label('Referencia')
                    ->relationship('referencia', 'referencia')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('cantidad')
                    ->numeric()
                    ->required()
                    ->label('Cantidad'),
                Forms\Components\Textarea::make('comentario')
                    ->nullable()
                    ->label('Comentario'),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('referencia.referencia')
                    ->label('Referencia')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->numeric(),
                Tables\Columns\TextColumn::make('comentario')
                    ->label('Comentario')
                    ->limit(50),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Agregar referencia a juego'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Desasociar')
                    ->icon('ri-link-unlink')
                    ->color('danger')
                    ->tooltip('Al desasociar, no se eliminará la referencia, solo se desvinculará del artículo')
                    ->action(function (ArticuloJuego $record) {
                        $record->delete();

                        // Notificación de éxito
                        \Filament\Notifications\Notification::make()
                            ->title('Referencia desasociada correctamente')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(function (ArticuloJuego $record): string {
                        return ReferenciaResource::getUrl('edit', ['record' => $record->referencia_id]);
                    })
            ]);
    }
}