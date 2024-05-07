<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\SaveAction;
use Filament\Actions\Modal\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Resources\Pages\EditRecord;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;


class EditPedidos extends EditRecord
{
    protected static string $resource = PedidosResource::class;
    

    protected function beforeSave()
    {
        $this->record->user_id = auth()->user()->id;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
            
    //     ];
    // }

    





    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
