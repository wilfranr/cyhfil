<?php
namespace App\Filament\Resources\MaquinasResource\Pages;

use Filament\Resources\Pages\ViewPage;
use App\Filament\Resources\MaquinasResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;  

class ViewMaquinas extends ViewRecord
{
  protected static string $resource = MaquinasResource::class;
  
  public function getTitle(): string | Htmlable
  {
    $record = $this->getRecord();

    return $record->modelo;
  }
  
  protected function getActions(): array
  {
    return [
      Action::make('edit')->label('Editar Maquina')->icon('heroicon-o-pencil')->url( function ($record) {
        return MaquinasResource::getUrl('edit', ['record' => $record->id]);
      }),
      // 'edit' => MaquinasResource\Pages\EditMaquinas::class,
      // 'delete' => MaquinasResource\Pages\DeleteMaquinas::class,
    ];
  }
}