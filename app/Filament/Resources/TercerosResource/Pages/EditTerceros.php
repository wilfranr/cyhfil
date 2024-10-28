<?php

namespace App\Filament\Resources\TercerosResource\Pages;

use App\Filament\Resources\TercerosResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditTerceros extends EditRecord
{
    protected static string $resource = TercerosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Whatsapp Cliente')
                        ->label(function () {
                            $record = $this->getRecord();

                            return $record->nombre;
                        })
                        ->icon('ri-whatsapp-line')
                        ->color('success')
                        ->url(function () {
                            $record = $this->getRecord();
                            // Usa el teléfono del contacto si existe, de lo contrario usa el teléfono del tercero
                            $telefono=$record->telefono;

                            return "https://wa.me/57$telefono";
                        }, shouldOpenInNewTab: true),
            Actions\DeleteAction::make(),

            
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
}
