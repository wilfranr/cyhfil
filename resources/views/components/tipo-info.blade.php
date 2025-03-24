<div>

  <div class="mb-2">
    <strong>Nombre:</strong> {{ $tipo->nombre }}
  </div>

  @if($tipo->definicion)
  <div class="mb-2">
    <strong>Descripción:</strong> {{ $tipo->definicion }}
  </div>
  @else
  <div class="mb-2 text-gray-500">
    Este tipo no tiene una descripción.
  </div>
  @endif

</div>