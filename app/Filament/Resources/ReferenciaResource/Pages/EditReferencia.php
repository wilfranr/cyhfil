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
                ->url(function (array $data) {
                    $record = $this->getRecord();
                    return ArticulosResource::getUrl('edit', ['record' => $record->articulo_id]);
                    
                },shouldOpenInNewTab: true),
        ];
    }
    
}
