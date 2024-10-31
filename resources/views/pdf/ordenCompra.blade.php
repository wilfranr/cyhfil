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
        <img src="{{ public_path('storage/' . $empresaActiva->logo) }}" alt="Logo" width="60px" height="60px">
    </div>
    <div class="empresa-datos">
        <div class="empresa-header">
            <h1>{{ $empresaActiva->nombre }}</h1>
            <h4>NIT {{ $empresaActiva->nit }}</h4>
        </div>
        <div class="empresa-body">
            <small>{{ $empresaActiva->direccion }}</small><br>
            <small>TELÉFONO: {{ $empresaActiva->telefono }} - {{ $empresaActiva->celular }}</small><br>
            <small>{{ $empresaActiva->email }}</small><br>
            @php
                $pais = App\Models\Country::find($empresaActiva->country_id);
                $ciudad = App\Models\City::find($empresaActiva->city_id);
            @endphp
            <small>{{ $ciudad->name }}, {{ $pais->name }}</small>
        </div>
    </div>
</div>


<h1 class="cotizacion">Orden de Compra <span class="cot_id text-red">OC{{ $id }}</span></h1>
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

            </tbody>
        </table>
    </div>
</div>

