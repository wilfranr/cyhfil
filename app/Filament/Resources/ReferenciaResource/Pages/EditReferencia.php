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
                ->url(function () {
                    $record = $this->getRecord();

                    // Verificar que el artículo existe antes de generar la URL
                    if ($record && $record->articulo_id) {
                        return ArticulosResource::getUrl('edit', ['record' => $record->articulo_id]);
                    }

                    // Si no existe el artículo, puedes manejar el caso aquí (redireccionar o mostrar un mensaje)
                    return null;
                }, shouldOpenInNewTab: true),
        ];
    }
}
