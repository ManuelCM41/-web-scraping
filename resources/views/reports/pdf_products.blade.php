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

    <h1 class="text-center mb-2">REPORTE DE PRODUCTOS</h1>

    <div class="mb-2">
        <span class="text-xs mr-2">Total: {{ $total }} productos</span>
        <span class="text-xs" style="margin-right: 588px">Usuario: {{ $user }}</span>
        <span class="text-xs mr-2">Fecha: {{ $date }}</span>
        <span class="text-xs">Hora: {{ $hour }}</span>
    </div>

    <table>
        <thead>
            <tr class="text-center text-xs font-bold uppercase">
                <th class="px-2">ID</th>
                <th class="px-2">Nombre</th>
                <th class="px-2">Material</th>
                <th class="px-2">Detalle</th>
                <th class="px-2">Uso</th>
                <th class="px-2">Caracteristicas</th>
                <th class="px-2">Categoria</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr class="text-xs text-gray-600">
                    <td class="text-center">{{ $product->id }}</td>
                    <td class="px-2 text-center">{{ $product->name }}</td>
                    <td class="px-2">{{ $product->material }}</td>
                    <td class="px-2">{{ $product->detail }}</td>
                    <td class="px-2">{{ $product->use }}</td>
                    <td class="px-2 text-center">
                        <div class="space-y-2">
                            <div class="">Largo: {{ $product->large }}.cm</div>
                            <div class="">Alto: {{ $product->height }}.cm</div>
                            <div class="">Ancho: {{ $product->width }}.cm</div>
                        </div>
                    </td>
                    <td class="px-2 text-center">{{ $product->category->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
