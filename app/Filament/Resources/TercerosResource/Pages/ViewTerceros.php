<?php

namespace App\Filament\Resources\TercerosResource\Pages;

use Filament\Resources\Pages\ViewPage;
use App\Filament\Resources\TercerosResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewTerceros extends ViewRecord
{
  protected static string $resource = TercerosResource::class;
  
  public function getTitle(): string | Htmlable
  {
    $record = $this->getRecord();

    return $record->nombre;
  }  
  
  public function getActions(): array
  {
    return [
      Action::make('edit')->label('Editar Tercero')->icon('heroicon-o-pencil')->url( function ($record) {
        return TercerosResource::getUrl('edit', ['record' => $record->id]);
      }),
      // 'edit' => TercerosResource\Pages\EditTerceros::class,
      // 'delete' => TercerosResource\Pages\DeleteTerceros::class,
    ];
  }
    
}