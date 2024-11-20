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

                    // Buscar el contacto principal asociado al tercero
                    $contactoPrincipal = $record->contactos()->where('principal', true)->first();

                    if ($contactoPrincipal) {
                        // Si hay un contacto principal, mostrar su nombre
                        return $contactoPrincipal->nombre;
                    }

                    // Si no hay contacto principal, mostrar "Sin Contacto"
                    return 'Sin Contacto';
                })

                ->icon('ri-whatsapp-line')
                ->color('success')
                ->url(function () {
                    $record = $this->getRecord();

                    // Buscar el contacto principal asociado al tercero
                    $contactoPrincipal = $record->contactos()->where('principal', true)->first();

                    if ($contactoPrincipal) {
                        // Si hay un contacto principal, usamos su número de teléfono
                        $telefono = $contactoPrincipal->telefono;
                        return "https://wa.me/57$telefono";
                    } else {
                        // Si no hay contacto principal ni teléfono del tercero, mostramos "Sin Contacto"
                        return "javascript:void(0);"; // Evita redirigir a un enlace inexistente
                    }
                }, shouldOpenInNewTab: true)
                ->visible(function () {
                    $record = $this->getRecord();

                    // Mostrar solo si hay contacto principal o teléfono del tercero
                    $contactoPrincipal = $record->contactos()->where('principal', true)->first();

                    return $contactoPrincipal || $record->telefono;
                })
                ->tooltip(function () {
                    $record = $this->getRecord();
                    $contactoPrincipal = $record->contactos()->where('principal', true)->first();

                    if ($contactoPrincipal) {
                        return "Teléfono: {$contactoPrincipal->telefono}";
                    } else {
                        return "Sin Contacto Principal Definido";
                    }
                }),
            Actions\DeleteAction::make(),


        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
