<div class="text-center">
  @if($hasImage)
      <img src="{{ $imageUrl }}" alt="Foto del artículo" class="max-w-full max-h-96 mx-auto">
  @else
      <p class="text-gray-500">No hay foto disponible para este artículo.</p>
  @endif
  
  <!-- Información adicional debajo de la foto -->
  <div class="mt-4 text-gray-700">
      <p><strong>Tipo de Artículo:</strong> {{ $articulo->definicion ?? 'No disponible' }}</p>
      <p><strong>Descripción:</strong> {{ $articulo->descripcionEspecifica ?? 'No disponible' }}</p>
      <p><strong>Peso:</strong> {{ $articulo->peso ?? 'No disponible' }}</p>
  </div>
</div>