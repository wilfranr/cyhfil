<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: right;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 20px;
            background-color: #f3f3f3;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
        }
        .footer img {
            margin: 0 10px;
            max-width: 80px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="path_to_your_logo/logo.png" alt="Logo">
    </div>
    <div class="content">
        <p><strong>DESTINATARIO:</strong> {{ $ordenTrabajo->tercero->nombre }}</p>
        <p><strong>NIT/CC:</strong> {{ $ordenTrabajo->tercero->nit }}</p>
        <p><strong>TRANSPORTADORA:</strong> {{ $ordenTrabajo->transportadora->nombre }}</p>
        <p><strong>FORMA DE PAGO:</strong> AL COBRO</p>
        <p><strong>DIRECCION:</strong> {{ $ordenTrabajo->direccion }}</p>
        <p><strong>TELEFONO:</strong> {{ $ordenTrabajo->telefono }}</p>
        <p><strong>CIUDAD:</strong> {{ $ordenTrabajo->direccion->city->name ?? '' }}, {{ $ordenTrabajo->direccion->state->name ?? '' }}</p>
    </div>
    <div class="footer">
      <img src="{{ public_path('images/proveedores.png') }}" width="100%">
    </div>
</body>
</html>
