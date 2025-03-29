<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>

<body>
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
        @php
        $pais = App\Models\Country::find($empresaActiva->country_id);
        $ciudad = App\Models\City::find($empresaActiva->city_id);
        @endphp
        <small>{{ $ciudad->name }}, {{ $pais->name }}</small>
      </div>
    </div>
  </div>

  <div class="content">
    @php
    $direccion = App\Models\Direccion::find($ordenTrabajo->direccion_id);
    $ciudad = App\Models\City::find($direccion->city_id);
    $state = App\Models\State::find($direccion->state_id);
    $pais = App\Models\Country::find($direccion->country_id);
    @endphp
    <p><strong>DESTINATARIO:</strong> {{ $ordenTrabajo->tercero->nombre }}</p>
    <p><strong>NIT/CC:</strong> {{ $ordenTrabajo->tercero->numero_documento }}</p>
    <p><strong>TRANSPORTADORA:</strong> {{ $ordenTrabajo->transportadora->nombre }}</p>
    <p><strong>FORMA DE PAGO:</strong> AL COBRO</p>
    <p><strong>DIRECCION:</strong> {{ $direccion->direccion }}</p>
    <p><strong>TELEFONO:</strong> {{ $ordenTrabajo->telefono }}</p>
    <p><strong>CIUDAD:</strong> {{ $ciudad->name ?? '' }}, {{ $state->name ?? '' }}, {{ $pais->name ?? '' }}</p>
  </div>
  <div class="footer">
    <img src="{{ public_path('images/proveedores.png') }}" width="100%">
  </div>
</body>

</html>