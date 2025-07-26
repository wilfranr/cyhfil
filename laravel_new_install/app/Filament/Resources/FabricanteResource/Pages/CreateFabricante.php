<?php

namespace App\Filament\Resources\FabricanteResource\Pages;

use App\Filament\Resources\FabricanteResource;
use App\Models\Fabricante;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFabricante extends CreateRecord
{
    protected static string $resource = FabricanteResource::class;
}
