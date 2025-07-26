<?php

namespace App\Filament\Resources\SistemasResource\Pages;  

use Filament\Resources\Pages\ViewPage;
use App\Filament\Resources\SistemasResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;  

class ViewSistemas extends ViewRecord
{
  protected static string $resource = SistemasResource::class;
  
  public function getTitle(): string | Htmlable
  {
    $record = $this->getRecord();

    return $record->nombre;
  }
  
  protected function getActions(): array
  {
    return [
      Action::make('edit')->label('Editar Sistema')->icon('heroicon-o-pencil')->url( function ($record) {
        return SistemasResource::getUrl('edit', ['record' => $record->id]);
      }),
      // 'edit' => SistemasResource\Pages\EditSistemas::class,
      // 'delete' => SistemasResource\Pages\DeleteSistemas::class,
    ];
  }
}