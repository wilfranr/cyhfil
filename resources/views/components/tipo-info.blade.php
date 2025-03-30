@php
use Illuminate\Support\Str;
@endphp

<div class="space-y-6 text-white">

  {{-- Título --}}
  <h2 class="text-2xl font-bold">{{ $tipo->descripcionEspecifica }}</h2>

  {{-- Contenedor de datos principales --}}
  <div class="bg-gray-900 p-6 rounded-lg grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
    {{-- Información del artículo --}}
    <div class="space-y-3">
      <div>
        <p class="text-sm text-gray-400">Tipo</p>
        <p class="text-lg font-medium">{{ $tipo->definicion }}</p>
      </div>
      <div>
        <p class="text-sm text-gray-400">Descripción específica</p>
        <p class="text-lg font-medium">{{ $tipo->descripcionEspecifica }}</p>
      </div>
      <div>
        <p class="text-sm text-gray-400">Peso (Kg)</p>
        <p class="text-lg font-medium">{{ $tipo->peso }}</p>
      </div>
    </div>

    {{-- Foto descriptiva --}}
    @if ($tipo->fotoDescriptiva)
    <div class="text-center">
      <p class="text-sm text-gray-400 mb-2">Foto Descriptiva</p>
      <img src="{{ Storage::url($tipo->fotoDescriptiva) }}" alt="Foto Descriptiva"
        class="rounded-lg shadow max-h-64 mx-auto">
    </div>
    @endif
  </div>

  {{-- Tabla de referencias cruzadas --}}
  @if ($tipo->referencias->isNotEmpty())
  <div class="bg-gray-900 p-6 rounded-lg">
    <h3 class="text-lg font-semibold mb-4 text-white">Referencias Cruzadas</h3>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm text-gray-300">
        <thead class="bg-gray-800 text-gray-400 uppercase text-xs">
          <tr>
            <th class="px-4 py-2 text-left">Referencia</th>
            <th class="px-4 py-2 text-left">Marca</th>
            <th class="px-4 py-2 text-left">Copiar</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          @foreach ($tipo->referencias as $referencia)
          @php
          $btnId = 'btn-' . Str::uuid();
          @endphp
          <tr>
            <td class="px-4 py-2">{{ $referencia->referencia ?? 'N/A' }}</td>
            <td class="px-4 py-2">{{ $referencia->marca->nombre ?? 'Sin marca' }}</td>
            <td class="px-4 py-2">
              <button id="{{ $btnId }}" onclick="
                                            const btn = document.getElementById('{{ $btnId }}');
                                            navigator.clipboard.writeText('{{ $referencia->referencia }}');
                                            btn.innerText = '✅ Copiado';
                                            btn.classList.add('text-green-400');
                                            setTimeout(() => {
                                                btn.innerHTML = `<svg xmlns='http://www.w3.org/2000/svg' class='w-4 h-4 inline mr-1' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 16h8M8 12h8m-6-4h6M4 6h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z' /></svg>Copiar`;
                                                btn.classList.remove('text-green-400');
                                            }, 2000);
                                        " class="text-blue-400 hover:text-blue-200 text-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 16h8M8 12h8m-6-4h6M4 6h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z" />
                </svg>
                Copiar
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif
</div>