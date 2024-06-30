{{-- Datos de la empresa --}}
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
    }

    h3,
    h4,
    p {
        margin: 5px 0;
    }

    .empresa,
    .cliente,
    .maquina {
        margin-bottom: 20px;
        border: 1px solid #ddd;
        padding: 10px;
    }

    .articulos {
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .text-right {
        text-align: right;
    }

    /* Empresa header en el centro */
    .empresa-header {
        text-align: center;
    }
    
</style>
<div class="empresa-datos">
    <div class="empresa-header">
        <h1>
            @foreach ($empresas as $empresa)
            @endforeach
            {{ $empresa->nombre }}
        </h1>
        <h4>
            <strong>
                NIT {{ $empresa->nit }}
            </strong>
        </h4>
        <small>TELÉFONO: {{ $empresa->telefono }} - {{ $empresa->celular }}</small><br>
        <small>{{ $empresa->email }}</small><br>
        <small>{{ $empresa->direccion }}</small><br>
        @php
            $pais = App\Models\Country::find($empresa->country_id);
            $ciudad = App\Models\City::find($empresa->city_id);
        @endphp
        <small>{{ $ciudad->name }}, {{ $pais->name }}</small>
    </div><br><br>
    <div class="CotizacionId">
        <strong>COT000{{ $id }}</strong><br>
        <small>Fecha: {{ $cotizacion->created_at->format('Y-m-d') }}</small><br>
        <small>Validez: {{ $cotizacion->created_at->addDays(9)->format('Y-m-d') }}</small>
    </div>

    <div class="cliente-info">
        <h3>Cliente: {{ $pedido->tercero->nombre }}</h3>
        <small>{{ $pedido->tercero->tipo_documento }}: {{ $pedido->tercero->numero_documento }}</small><br>
        <small>Dirección: {{ $pedido->tercero->direccion }}</small><br>
        <small>Teléfono: {{ $pedido->tercero->telefono }}</small><br>
        <small>Email: {{ $pedido->tercero->email }}</small><br>
    </div>
    <div class="maquina-info">
        <h3>{{ $tipo_maquina }}</h3>
        <small>Modelo: {{ $maquina->modelo }}</small><br>
        <small>Serie: {{ $maquina->serie }}</small><br>
    </div>

    <div class="articulos">
        <h3>Artículos</h3>
        <div class="table-responsive">
            <table class="articulos-tabla">
                <thead>
                    <tr>
                        <th>REFERENCIA</th>
                        <th>DESCRIPCIÓN</th>
                        <th>CANTIDAD</th>
                        <th>MARCA</th>
                        <th>ENTREGA</th>
                        <th>VALOR UNI.</th>
                        <th>SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalGeneral = 0; // Inicializa el total general a 0 antes de comenzar el bucle principal
                    @endphp

                    @foreach ($pedidoReferencia as $pedidoReferencia)
                        @php
                            $referencia = App\Models\Referencia::find($pedidoReferencia->referencia_id);
                            $pedRefProveedor = App\Models\PedidoReferenciaProveedor::where(
                                'pedido_id',
                                $pedidoReferencia->id,
                            )->get();
                            $articulo = App\Models\Articulo::find($referencia->articulo_id);
                        @endphp
                        @foreach ($pedRefProveedor as $proveedor)
                            <tr>
                                @if ($mostrarReferencia == 0)
                                    <td>N/A</td>
                                @else
                                    <td>{{ $referencia->referencia }}</td>
                                @endif
                                <td>{{ $articulo->definicion }}</td>
                                <td>{{ $pedidoReferencia->cantidad }}</td>
                                @php $marca = App\Models\Marca::find($proveedor->marca_id); @endphp
                                <td>{{ $marca->nombre }}</td>
                                @if ($proveedor->Entrega == 'Programada')
                                    <td class="bg-yellow">{{ $proveedor->dias_entrega }} días</td>
                                @else
                                    <td>{{ $proveedor->Entrega }}</td>
                                @endif
                                @php
                                    $valorTotal = $proveedor->valor_total;
                                    $valorUnitario = $valorTotal / $pedidoReferencia->cantidad;
                                    $totalGeneral += $valorTotal; // Suma el valorTotal al total general
                                @endphp
                                <td>{{ $valorUnitario }}</td>
                                <td>{{ $valorTotal }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                    <tr>
                        <td colspan="6" class="text-right">Subtotal:</td>
                        <td>{{ $totalGeneral }}</td>
                    </tr>
                    <tr>
                        <td colspan="6">Iva</td>
                        <td>{{ $totalGeneral * 0.19 }}</td>
                    </tr>
                    <tr>
                        <td colspan="6">Total</td>
                        <td>{{ $totalGeneral * 0.19 + $totalGeneral }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
