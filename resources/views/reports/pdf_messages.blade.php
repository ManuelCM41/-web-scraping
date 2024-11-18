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

    <h1 class="text-center mb-2">REPORTE DE MENSAJES</h1>

    <div class="mb-2">
        <span class="text-xs mr-2">Total: {{ $total }} mensajes</span>
        <span class="text-xs" style="margin-right: 260px">Usuario: {{ $user }}</span>
        <span class="text-xs mr-2">Fecha: {{ $date }}</span>
        <span class="text-xs">Hora: {{ $hour }}</span>
    </div>

    <table class="w-full">
        <thead>
            <tr class="text-center text-xs font-bold uppercase">
                <th class="px-2">ID</th>
                <th class="px-2">Nombre</th>
                <th class="px-2">Email</th>
                <th class="px-2">Teléfono</th>
                <th class="px-2">Asunto</th>
                <th class="px-2">Mensaje</th>
                <th class="px-2">Creacion</th>
                <th class="px-2">Actualizado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($messages as $message)
                <tr class="text-xs text-gray-600 text-center">
                    <td class="">{{ $message->id }}</td>
                    <td class="p-2">{{ $message->nombre }}</td>
                    <td class="p-2">{{ $message->telefono }}</td>
                    <td class="p-2">{{ $message->email }}</td>
                    <td class="p-2">{{ $message->asunto }}</td>
                    <td class="p-2">{{ $message->mensaje }}</td>
                    <td class="p-2">{{ $message->created_at }}</td>
                    <td class="p-2">{{ $message->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
