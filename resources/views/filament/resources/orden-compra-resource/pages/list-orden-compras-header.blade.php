<div class="filament-header space-y-2">
    <div class="flex items-center justify-between">
        <h1 class="filament-header-heading text-2xl font-bold tracking-tight dark:text-white">
            Órdenes de Compra
        </h1>
    </div>
    
    <div class="filament-header-description">
        <p class="text-gray-500 dark:text-gray-400">
            Gestión de órdenes de compra agrupadas por proveedor y cliente
        </p>
    </div>
</div>

<div class="mt-6 space-y-6">
    @foreach($ordenes as $proveedorId => $ordenesProveedor)
        @php
            $proveedor = $ordenesProveedor->first()->proveedor;
            $totalProveedor = $ordenesProveedor->sum('valor_total');
        @endphp
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-primary-500 rounded-full"></div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $proveedor->nombre }}
                        </h3>
                        <span class="px-2 py-1 text-xs font-medium bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-100 rounded-full">
                            {{ $ordenesProveedor->count() }} {{ Str::plural('orden', $ordenesProveedor->count()) }}
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-300">Total Proveedor</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            ${{ number_format($totalProveedor, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($ordenesProveedor->groupBy('tercero_id') as $clienteId => $ordenesCliente)
                    @php
                        $cliente = $ordenesCliente->first()->tercero;
                        $totalCliente = $ordenesCliente->sum('valor_total');
                    @endphp
                    
                    <div class="px-6 py-3 bg-gray-25 dark:bg-gray-750">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-700 dark:text-white">{{ $cliente->nombre }}</h4>
                            <span class="text-sm text-gray-500 dark:text-gray-200">
                                ${{ number_format($totalCliente, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($ordenesCliente as $orden)
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            OC-{{ $orden->id }}
                                        </span>
                                        <div class="w-4 h-4 rounded-full border-2 border-white dark:border-gray-800 shadow-sm" 
                                             style="background-color: {{ $orden->color }}"
                                             title="{{ match($orden->color) {
                                                 '#FFFF00' => 'En proceso',
                                                 '#00ff00' => 'Entregado',
                                                 '#ff0000' => 'Cancelado',
                                                 default => 'Desconocido'
                                             } }}">
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-2 text-sm mb-4">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">Pedido:</span>
                                            <span class="text-gray-900 dark:text-white">#{{ $orden->pedido_id }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">Entrega:</span>
                                            <span class="text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($orden->fecha_entrega)->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400 font-medium">Valor:</span>
                                            <span class="text-gray-900 dark:text-white font-semibold">
                                                ${{ number_format($orden->valor_total, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Referencias de la Orden -->
                                    <div class="mb-4">
                                        <h5 class="text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wide mb-2">
                                            Referencias a Comprar
                                        </h5>
                                        @if($orden->referencias && $orden->referencias->count() > 0)
                                            <div class="space-y-2">
                                                @foreach($orden->referencias as $referencia)
                                                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded text-xs">
                                                        <span class="text-gray-700 dark:text-white font-medium">
                                                            {{ $referencia->referencia }}
                                                        </span>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-gray-500 dark:text-gray-200">
                                                                {{ $referencia->pivot->cantidad }} uds
                                                            </span>
                                                            <span class="text-gray-600 dark:text-white font-medium">
                                                                ${{ number_format($referencia->pivot->valor_unitario, 0, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-xs text-gray-400 dark:text-gray-300 italic p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                                Sin referencias asociadas
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('filament.admin.resources.orden-compras.edit', $orden) }}" 
                                           class="inline-flex items-center px-3 py-2 text-xs font-medium text-primary-700 dark:text-primary-400 bg-primary-100 dark:bg-primary-900 rounded-lg hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Editar
                                        </a>
                                        <a href="#" 
                                           class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
