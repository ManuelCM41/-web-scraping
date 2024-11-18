<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Venta</title>
    <style>
        @page {
            size: A4 landscape;
            /* Cambia la orientación a horizontal */
            margin: 0cm;
            /* Establece el margen de 2cm en todos los lados */
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            background-color: rgba(0, 128, 255, 0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: transparent;
            /* Fondo transparente */
        }


        main {
            width: 100%;
            padding: 20px;
            margin: auto;
            margin-top: 100px;
        }

        .padre {
            padding: 15px;
            text-align: center;
            width: 30%;
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            background-color: rgba(0, 128, 255, 0.5);
        }

        .container {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 10px;
            color: white;
        }

        .crud {
            display: flex none;
        }

        .titulo {
            font-size: 10px;
            color: white;
        }

        .logo img {
            width: 100%;
            height: auto;
            font-size: 10px;
            color: white;
            padding: 10px 10px 0px 10px;
        }

        .footer {
            padding-top: 10px;
            font-size: 5px;
            color: white;
        }

        .voucher-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .voucher-header,
        .voucher-data {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 0.5rem;
        }

        .voucher-header {
            background-color: #f2f2f2;
        }

        .voucher-data:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="padre" style="border:solid 1px black;">
            <div class="logo">
                <img src="{{ asset('img/infotel.png') }}" alt="Infotel Perú">
            </div>
            <div class="titulo"style="padding-top:10px; padding-bottom:10px;">
                <h1 style="margin: 0; font-family: 'Courier New', Courier, monospace;">{{ $proforma->tipo }}</h1>
                <h1 style="margin: 0;font-family: 'Courier New', Courier, monospace; font-size: 15px;">
                    {{ $cliente->address }}</h1>
                <h1 style="margin: 0;font-family: 'Courier New', Courier, monospace; font-size: 15px;">
                    {{ $fechaFormateada }}</h1>
            </div>

            <div style="border-bottom: solid 1px white;"></div>
            <h1 style="font-family: 'Courier New', Courier, monospace; font-size: 15px;">LISTA DE PEDIDOS</h1>
            <div style="border-bottom: solid 1px white;"></div>
            <h1
                style="text-align: left; padding: 2px; font-family: 'Courier New', Courier, monospace; font-size: 13px;">
                Nombre: {{ $cliente->name }} {{ $cliente->paterno }} {{ $cliente->materno }}</h1>
            <h1
                style="text-align: left; padding: 2px; font-family: 'Courier New', Courier, monospace; font-size: 13px;">
                Documento: {{ $cliente->tdocumento }} : {{ $cliente->document }}</h1>
            <div style="border-bottom: solid 1px white;"></div>

            <table class="voucher-table">
                <thead>
                    <tr>
                        <th class="voucher-header">CANT.</th>
                        <th class="voucher-header">PRODUCTO</th>
                        <th class="voucher-header">METODO PAGO</th>
                        <th class="voucher-header">P. UNITARIO</th>
                        <th class="voucher-header">IMPORTE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalles as $detalle)
                        <tr>
                            <td class="voucher-data">{{ $detalle->cantidad }}</td>
                            <td class="voucher-data">{{ $detalle->descripcion }}</td>
                            <td class="voucher-data">{{ $proforma->metodo_pago }}</td>
                            <td class="voucher-data">{{ $detalle->punitario }}</td>
                            <td class="voucher-data">{{ $detalle->importe }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="border-bottom: solid 1px white;"></div>
            <div class=""style="text-align:right; padding: 5px;font-size: 15px;">Total a pagar : S/.
                {{ $proforma->total }}</div>
            <div class="footer">
                <h1>Gracias por Confiar en Infotel Perú</h1>
                <h1>https://infotelperu.com/</h1>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            // Imprime el documento
            window.print();
        };
    </script>

</body>

</html>
