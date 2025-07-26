<?php

namespace App\Filament\Resources\SistemasResource\Pages;

use App\Filament\Resources\SistemasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSistemas extends EditRecord
{
    protected static string $resource = SistemasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
