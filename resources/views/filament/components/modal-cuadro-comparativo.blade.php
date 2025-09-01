<div id="cuadro-comparativo-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
    
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-7xl w-full max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">
                    📊 Cuadro Comparativo de Proveedores
                </h3>
                <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6 overflow-auto max-h-[calc(90vh-120px)]">
                <div id="contenido-comparativo">
                    <!-- El contenido se cargará dinámicamente -->
                </div>
            </div>
            
            <!-- Footer -->
            <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
                <button onclick="exportarComparativo()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    📊 Exportar
                </button>
                <button onclick="cerrarModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cerrarModal() {
    document.getElementById('cuadro-comparativo-modal').classList.add('hidden');
}

function exportarComparativo() {
    // Implementar exportación
    console.log('Exportando comparativo...');
    // Aquí se puede implementar la exportación a Excel o PDF
}

// Escuchar evento para abrir modal
document.addEventListener('open-modal', function(event) {
    if (event.detail.id === 'cuadro-comparativo-modal') {
        document.getElementById('cuadro-comparativo-modal').classList.remove('hidden');
        
        // Cargar datos en el modal
        const datos = event.detail.data;
        cargarDatosComparativos(datos);
    }
});

function cargarDatosComparativos(datos) {
    const contenedor = document.getElementById('contenido-comparativo');
    
    if (!datos || datos.length === 0) {
        contenedor.innerHTML = '<p class="text-gray-500 text-center py-8">No hay datos para comparar</p>';
        return;
    }
    
    let html = '';
    
    datos.forEach(item => {
        html += `
            <div class="mb-8">
                <h4 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
                    🔍 ${item.referencia_nombre}
                </h4>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proveedor</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Marca</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entrega</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Costo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
        `;
        
        item.proveedores.forEach((proveedor, index) => {
            const esMejorPrecio = index === 0;
            const filaClase = esMejorPrecio ? 'bg-green-50' : '';
            
            html += `
                <tr class="${filaClase} hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                        Proveedor ${index + 1}
                        ${esMejorPrecio ? '<span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">🏆 Mejor Precio</span>' : ''}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">${proveedor.marca}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${proveedor.tiempo_entrega}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${proveedor.cantidad}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                        $${parseFloat(proveedor.costo).toFixed(2)}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            ${proveedor.estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${proveedor.estado ? '✅ Activo' : '❌ Inactivo'}
                        </span>
                    </td>
                </tr>
            `;
        });
        
        html += `
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    });
    
    contenedor.innerHTML = html;
}
</script>
