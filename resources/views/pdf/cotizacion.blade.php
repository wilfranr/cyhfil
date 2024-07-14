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

    .empresa-datos {
        font-size: 12px;
        margin-bottom: 20px;
        text-align: center;
    }

    .articulos {
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 12px;
        margin-bottom: 10px;
    }

    td {
        background-color: #f2f2f2;
        border: 1px solid #fff;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #d6d6d6;
    }

    .text-right {
        text-align: right;
    }

    .cotizacion {
        text-align: center;
        font-size: 16px;
    }

    .cot_id {
        font-size: 20px;
    }

    .text-red {
        color: crimson;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        margin-bottom: 0;
        font-size: 8px;
        text-align: justify;
    }
</style>

<div class="empresa">
    <div class="logo">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" width="60px" height="60px">
    </div>
    <div class="empresa-datos">
        <div class="empresa-header">
            <h1>
                @foreach ($empresas as $empresa)
                @endforeach
                {{ $empresa->nombre }}
            </h1>
            <h4>
                NIT {{ $empresa->nit }}
            </h4>
        </div>
        <div class="empresa-body">
            <small>{{ $empresa->direccion }}</small><br>
            <small>TELÉFONO: {{ $empresa->telefono }} - {{ $empresa->celular }}</small><br>
            <small>{{ $empresa->email }}</small><br>
            @php
                $pais = App\Models\Country::find($empresa->country_id);
                $ciudad = App\Models\City::find($empresa->city_id);
            @endphp
            <small>{{ $ciudad->name }}, {{ $pais->name }}</small>
        </div>
    </div>
</div>

<h1 class="cotizacion">Cotización <span class="cot_id text-red">COT000{{ $id }}</span></h1>
<table>
    <tr>
        <td colspan="8"><strong>Elaborada por: </strong>{{ $pedido->user->name }} </td>
        <td colspan="2"><strong>Fecha: </strong>{{ $cotizacion->created_at->format('Y-m-d') }}</td>
        <td colspan="2"><strong>Validez: </strong>{{ $cotizacion->created_at->addDays(9)->format('Y-m-d') }}</td>
    </tr>
    <tr>
        <td colspan="10"><strong>Razón Social: {{ $cliente->nombre }}</strong></td>
        <td colspan="2"><strong>{{ $cliente->tipo_documento }} </strong>{{ $cliente->numero_documento }}</td>
    </tr>
    <tr>
        <td colspan="7"><strong>Dirección: </strong>{{ $cliente->direccion }}</td>
        <td colspan="1"><strong>Ciudad: </strong>{{ $ciudad_cliente }}</td>
        <td colspan="1"><strong>Telefono: </strong>{{ $cliente->telefono }}</td>
        <td colspan="3"><strong>Email: </strong>{{ $cliente->email }}</td>
    </tr>
    <tr>
        <td colspan="12"><strong>Contacto</strong></td>
    </tr>
    <tr>
        <td colspan="8"><strong>Maquina: </strong>{{ $tipo_maquina }}</td>
        <td colspan="2"><strong>Modelo: </strong>{{ $maquina->modelo }}</td>
        <td colspan="2"><strong>Serie: </strong>{{ $maquina->serie }}</td>
    </tr>
</table>

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
                    @if ($proveedor->estado == 1) 
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
                            <td>${{ $valorUnitario }}</td>
                            <td>${{ $valorTotal }}</td>
                        </tr>
                    @endif
                    @endforeach
                @endforeach

                <tr>
                    <td colspan="6" class="text-right">Subtotal:</td>
                    <td>${{ $totalGeneral }}</td>
                </tr>
                <tr>
                    <td colspan="6" class="text-right">Iva</td>
                    <td>${{ $totalGeneral * 0.19 }}</td>
                </tr>
                <tr>
                    <td colspan="6" class="text-right text-red">Total</td>
                    <td class="text-red">${{ $totalGeneral * 0.19 + $totalGeneral }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="footer">
    <p>
        <strong>
            CONSIGNAR A NOMBRE DE IMPORTACIONES E INVERSIONES CYH S.A.S.
            CUENTA DE AHORROS NO. 073514564 DEL BANCO DE BOGOTA
            CUENTA DE AHORROS NO. 108-000011-20 DE BANCOLOMBIA
        </strong>
    </p>
    <p>
        <small>
            Cotización valida hasta la fecha establecida en el campo "validez". Esta cotización está sujeta a venta
            previa. La garantía del producto ofrecido aquí
            es la misma garantía ofrecida por el fabricante. IMPORTACIONES E INVERSIONES CYH S.A.S. no se hace
            responsable por problemas consecuentes
            por materiales de fabricación y/o instalación deficiente del producto, esto será responsabilidad del cliente
            o el fabricante según corresponda el
            caso. El tiempo de entrega estipulado en la cotización presente es una estimación según condiciones normales
            de transporte o importación, no
            cuenta con retrasos en vuelos, aduanas o casos fortuitos. En caso de cualquier anormalidad en la entrega del
            producto, IMPORTACIONES E
            INVERSIONES CYH SAS informara al cliente. Al comprar el cliente acepta que entiende a cabalidad lo
            establecido en las anteriores líneas, cualquier
            información adicional, favor consultarla en nuestra línea 801 2642 en Bogotá o escribanos un correo a
            importacioneseinversionescyh@gmail.com
        </small>
    </p>
    {{-- insertar imgaen --}}
    <img src="{{ public_path('images/proveedores.png') }}" width="100%">
</div>
