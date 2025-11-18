@php
    // Obtener el ID del pedido desde la URL o usar un valor por defecto
    $pedidoId = request()->route('record') ?? null;
@endphp

<div class="space-y-4" x-data="filtroProveedor()">
    <!-- Filtro por proveedor -->
    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-4">
            <label for="filtro_proveedor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Filtrar por Proveedor
            </label>
            <select 
                id="filtro_proveedor" 
                name="filtro_proveedor"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                x-model="filtroProveedor"
                x-on:change="aplicarFiltro($event.target.value)"
            >
                <option value="">Seleccionar proveedor para filtrar referencias</option>
                <template x-for="proveedor in proveedores" :key="proveedor.id">
                    <option :value="proveedor.id" x-text="proveedor.nombre"></option>
                </template>
            </select>
        </div>
        
        <!-- Campo oculto para mantener el estado del filtro -->
        <input type="hidden" name="filtro_proveedor_activo" x-model="filtroProveedor">
        
        <!-- Botón para limpiar filtro -->
        <div class="col-span-2 flex items-end">
            <button 
                type="button"
                class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                x-show="filtroProveedor"
                x-on:click="limpiarFiltro()"
            >
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Limpiar Filtro
            </button>
        </div>
        
        <!-- Indicador de referencias filtradas -->
        <div class="col-span-6 flex items-end">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-medium">Referencias Mostradas:</span>
                <span id="referencias_filtradas" x-text="indicadorTexto"></span>
            </div>
        </div>
    </div>
</div>

<script>
function filtroProveedor() {
    return {
        filtroProveedor: '',
        proveedores: [],
        indicadorTexto: 'Todas las referencias',
        
        init() {
            this.cargarProveedores();
        },
        
        async cargarProveedores() {
            try {
                // Obtener el ID del pedido desde la URL
                const urlParts = window.location.pathname.split('/');
                const pedidoId = urlParts[urlParts.length - 2];
                
                if (!pedidoId || isNaN(pedidoId)) {
                    console.warn('No se pudo obtener el ID del pedido');
                    return;
                }
                
                // Hacer una petición AJAX para obtener los proveedores del pedido
                const response = await fetch(`/api/pedidos/${pedidoId}/proveedores`);
                if (response.ok) {
                    this.proveedores = await response.json();
                } else {
                    console.error('Error al cargar proveedores');
                }
            } catch (error) {
                console.error('Error al cargar proveedores:', error);
            }
        },
        
        aplicarFiltro(proveedorId) {
            if (!proveedorId) {
                this.mostrarTodasLasReferencias();
                return;
            }
            
            // Ocultar todas las referencias primero
            const referencias = document.querySelectorAll('[data-referencia-item]');
            referencias.forEach(ref => {
                ref.style.display = 'none';
            });
            
            // Mostrar solo las referencias del proveedor seleccionado
            const referenciasProveedor = document.querySelectorAll(`[data-proveedor-id='${proveedorId}']`);
            referenciasProveedor.forEach(ref => {
                ref.style.display = 'block';
            });
            
            // Actualizar el indicador
            this.actualizarIndicador(proveedorId);
        },
        
        mostrarTodasLasReferencias() {
            const referencias = document.querySelectorAll('[data-referencia-item]');
            referencias.forEach(ref => {
                ref.style.display = 'block';
            });
            
            this.actualizarIndicador(null);
        },
        
        limpiarFiltro() {
            this.filtroProveedor = '';
            this.mostrarTodasLasReferencias();
        },
        
        actualizarIndicador(proveedorId) {
            if (!proveedorId) {
                this.indicadorTexto = 'Todas las referencias';
            } else {
                const select = document.getElementById('filtro_proveedor');
                const option = select.options[select.selectedIndex];
                this.indicadorTexto = `Referencias del proveedor: ${option.text}`;
            }
        }
    }
}
</script>
