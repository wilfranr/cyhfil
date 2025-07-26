<?php

namespace App\Filament\Resources\ListasResource\Pages;

use App\Filament\Resources\ListasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListas extends EditRecord
{
    protected static string $resource = ListasResource::class;

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
