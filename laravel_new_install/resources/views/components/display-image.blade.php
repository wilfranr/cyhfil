<!-- resources/views/components/forms/display-image.blade.php -->
@props(['imageUrl'])

@if ($imageUrl)
    <div class="p-4 text-center">
        <img src="{{ asset('storage/' . $imageUrl) }}" alt="Imagen de referencia" class="w-40 mx-auto rounded-lg shadow-lg">
    </div>
@else
    <p>No hay imagen disponible.</p>
@endif
