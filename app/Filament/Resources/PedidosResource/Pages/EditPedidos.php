<?php

namespace App\Filament\Resources\PedidosResource\Pages;

use App\Filament\Resources\PedidosResource;
use App\Models\{User, PedidoReferenciaProveedor};
use Barryvdh\DomPDF\Facade\Pdf;
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
        $rol = Auth::user()->roles->first()->name;
        if ($rol == 'Analista') {
            return [
                Action::make('Guardar Cambios')->action('save')->color('primary'),
                Action::make('Enviar a costeo')->action('changeStatus')->color('info'),
                
            ];
        } else {
            return [
                
                Actions\DeleteAction::make(),
                Action::make('Guardar Cambios')->action('save')->color('primary'),
                Action::make('GenerateCotización')
                    ->label('Generar Cotización')->color('success')
                    ->action('generarCotizacion'),
            ];
        }
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



        $cotizacion = new \App\Models\Cotizacion();
        $cotizacion->pedido_id = $this->record->id;
        $cotizacion->tercero_id = $this->record->tercero_id;

        $cotizacion->save();

        $cotizacion_id = $cotizacion->id;
        // dd($cotizacion_id);


        //traer referencias asociadas al pedido
        // for ($i=0; $i < count($this->record->referencias); $i++) { 
        //     $referencia_id = $this->record->referencias[$i]->id;
        //     // dd($referencia_id);
        //     $referencia_pedido_proveedor = \App\Models\PedidoReferenciaProveedor::where('pedido_id', $referencia_id)->first();
        //     // dd($referencia_pedido_proveedor->id);
        //     $cotizacion_referencia = new \App\Models\CotizacionReferenciaProveedor();
        //     $cotizacion_referencia->cotizacion_id = $cotizacion->id;
        //     $cotizacion_referencia->pedido_referencia_proveedor_id = $referencia_pedido_proveedor->id;

        //     $cotizacion_referencia->save();

        // }



        return redirect()->route('pdf.cotizacion', ['id' => $cotizacion_id]);
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
