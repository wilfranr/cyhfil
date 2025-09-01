<div class="space-y-4">
    <div class="bg-gray-50 p-4 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">
            Referencia: {{ $referenciaNombre }}
        </h3>
        <p class="text-sm text-gray-600">
            Comparación de {{ count($proveedores) }} proveedores cotizando
        </p>
    </div>

    @if(count($proveedores) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Proveedor
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Marca
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ubicación
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cantidad
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Días Entrega
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Costo Unidad
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valor Unidad
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Valor Total
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($proveedores as $index => $proveedor)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $proveedor['proveedor_nombre'] }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $proveedor['marca'] }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $proveedor['ubicacion'] === 'Internacional' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $proveedor['ubicacion'] }}
                                </span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ $proveedor['cantidad'] }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ $proveedor['tiempo_entrega'] }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                ${{ number_format($proveedor['costo'], 2) }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                ${{ number_format($proveedor['valor_unidad'], 2) }}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                ${{ number_format($proveedor['valor_total'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
            <h4 class="text-sm font-medium text-blue-900 mb-2">Resumen de la comparación:</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-blue-700 font-medium">Mejor precio:</span>
                    <span class="text-blue-900">${{ number_format(min(array_column($proveedores, 'valor_total')), 2) }}</span>
                </div>
                <div>
                    <span class="text-blue-700 font-medium">Entrega más rápida:</span>
                    <span class="text-blue-900">{{ min(array_column($proveedores, 'tiempo_entrega')) }} días</span>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <div class="text-gray-400 mb-2">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-sm font-medium text-gray-900 mb-1">No hay proveedores para comparar</h3>
            <p class="text-sm text-gray-500">Selecciona al menos dos proveedores activos para ver la comparación.</p>
        </div>
    @endif
</div>
