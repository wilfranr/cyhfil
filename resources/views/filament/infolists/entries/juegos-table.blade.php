@if (!empty($getState()))
    <div class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
        Juegos
    </div>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-4 py-2">Referencia</th>
                <th class="px-4 py-2">Cantidad</th>
                <th class="px-4 py-2">Comentario</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($getState() as $juego)
                <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-2">
                        <a href="{{ url('/admin/referencias/' . $juego->referencia->id . '/edit') }}" 
                           target="_blank" 
                           class="text-blue-500 hover:underline">
                            {{ $juego->referencia->referencia ?? 'N/A' }}
                        </a>
                    </td>
                    <td class="px-4 py-2">{{ $juego->cantidad ?? '0' }}</td>
                    <td class="px-4 py-2">{{ $juego->comentario ?? 'Sin comentario' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
