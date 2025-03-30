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

{{-- Encabezado empresa --}}
<div class="empresa">
  <div class="logo">
    <img src="{{ public_path('storage/' . $empresaActiva->logo_dark) }}" alt="Logo" width="160px" height="20px">
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
      <small>{{ $empresaActiva->city->name }}, {{ $empresaActiva->country->name }}</small>
    </div>
  </div>
</div>

<h1 class="cotizacion">Cotización <span class="cot_id text-red">COT{{ $id }}</span></h1>

<table>
  <tr>
    <td colspan="8"><strong>Elaborada por: </strong>{{ $pedido->user->name }}</td>
    <td colspan="2"><strong>Fecha: </strong>{{ $cotizacion->created_at->format('Y-m-d') }}</td>
    <td colspan="2"><strong>Validez: </strong>{{ $cotizacion->created_at->addDays(9)->format('Y-m-d') }}</td>
  </tr>
  <tr>
    <td colspan="10"><strong>Razón Social: </strong>{{ $cliente->nombre }}</td>
    <td colspan="2"><strong>{{ $cliente->tipo_documento }} </strong>{{ $cliente->numero_documento }}</td>
  </tr>
  <tr>
    <td colspan="7"><strong>Dirección: </strong>{{ $cliente->direccion }}</td>
    <td><strong>Ciudad: </strong>{{ $ciudad_cliente }}</td>
    <td><strong>Teléfono: </strong>{{ $cliente->telefono }}</td>
    <td colspan="3"><strong>Email: </strong>{{ $cliente->email }}</td>
  </tr>
  <tr>
    <td colspan="8"><strong>Contacto: </strong>{{ $contacto->nombre ?? 'Sin contacto asignado' }}</td>
  </tr>
  <tr>
    <td colspan="8"><strong>Máquina: </strong>{{ $tipo_maquina }}</td>
    <td colspan="2"><strong>Modelo: </strong>{{ $maquina->modelo }}</td>
    <td colspan="2"><strong>Serie: </strong>{{ $maquina->serie }}</td>
  </tr>
</table>

{{-- Artículos --}}
<div class="articulos">
  <h3>Artículos</h3>
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
      @php $totalGeneral = 0; @endphp

      @foreach ($pedidoReferencia as $ref)
      @if ($ref->estado === 1)
      @php
      $referencia = $ref->referencia;
      $articulo = $referencia->articuloReferencia->first()?->articulo;
      @endphp

      @foreach ($ref->proveedores->where('estado', 1) as $proveedor)
      @php
      $totalGeneral += $proveedor->valor_total;
      $marca = \App\Models\Lista::find($proveedor->marca_id);
      @endphp
      <tr>
        <td>{{ $ref->mostrar_referencia == 0 ? 'N/A' : $referencia->referencia }}</td>
        <td>{{ $articulo->descripcionEspecifica ?? 'Sin descripción' }}</td>
        <td>{{ $proveedor->cantidad }}</td>
        <td>{{ $marca->nombre ?? 'N/A' }}</td>
        <td class="{{ $proveedor->Entrega == 'Programada' ? 'bg-yellow' : '' }}">
          {{ $proveedor->Entrega == 'Programada' ? $proveedor->dias_entrega . ' días' : $proveedor->Entrega }}
        </td>
        <td>${{ number_format($proveedor->valor_unidad, 2, ',', '.') }}</td>
        <td>${{ number_format($proveedor->valor_total, 2, ',', '.') }}</td>
      </tr>
      @endforeach
      @endif
      @endforeach

      {{-- Totales --}}
      <tr>
        <td colspan="6" class="text-right">Subtotal:</td>
        <td>${{ number_format($totalGeneral, 2, ',', '.') }}</td>
      </tr>
      <tr>
        <td colspan="6" class="text-right">IVA (19%):</td>
        <td>${{ number_format($totalGeneral * 0.19, 2, ',', '.') }}</td>
      </tr>
      <tr>
        <td colspan="6" class="text-right text-red">Total:</td>
        <td class="text-red">${{ number_format($totalGeneral * 1.19, 2, ',', '.') }}</td>
      </tr>
    </tbody>
  </table>
</div>


{{-- Footer --}}
<div class="footer">
  <p>
    <strong>
      CONSIGNAR A NOMBRE DE {{ $empresaActiva->nombre }}S.A.S.<br>
      CUENTA DE AHORROS NO. 073514564 DEL BANCO DE BOGOTÁ<br>
      CUENTA DE AHORROS NO. 108-000011-20 DE BANCOLOMBIA
    </strong>
  </p>
  <p>
    <small>
      Cotización válida hasta la fecha establecida. Sujeta a venta previa. La garantía del producto corresponde a la del
      fabricante. No nos hacemos responsables por defectos de fabricación o instalación. Los tiempos de entrega son
      estimados, sujetos a retrasos por fuerza mayor. Para más información contáctanos: 801 2642 (Bogotá) o
      importacioneseinversionescyh@gmail.com
    </small>
  </p>
  <img src="{{ public_path('images/proveedores.png') }}" width="100%">
</div>