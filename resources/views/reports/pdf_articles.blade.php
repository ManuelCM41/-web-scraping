<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <title>Reporte PDF</title>
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

    <h1 class="text-center mb-2">REPORTE DE ARTÍCULOS</h1>

    <div class="mb-2">
        <span class="text-xs mr-2">Total: {{ $total }} artículos</span>
        <span class="text-xs" style="margin-right: 570px">Usuario: {{ $user }}</span>
        <span class="text-xs mr-2">Fecha: {{ $date }}</span>
        <span class="text-xs">Hora: {{ $hour }}</span>
    </div>

    <table>
        <thead>
            <tr class="text-center text-xs font-bold uppercase">
                <th class="px-2">ID</th>
                <th class="px-2">Título</th>
                <th class="px-2">Url</th>
                <th class="px-2">Extracto</th>
                <th class="px-2">Categoría</th>
                <th class="px-2">Imagen</th>
                <th class="px-2">Autor</th>
                <th class="px-2">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($articles as $index => $product)
                <tr class="text-xs text-gray-600">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="px-2 text-center">{{ $product->titulo }}</td>
                    <td class="px-2">{{ $product->url }}</td>
                    <td class="px-2">{{ $product->extracto }}</td>
                    <td class="px-2">{{ $product->categoria }}</td>
                    <td class="px-2">{{ $product->imagen }}</td>
                    <td class="px-2">{{ $product->autor }}</td>
                    <td class="px-2">{{ $product->fecha }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
