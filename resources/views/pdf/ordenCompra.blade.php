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

<h1 class="cotizacion">Orden de Compra <span class="cot_id text-red">OC000{{ $id }}</span></h1>
<table>
    <tr>
        <td colspan="8"><strong>SEÑOR(ES): </strong>{{ $proveedor->nombre }} </td>
        <td colspan="2"><strong>Fecha de Expedición: </strong>{{ $ordenCompra->fecha_expedicion}}</td>
    </tr>
    <tr>
        <td colspan="8"><strong>Dirección: </strong>{{ $proveedor->direccion }}</td>
        <td colspan="2"><strong>Fecha de Entrega: </strong>{{ $ordenCompra->fecha_entrega}}</td>
    </tr>
    <tr>
        <td colspan="4"><strong>Nit: </strong>{{ $proveedor->numero_documento}}</td>
        <td colspan="4"><strong>Ciudad: </strong>{{ $ciudad_proveedor->name}}</td>
        <td colspan="4"><strong>Teléfono </strong>{{ $proveedor->telefono}}</td>
</table>

<div class="articulos">
    <h3>Artículos</h3>
    <div class="table-responsive">
        <table class="articulos-tabla">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Ítem</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Descuento</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $ordenCompra->referencia->referencia}}</td>
                    <td>{{ $item }}</td>
                    <td>${{ $ordenCompra->valor_unitario }}</td>
                    <td>{{ $ordenCompra->cantidad }}</td>
                    <td>${{ $ordenCompra->descuento }}</td>
                    <td>${{ $ordenCompra->valor_unitario*$ordenCompra->cantidad }}</td>
                </tr>


                {{-- <tr>
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
                </tr> --}}
            </tbody>
        </table>
    </div>
</div>

{{-- <div class="footer">
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
    {{-- <img src="{{ public_path('images/proveedores.png') }}" width="100%">
</div>  --}}
