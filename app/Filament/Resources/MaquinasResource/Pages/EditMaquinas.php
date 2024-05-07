<?php

namespace App\Filament\Resources\MaquinasResource\Pages;

use App\Filament\Resources\MaquinasResource;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;

class EditMaquinas extends EditRecord
{
    protected static string $resource = MaquinasResource::class;

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

    // protected function getTabs(): array
    // {
    //     return [
    //         'Information' => [
    //             Select::make('tipo')
    //                 ->options([
    //                     'excavadora' => 'Excavadora',
    //                     'retroexcavadora' => 'Retroexcavadora',
    //                     'bulldozer' => 'Bulldozer',
    //                     'grua' => 'Grua',
    //                     'montacargas' => 'Montacargas',
    //                     'compactador' => 'Compactador',
    //                     'motoniveladora' => 'Motoniveladora',
    //                     'rodillo' => 'Rodillo',
    //                     'tractor' => 'Tractor',
    //                     'camion' => 'Camion',
    //                     'volqueta' => 'Volqueta',
    //                     'otro' => 'Otro',
    //                 ])
    //                 ->label('Tipo')
    //                 ->searchable()
    //                 ->required(),

    //             Select::make('marca_id')
    //                 ->relationship('marcas', 'nombre')
    //                 ->label('Marca')
    //                 ->preload()
    //                 ->live()
    //                 ->searchable(),

    //             TextInput::make('modelo')
    //                 ->label('Modelo')
    //                 ->required(),

    //             TextInput::make('serie')
    //                 ->label('Serie')
    //                 ->required(),
    //         ],
    //     ];
    // }
}
