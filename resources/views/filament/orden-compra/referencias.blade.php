@php
    $referencias = $getRecord()->referencias;
@endphp

<div>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Referencia
                </th>
                <th scope="col" class="px-6 py-3">
                    Cantidad
                </th>
                <th scope="col" class="px-6 py-3">
                    Valor Unitario
                </th>
                <th scope="col" class="px-6 py-3">
                    Valor Total
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($referencias as $referencia)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $referencia->referencia }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $referencia->pivot->cantidad }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $referencia->pivot->valor_unitario }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $referencia->pivot->valor_total }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
