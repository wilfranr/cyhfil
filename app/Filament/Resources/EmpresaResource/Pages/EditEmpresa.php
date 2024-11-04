<?php

namespace App\Filament\Resources\EmpresaResource\Pages;

use App\Filament\Resources\EmpresaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Empresa;
use Filament\Notifications\Notification;

class EditEmpresa extends EditRecord
{
    protected static string $resource = EmpresaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Activar')
                ->label('Activar Empresa')
                ->action(fn () => $this->record->update(['estado' => true]))
                ->color('success')
                ->icon('heroicon-o-check')
                ->requiresConfirmation()
                ->modalHeading('¿Está seguro de activar esta empresa?')
                ->modalDescription('Activar esta empresa desactivará todas las demás empresas. ¿Desea continuar?')
                ->hidden(fn () => (bool) $this->record->estado),
            Actions\DeleteAction::make(),
        ];
    }

    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
