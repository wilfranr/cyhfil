<?php

namespace App\Filament\Resources\OrdenCompraResource\Pages;

use App\Filament\Resources\OrdenCompraResource;
use App\Models\OrdenCompra;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListOrdenCompras extends ListRecords
{
    protected static string $resource = OrdenCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeader(): ?View
    {
        $ordenes = OrdenCompra::with(['proveedor', 'tercero', 'pedido'])
            ->orderBy('proveedor_id')
            ->orderBy('tercero_id')
            ->get()
            ->groupBy('proveedor_id');

        return view('filament.resources.orden-compra-resource.pages.list-orden-compras-header', [
            'ordenes' => $ordenes
        ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->orderBy('proveedor_id')
            ->orderBy('tercero_id');
    }
}
