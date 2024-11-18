<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>resporte PDF</title>
</head>
<style>
    th,
    td {
        padding: 2px;
        border: 1px solid #ccc;
    }
    th {
        background-color: #e4e4e4;
    }
</style>

<body>

    <h1 class="text-center mb-2">REPORTE DE PAGOS POR YAPE</h1>

    <div class="mb-2">
        <span class="text-xs mr-2">Total: {{ $total }} clientes</span>

        <span class="text-xs mr-2">Fecha: {{ $date }}</span>
        <span class="text-xs">Hora: {{ $hour }}</span>
    </div>

    <table>
        <thead>
            <tr class="text-center text-xs font-bold uppercase">
                <th class="px-2">ID</th>
                <th class="px-2">Codigo</th>
                <th class="px-2">Nombre del cliente</th>
                <th class="px-2">fecha</th>
                <th class="px-2">Numero de voucher</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($yapePayments as $item)
                <tr class="text-xs text-gray-600">
                    <td class="text-center">{{ $item->id }}</td>
                    <td class="px-2 text-center">{{ $item->codigo }}</td>
                    <td class="px-2">{{ $item->name }}</td>
                    <td class="px-2">{{ $item->fecha }}</td>
                    <td class="px-2">{{ $item->voucher->numero }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
