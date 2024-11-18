<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a INFOTELPerú</title>
</head>
<body>
    <h1>Bienvenido a INFOTELPERU, {{ $user->name }}</h1>
    <p>Gracias por registrarte. Aquí están tus datos:</p>
    <ul>
        <li><strong>Nombre:</strong> {{ $user->name }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Apellido Materno:</strong> {{ $user->apellido_materno }}</li>
        <li><strong>Apellido Paterno:</strong> {{ $user->apellido_paterno }}</li>
        <li><strong>Avatar:</strong> <img src="{{ $user->avatar }}" alt="Avatar" width="100"></li>
        <!-- Agrega más campos si es necesario -->
    </ul>
    <p>Tu contraseña temporal es: <strong>{{ $password }}</strong>. Te recomendamos cambiarla lo antes posible.</p>
</body>
</html>
