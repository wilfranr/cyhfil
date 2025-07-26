<div class="text-center">
    @if($hasImage)
        <img src="{{ $imageUrl }}" alt="Foto del sistema" class="max-w-full max-h-96 mx-auto">
    @else
        <p class="text-gray-500">No hay foto disponible para este sistema.</p>
    @endif

    <!-- Información adicional debajo de la foto -->
    <div class="mt-4 text-gray-700">
        <p><strong>Nombre:</strong> {{ $sistema->nombre ?? 'No disponible' }}</p>
        <p><strong>Descripción:</strong> {{ $sistema->descripcion ?? 'No disponible' }}</p>
    </div>
</div>
