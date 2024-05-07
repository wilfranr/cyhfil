<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Select;
use App\Filament\Imports\UserImporter;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Contracts\View\View;
use Filament\Widgets\StatsOverviewWidget as WidgetsStatsOverviewWidget;
use function Laravel\Prompts\text;



class CreatePedidos extends CreateRecord
{
    protected static string $resource = PedidosResource::class;

    //formulario de creación de pedido

    

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
