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

    <h1 class="text-center mb-2">REPORTE DE CATEGORIAS</h1>

    <div class="mb-2">
        <span class="text-xs mr-2">Total: {{ $total }} categorias</span>
        <span class="text-xs" style="margin-right: 260px">Usuario: {{ $user }}</span>
        <span class="text-xs mr-2">Fecha: {{ $date }}</span>
        <span class="text-xs">Hora: {{ $hour }}</span>
    </div>

    <table class="w-full">
        <thead>
            <tr class="text-center text-xs font-bold uppercase">
                <th class="px-2">ID</th>
                <th class="px-2">Nombre</th>
                <th class="px-2">Slug</th>
                <th class="px-2">Creacion</th>
                <th class="px-2">Actualizado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr class="text-xs text-gray-600 text-center">
                    <td class="">{{ $category->id }}</td>
                    <td class="p-2">{{ $category->name }}</td>
                    <td class="p-2">{{ $category->slug }}</td>
                    <td class="p-2">{{ $category->created_at }}</td>
                    <td class="p-2">{{ $category->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
