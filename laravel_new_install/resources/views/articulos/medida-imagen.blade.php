@php
    // Intentamos obtener el artículo según el contexto
    $articulo = isset($this) && method_exists($this, 'getOwnerRecord') ? $this->getOwnerRecord() : ($getRecord()?->articulo ?? null);
    $fotoMedida = $articulo?->foto_medida;
@endphp

@if($fotoMedida)
    <div x-data="{ open: false }" class="p-4 text-center">
        <p class="font-bold text-gray-700">Imagen de Referencia:</p>

        <!-- Miniatura de la Imagen -->
        <div class="inline-block cursor-pointer" @click="open = true">
            <img src="{{ asset('storage/' . $fotoMedida) }}" 
                 alt="Imagen de referencia"
                 class="w-40 mx-auto rounded-lg shadow-lg hover:scale-105 transition-transform duration-200">
        </div>

        <!-- Modal usando `x-teleport="body"` y asegurando el `z-index` más alto -->
        <template x-teleport="body">
            <div x-show="open" 
                 class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-75"
                 style="z-index: 999999 !important;" 
                 @click.outside="open = false" @keydown.escape.window="open = false"
                 x-transition>
                <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl relative"
                     style="z-index: 1000000 !important; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                    <!-- Botón de Cerrar -->
                    <button @click="open = false" class="absolute top-2 right-2 bg-gray-300 text-gray-700 p-1 rounded-full">
                        ✕
                    </button>

                    <!-- Imagen Ampliada -->
                    <img src="{{ asset('storage/' . $fotoMedida) }}" 
                         alt="Imagen ampliada"
                         class="max-w-full max-h-[80vh] mx-auto rounded-lg shadow-lg">
                </div>
            </div>
        </template>
    </div>
@endif
