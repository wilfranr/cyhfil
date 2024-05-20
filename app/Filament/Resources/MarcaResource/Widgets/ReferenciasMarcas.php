<?php

namespace App\Filament\Resources\MarcaResource\Widgets;

use App\Filament\Resources\MarcaResource\Pages\ListMarcas;
use App\Models\Marca;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\TableWidget as Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;



class ReferenciasMarcas extends Widget
{
    // use InteractsWithPageTable;
    public ?Model $record = null;

    // protected function getTablePage(): string
    // {
    //     return ListMarcas::class;
    // }
    

    
}
