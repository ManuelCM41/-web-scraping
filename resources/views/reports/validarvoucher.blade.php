<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/img/icono.png" type="image/png">
    <link rel="shortcut icon" href="/img/icono.png">

    <title>SCRAPING - PÁGINA Virtual</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/styles.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #fff;
            color: #000;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<?php

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$options = new QROptions([
    'version'    => 5,      // Tamaño del QR (del 1 al 40)
    'eccLevel'   => QRCode::ECC_L, // Nivel de corrección de errores (L, M, Q, H)
    'outputType' => QRCode::OUTPUT_IMAGE_PNG,  // Salida como imagen PNG
    'imageBase64'=> true,  // No usar base64
]);

?>

<body>
    <div class="max-w-[600px] m-auto rounded-lg flex justify-center items-center">
        <div class="w-full bg-white rounded-xl pt-5 border">
            <div class="flex items-center justify-center">
                <img class="max-w-[200px]" src="{{ asset('img/infotel.png') }}" alt="Infotel Perú">
            </div>
            <div class="flex items-center justify-center">
                <samp class="text-xl font-bold pt-5 ">
                    SCRAPING BUSINESS SAC
                </samp>
            </div>
            <div class="flex items-center justify-center pt-4">
                <div class="flex flex-col text-center">
                    <samp>
                        Página de Scraping
                    </samp>
                    <samp>
                        Correo electrónico: scrapingperu@gmail.com
                    </samp>
                    <samp>
                        Teléfono: +51 981141413
                    </samp>
                    <samp>
                        R.U.C. Nº: 20448517170
                    </samp>
                    <samp class="font-bold">
                        {{ $proforma->tipo }}
                    </samp>
                    <samp class="font-bold">
                        {{ $proforma->numero }}
                    </samp>
                </div>
            </div>
            <div class="px-5">
                <samp class="pt-5">
                    {{ $fechaFormateada }}
                </samp>
                <div class="border border-gray-950 p-0 m-0"></div>

                <samp class="flex flex-row gap-2 pt-2">
                    <h5 class="font-bold"> Cliente: </h5>
                    <h5>{{ $cliente->name }} {{ $cliente->paterno }} {{ $cliente->materno }}</h5>
                </samp>
                <samp class="flex flex-row gap-2">
                    <h5 class="font-bold"> DNI : </h5>
                    <h5>{{ $cliente->document }}</h5>
                </samp>

                <samp class="flex flex-row gap-2">
                    <h5 class="font-bold"> CÓDIGO DE COMPRA : </h5>
                    <h5>{{ $proforma->numero ?? 'NO HAY REGISTRO' }}</h5>
                </samp>

                <table class="w-full table-auto my-4">
                    <thead class=" text-black border-t-2 border-t-black border-b-[3px] border-b-black">
                        <tr class="text-center text-xs font-bold">
                            <td scope="col" class="px-2 text-center">NOMBRE</td>
                            <td scope="col" class="px-2 text-center">N° DNI</td>
                            <td scope="col" class="px-2 text-center">FECHA</td>
                            <td scope="col" class="px-2 text-center">TOTAL</td>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 bg-white border-b-[3px] border-b-black">
                        <tr class="text-sm font-medium text-gray-900 hover:bg-gray-100">
                            <td class="px-2 pt-[2px] text-center">{{ $proforma->cliente->name }}</td>
                            <td class="px-2 pt-[2px] text-center">{{ $proforma->cliente->document }}</td>
                            <td class="px-2 pt-[2px] text-center">{{ $proforma->cliente->fecha }}.00
                            </td>
                            <td class="px-2 pt-[2px] text-center">{{ $proforma->total }}.00</td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <div class="grid grid-cols-3">
                        <samp class="text-start"></samp>
                        <samp class="text-center"></samp>
                        <samp class="text-end">SUB TOTAL S/. {{ $proforma->total }}.00</samp>
                    </div>
                    <div class="grid grid-cols-3">
                        <samp class="text-start"></samp>
                        <samp class="text-center"></samp>
                        <samp class="text-end">I.G.V S/. 00.00</samp>
                    </div>
                    <div class="grid grid-cols-3 font-bold">
                        <samp class="text-start"></samp>
                        <samp class="text-center"></samp>
                        <samp class="text-end">TOTAL S/. {{ $proforma->total }}.00</samp>
                    </div>
                </div>
                <div class="py-10 flex w-full gap-2 uppercase">
                    <samp class="font-bold">IMPORTE EN LETRAS</samp>
                    <samp>{{ $totalEnLetras }}</samp>
                </div>
                <div class="flex items-center justify-center pt-4 pb-5">
                    <div class="flex flex-col text-center">
                        <div class="flex flex-col text-center">
                            <div class="flex justify-center items-center pb-5">
                                <samp>
                                    <img src="{!! (new QRCode($options))->render(route('voucher.visualizar', ['id' => $proforma->id])) !!}" alt="QR Code" />
                                </samp>
                            </div>
                        </div>

                        <samp>
                            ¡Gracias por su preferencia!
                        </samp>
                        <samp>
                            https://peru.com.pe
                        </samp>
                    </div>
                </div>
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
