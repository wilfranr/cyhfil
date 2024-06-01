<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use App\Models\{User, PedidoReferenciaProveedor};
use Filament\Actions;
use Filament\Actions\Action;
// use Filament\Actions\Modal\Actions\Action;
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
    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
            
            Action::make('Enviar a costeo')->action('changeStatus')->color('info'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('Guardar Cambios')->action('save')->color('primary'),
            Action::make('Generar Cotización')->action('generarCotizacion')->color('success'),
            
        ];
    }


    public function changeStatus()
    {
        $this->record->estado = 'En_Costeo';
        $this->record->save();
        $this->redirect($this->getResource()::getUrl('index'));
    }

    public function generarCotizacion()
    {

        
        $this->record->estado = 'Cotizado';
        $this->record->save();
        
        // referencia_proveedor = 
        
        $cotizacion = new \App\Models\Cotizacion();
        $cotizacion->pedido_id = $this->record->id;
        $cotizacion->tercero_id = $this->record->tercero_id;
        
        $cotizacion->save();
        
        
        //traer referencias asociadas al pedido
        for ($i=0; $i < count($this->record->referencias); $i++) { 
            $referencia_id = $this->record->referencias[$i]->id;
            // dd($referencia_id);
            $referencia_pedido_proveedor = \App\Models\PedidoReferenciaProveedor::where('pedido_id', $referencia_id)->first();
            // dd($referencia_pedido_proveedor->id);
            $cotizacion_referencia = new \App\Models\CotizacionReferenciaProveedor();
            $cotizacion_referencia->cotizacion_id = $cotizacion->id;
            $cotizacion_referencia->pedido_referencia_proveedor_id = $referencia_pedido_proveedor->id;

            $cotizacion_referencia->save();
            
        }
        

        // $referencia_pedido_proveedor = new \App\Models\PedidoReferenciaProveedor();
        // $referencia_pedido_proveedor->pedido_id = $this->record->id;
        // $referencia_pedido_proveedor->referencia_id = $this->record->referencia_id;
        // $referencia_pedido_proveedor->marca_id = $this->record->marca_id;
        // $referencia_pedido_proveedor->tercero_id = $this->record->tercero_id;
        // $referencia_pedido_proveedor->dias_entrega = $this->record->dias_entrega;
        // $referencia_pedido_proveedor->costo_unidad = $this->record->costo_unidad;
        // $referencia_pedido_proveedor->utilidad = $this->record->utilidad;
        // $referencia_pedido_proveedor->valor_total = $this->record->valor_total;
        // $referencia_pedido_proveedor->save();

        


        $this->redirect($this->getResource()::getUrl('index'));
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
