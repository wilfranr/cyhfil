@if ($records = $getState())
<table class="min-w-full divide-y divide-gray-200 text-sm">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-2 text-left">Referencia</th>
            <th class="px-4 py-2 text-left">Sistema</th>
            <th class="px-4 py-2 text-right">Cantidad</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        @foreach ($records as $referencia)
            <tr>
                <td class="px-4 py-2">{{ $referencia->referencia?->referencia ?? '-' }}</td>
                <td class="px-4 py-2">{{ $referencia->sistema?->nombre ?? '-' }}</td>
                <td class="px-4 py-2 text-right">{{ $referencia->cantidad }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
