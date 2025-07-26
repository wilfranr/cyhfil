<div class="text-center">
  @if($hasImage)
      <img src="{{ $imageUrl }}" alt="Foto de la máquina" class="max-w-full max-h-96 mx-auto">
  @else
      <p class="text-gray-500">No hay foto disponible para esta máquina.</p>
  @endif

  <!-- Información adicional debajo de la foto -->
  <div class="mt-4 text-gray-700">
      <p><strong>Modelo:</strong> {{ $maquina->modelo ?? 'No disponible' }}</p>
      <p><strong>Serie:</strong> {{ $maquina->serie ?? 'No disponible' }}</p>
      <p><strong>Arreglo:</strong> {{ $maquina->arreglo ?? 'No disponible' }}</p>
      <p><strong>Fabricante:</strong> {{ $maquina->fabricantes->nombre ?? 'No disponible' }}</p>
      
  </div>
</div>
