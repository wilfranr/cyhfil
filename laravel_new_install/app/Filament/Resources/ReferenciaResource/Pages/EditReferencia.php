<?php

namespace App\Filament\Resources\ReferenciaResource\Pages;

use App\Filament\Resources\ArticulosResource;
use App\Filament\Resources\ReferenciaResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditReferencia extends EditRecord
{
    protected static string $resource = ReferenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('ver_articulo')
                ->label('Ver Artículo')
                ->icon('heroicon-s-eye')
                ->url(function ($record) {
                    // Obtener el primer artículo relacionado
                    $articulo = $record->articulos->first();

                    // Verificar que el artículo existe antes de generar la URL
                    if ($articulo) {
                        return ArticulosResource::getUrl('edit', ['record' => $articulo->id]);
                    }

                    // Si no existe el artículo, retornar null
                    return null;
                }, shouldOpenInNewTab: true)
                ->tooltip('Ver el artículo relacionado')
                ->visible(fn ($record) => $record->articulos->isNotEmpty()),

        ];
    }
}
