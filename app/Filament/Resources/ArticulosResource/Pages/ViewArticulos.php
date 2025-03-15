<?php
namespace App\Filament\Resources\ArticulosResource\Pages;

use Filament\Resources\Pages\ViewPage;
use App\Filament\Resources\ArticulosResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewArticulos extends ViewRecord
{
  protected static string $resource = ArticulosResource::class;
  
  public function getTitle(): string | Htmlable
  {
    $record = $this->getRecord();

    return $record->descripcionEspecifica;
  }

  protected function getActions(): array
  {
    return [
      Action::make('edit')->label('Edit Articulo')->icon('heroicon-o-pencil')->url( function ($record) {
        return ArticulosResource::getUrl('edit', ['record' => $record->id]);
      }),
      // 'edit' => ArticulosResource\Pages\EditArticulos::class,
      // 'delete' => ArticulosResource\Pages\DeleteArticulos::class,
    ];
  }
    
}