@section('content')
    <div class="container p-4">
        {{-- Datos de la empresa --}}
        <div class="row justify-content-between">
            <div class="col text-center">
                <h3>
                    {{-- @foreach ($empresas as $empresa)
                    @endforeach
                    <strong>
                        {{ $empresa->nombre }}
                    </strong> --}}
                    <strong>CYH Importaciones e Inversiones</strong>
                </h3>
                <h4>
                    <strong>
                        800000xxxxxxxx
                    </strong>
                </h4>
                <small>CRA 69D No. 1 - 45 SUR - TORRE 2 APTO 1214</small><br>
                <small>TELÉFONO: 8012642 - 310 331 1634</small><br>
                <small>importacionesyinversionescyh@gmail.com</small><br>
                <small>Bogotá, Colombia}</small>
            </div>
            <div class="col text-right">
                <img src="{{ asset('storage/' . $empresa->logo) }}" alt="logo" width="50%">
            </div>
        </div>
        <div class="text-center">
            <h1>
                <strong>COTIZACIÓN</strong>
            </h1>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="invoice p-3 mb-3">
                        <!-- info cotización -->
                        <div class="row text-muted">
                            <div class="col-4">
                                <h1><strong>COT 000{{ $cotizacion->id }}</strong></h1>

                            </div>
                            <div class="col-4">
                                <p>Elaborada por: <b>{{ $pedido->user->name }}</b></p>
                            </div>
                            <div class="col-4 text-right">

                                <p>Fecha: <b>{{ $cotizacion->created_at->formatLocalized('%d de %B de %Y') }}</b></p>
                                <p>Validez: <b>{{ $cotizacion->created_at->addDays(8)->formatLocalized('%d de %B de %Y') }}</b>
                                </p>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- info cliente -->
                        <div class="row invoice-info border border-4">
                            <div class="col-sm-4 invoice-col">
                                Razón social
                                <address>
                                    <strong>{{ $pedido->tercero->nombre }}</strong><br>
                                    @if ($pedido->tercero->tipo_documento == 'CC')
                                        CC: <b>{{ $pedido->tercero->numero_documento }}</b><br>
                                    @elseif ($pedido->tercero->tipo_documento == 'NIT')
                                        NIT: <b>{{ $pedido->tercero->numero_documento }}</b><br>
                                    @elseif ($pedido->tercero->tipo_documento == 'CE')
                                        CE: <b>{{ $pedido->tercero->numero_documento }}</b><br>
                                    @endif
                                    {{ $pedido->tercero->direccion }}<br>
                                    Bogotá, Colombia<br>
                                    Tel: {{ $pedido->tercero->telefono }}<br>
                                    Email: {{ $pedido->tercero->email }}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                Contacto
                                <address>
                                    @if ($pedido->contacto)
                                        <strong>{{ $pedido->contacto->nombre }}</strong><br>
                                        Tel: {{ $pedido->contacto->telefono }}<br>
                                        Email: {{ $pedido->contacto->email }}
                                    @else
                                        <strong>N/A</strong><br>
                                    @endif
                                </address>
                            </div>
                            {{--  /.col --}}
                            <div class="col-sm-4 invoice-col">
                                Maquina
                                <address>
                                    @if ($pedido->maquinas->count() >= 1)
                                        @foreach ($pedido->maquinas as $maquina)
                                        @endforeach
                                        <b>{{ $maquina->tipo }}</b><br>
                                        Marca: {{ $maquina->marca }}<br>
                                        Modelo: {{ $maquina->modelo }}<br>
                                        Serie: {{ $maquina->serie }}<br>
                                    @endif
                                </address>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row mt-3">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped table-bordered border-dark">
                                    <thead>
                                        <tr>
                                            <th>ÍTEM</th>
                                            <th>CANT</th>
                                            <th>REFERENCIA</th>
                                            <th>DESCRIPCIÓN</th>
                                            <th>MARCA</th>
                                            <th>ENTREGA</th>
                                            <th>VR. UNI</th>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cotizacion->articulos as $index => $articulo)
                                            <tr>
                                                <td>{{ $index+1 }}</td>
                                                <td>{{ $articulo->cantidad }}</td>
                                                <td>{{ $articulo->referencia }}</td>
                                                <td>{{ $articulo->definicion }}</td>
                                                <td>{{ $articulo->marca }}</td>
                                                <td>{{ $articulo->plazo_entrega }}</td>
                                                <td>{{ $articulo->precio_venta / $articulo->cantidad }}
                                                <td>{{ $articulo->precio_venta }}</td>
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-7">
                                <p class="lead text-center fs-6">
                                    CONSIGNAR A NOMBRE DE IMPORTACIONES E INVERSIONES CYH S.A.S.<br>
                                    CUENTA DE AHORROS NO. <b>073514564</b> DEL BANCO DE BOGOTA<br>
                                    CUENTA DE AHORROS NO. <b>108-000011-20</b> DE BANCOLOMBIA
                                </p>
                                
                                
                            </div>
                            <!-- /.col -->
                            <div class="col-5">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td>$ 1.094.800</td>
                                        </tr>
                                        <tr>
                                            <th>Iva (19%)</th>
                                            <td>$ 208.012</td>
                                        </tr>
                                        <tr class="text-danger">
                                            <th class="text-danger">Total:</th>
                                            <td>$ 1.302.812</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <p class="text-muted well well-sm shadow-none text-justify" style="margin-top: 10px;">
                                <small class="text-sm">
                                    Cotización válida hasta la fecha establecida en el campo "validez". Esta cotización
                                    está sujeta a venta previa. La garantía del producto ofrecido aquí es la misma
                                    garantía ofrecida por el fabricante. IMPORTACIONES E INVERSIONES CYH S.A.S. no se hace
                                    responsable por problemas consecuentes por materiales de fabricación y/o
                                    instalación deficiente del producto; esto será responsabilidad del cliente o el
                                    fabricante según corresponda el caso. El tiempo de entrega estipulado en la
                                    cotización presente es una estimación según condiciones normales de transporte o
                                    importación, no cuenta con retrasos en vuelos, aduanas o casos fortuitos. En caso de
                                    cualquier anomalía en la entrega del producto, IMPORTACIONES Y INVERSIONES CYH SAS
                                    informará al cliente. Al comprar, el cliente acepta que entiende cabalmente lo
                                    establecido en las anteriores líneas. Cualquier información adicional, favor
                                    consultarla en nuestra línea 8012642 en Bogotá o escribirnos un correo a
                                    importacionesyinversionescyh@gmail.com
                                </small>
                            </p>
                        </div>
                        <div class="row">

                            <div class="mx-auto">
                                <img src="{{ asset('storage/marcas.png') }}" alt="marcas" width="1000px">
                            </div>
                        </div>

                        <!-- this row will not appear when printing -->
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="{{ route('costeos.costear', $pedido->id) }}" class="btn btn-default"><i
                                        class="fas fa-edit"></i> Editar</a>
                                <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i
                                        class="fas fa-print"></i> Imprimir</a>
                                <button type="button" class="btn btn-success float-right"><i
                                        class="far fa-credit-card"></i>
                                    Enviar
                                </button>
                                <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                    <i class="fas fa-download"></i> Descargar PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.invoice -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection
