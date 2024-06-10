<div class="">
    {{-- Datos de la empresa --}}
    <div class="">
        <div class="flex justify-center">
            <h3>
                @foreach ($empresas as $empresa)
                @endforeach
                <strong>
                    {{ $empresa->nombre }}
                </strong>
            </h3>
            <h4>
                <strong>
                    {{ $empresa->nit }}
                </strong>
            </h4>
            <strong>COT000{{ $id }}</strong><br>
            <h3>Fecha: {{ $cotizacion->created_at }}</h3>
            <small>TELÉFONO: {{ $empresa->telefono }} - {{ $empresa->celular }}</small><br>
            <small>{{ $empresa->email }}</small><br>
            <small>{{ $empresa->direccion }}</small><br>
            @php
                $pais = App\Models\Country::find($empresa->country_id);
                $ciudad = App\Models\City::find($empresa->city_id);
            @endphp
            <small>{{ $ciudad->name }}, {{ $pais->name }}</small>
        </div>

        <div>
            <h3>Cliente: {{ $pedido->tercero->nombre }}</h3>
            <small>{{ $pedido->tercero->tipo_documento }}: {{ $pedido->tercero->numero_documento }}</small><br>
            <small>Dirección: {{ $pedido->tercero->direccion }}</small><br>
            <small>Teléfono: {{ $pedido->tercero->telefono }}</small><br>
            <small>Email: {{ $pedido->tercero->email }}</small><br>
        </div>
        <div>
            <h3>{{ $tipo_maquina }}</h3>
            <small>Modelo: {{ $maquina->modelo }}</small><br>
            <small>Modelo: {{ $maquina->serie }}</small><br>
        </div>

        <div>
            <h3>Artículos</h3>
            <div>
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">REFERENCIA</th>
                            <th class="px-4 py-2">DESCRIPCIÓN</th>
                            <th class="px-4 py-2">CANTIDAD</th>
                            <th class="px-4 py-2">MARCA</th>
                            <th class="px-4 py-2">ENTREGA</th>
                            <th class="px-4 py-2">VALOR UNI.</th>
                            <th class="px-4 py-2">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalGeneral = 0; // Inicializa el total general a 0 antes de comenzar el bucle principal
                        @endphp

                        @foreach ($pedidoReferencia as $pedidoReferencia)
                            @php
                                $referencia = App\Models\Referencia::find($pedidoReferencia->referencia_id);
                                $pedRefProveedor = App\Models\PedidoReferenciaProveedor::where('pedido_id', $pedidoReferencia->id)->get();
                                $articulo = App\Models\Articulo::find($referencia->articulo_id);
                            @endphp
                            @foreach ($pedRefProveedor as $proveedor)
                                <tr>
                                    <td class="border px-4 py-2">{{ $referencia->referencia }}</td>
                                    <td class="border px-4 py-2">{{ $articulo->definicion }}</td>
                                    <td class="border px-4 py-2">{{ $pedidoReferencia->cantidad }}</td>
                                    @php $marca = App\Models\Marca::find($proveedor->marca_id); @endphp
                                    <td class="border px-4 py-2">{{ $marca->nombre }}</td>
                                    @if ($proveedor->Entrega == 'Programada')
                                        <td class="border px-4 py-2 bg-yellow-200">{{ $proveedor->dias_entrega }} días</td>
                                    @else
                                        <td>{{ $proveedor->Entrega }}</td>
                                    @endif
                                    @php
                                        $valorTotal = $proveedor->valor_total;
                                        $valorUnitario = $valorTotal / $pedidoReferencia->cantidad;
                                        $totalGeneral += $valorTotal; // Suma el valorTotal al total general
                                    @endphp
                                    <td class="border px-4 py-2">{{ $valorUnitario }}</td>
                                    <td class="border px-4 py-2">{{ $valorTotal }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                        <tr>
                            <td colspan="6" class="border px-4 py-2 text-right">Subtotal:</td>
                            <td class="border px-4 py-2">{{ $totalGeneral }}</td>
                        </tr>
                        <tr>
                            <td colspan="6">Iva</td>
                            <td>{{ $totalGeneral * 0.19 }}</td>
                        </tr>
                        <tr>
                            <td colspan="6">Total</td>
                            <td>{{ ($totalGeneral * 0.19)+ $totalGeneral}}</td>
                        </tr>
                    </tbody>
                </table>


                

            </div>
        </div>
