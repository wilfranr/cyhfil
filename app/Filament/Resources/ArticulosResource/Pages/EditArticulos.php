<?php

namespace App\Filament\Resources\ArticulosResource\Pages;

use App\Filament\Resources\ArticulosResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\EditRecord;

class EditArticulos extends EditRecord
{
    protected static string $resource = ArticulosResource::class;
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\CreateAction::make(),
            
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
}
