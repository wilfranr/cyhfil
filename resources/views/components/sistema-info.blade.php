@if($sistema)
    <div style="text-align: center;">
        <img src="{{ asset('storage/' . $sistema->imagen) }}" 
             alt="Imagen de {{ $sistema->nombre }}" 
             style="width: 100px; height: auto; border-radius: 5px;"/>
        <p>{{ $sistema->descripcion }}</p>
    </div>
@else
    <p>Seleccione un sistema.</p>
@endif

