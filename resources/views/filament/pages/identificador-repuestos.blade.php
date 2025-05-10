<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Analizar Imagen
        </x-filament::button>
    </form>

    @if ($this->respuestaIA)
        <div class="mt-6 p-4 bg-gray-900 text-white rounded-xl shadow border border-gray-700">
            <h2 class="text-lg font-semibold mb-2">Resultado IA:</h2>
            <p>{{ $this->respuestaIA }}</p>
        </div>
    @endif

@if ($this->coincidencias->isNotEmpty())
    <div class="mt-6">
        <h2 class="text-lg font-semibold mb-2">Artículos relacionados encontrados:</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    @foreach ($this->coincidencias as $articulo)
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow border border-gray-700 p-4 flex items-center gap-4">
            @if ($articulo->fotoDescriptiva)
                <img src="{{ Storage::url($articulo->fotoDescriptiva) }}"
                     alt="Foto del artículo"
                     class="w-20 h-20 object-contain rounded-md border border-gray-600" />
            @else
                <div class="w-20 h-20 bg-gray-800 rounded-md flex items-center justify-center text-gray-400 text-xs">
                    Sin imagen
                </div>
            @endif

            <div class="flex-1">
                <h3 class="font-semibold text-white">Artículo #{{ $articulo->id }}</h3>
                <p class="text-sm text-gray-400">{{ $articulo->definicion }}</p>
            </div>

            <a href="{{ route('filament.admin.resources.articulos.edit', $articulo->id) }}"
               class="bg-amber-500 hover:bg-amber-600 text-white text-sm px-4 py-2 rounded transition font-medium">
                Ver
            </a>
        </div>
    @endforeach
</div>
    </div>
@endif
</x-filament::page>

