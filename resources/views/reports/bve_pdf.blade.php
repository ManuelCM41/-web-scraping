<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boleta de Venta Electrónica</title>
</head>
<style>
    .bve_first_section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-family: Arial, Helvetica, sans-serif;
        gap: 1.5rem;
        margin: 0 auto;
        padding-bottom: 1.5rem;
    }

    .bvefs_content_01 {
        display: grid;
        grid-template-columns: repeat(2, 500px);
        gap: 2rem;
    }

    .bvefsc_01_img {
        width: 20rem;
        height: auto;
        text-align: center;
    }

    .bvefsc_01_div {
        line-height: 0.6rem;
    }

    .bvefs_content_02 {
        display: grid;
        text-align: center;
        gap: 2rem;
        border: 1px solid black;
        border-radius: 0.8rem;
    }

    .bve_second_section {
        max-width: 1032px;
        margin: 0 auto;
        border: 1px solid #a8a7a7;
        border-radius: 0.8rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
        font-family: Arial, Helvetica, sans-serif;
    }

    .bvess_table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .bvess_th,
    .bvess_td {
        padding: 8px;
        text-align: left;
    }

    .bvess_th {
        font-weight: 700;
        font-size: 1.2rem;
        text-align: center;
    }

    .bvess_td {
        background-color: #f2f2f2;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
        text-align: center;
    }

    .bvess_div {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        font-size: 18px;
        font-weight: bold;
        text-align: end;
        padding-right: 2rem;
    }

    .bve_third_section {
        max-width: 1032px;
        margin: 0 auto;
        overflow: hidden;
        font-family: Arial, Helvetica, sans-serif;
    }

    .bvets_content_01 {
        display: flex;
        border: 1px solid #a8a7a7;
        border-radius: 0.8rem;
        gap: 1.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        justify-content: space-between;
    }

    .bvets_content_02 {
        display: grid;
        grid-template-columns: repeat(2, 200px 100%);
        border: 1px solid #a8a7a7;
        border-radius: 0.8rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .bve_footer {
        text-align: center;
    }
</style>

<body>
    {{-- FOMRATO A4 -> START --}}
    <div class="bve_first_section">
        <div class="bvefs_content_01">
            <div style="display: grid; align-items: center; justify-content: center">
                <img class="bvefsc_01_img" src="{{ asset('img/infotel.png') }}" alt="Logo INFOTELPERÚ" />
            </div>
            <div
                style="
              line-height: 1rem;
              text-align: center;
              border: 1px solid black;
              border-radius: 0.8rem;
              padding: 1rem 1.5rem;
              background-color: rgb(230, 227, 227);
            ">
                <h1>R.U.C N° 2025454121205</h1>
                <h1>BOLETA DE VENTA ELECTRÓNICA</h1>
                <h1>B003-52</h1>
            </div>
        </div>
        <div class="bvefs_content_01">
            <div class="bvefsc_01_div">
                <h1>EMPRESA DE EJEMLPO</h1>
                <h3 style="font-weight: 300">Dirección de la Empresa</h3>
                <h3 style="font-weight: 300">MI NOMBRE COMERCIAL</h3>
                <h3 style="font-weight: 300">
                    Correo Electrónico: example@company.com
                </h3>
                <h3 style="font-weight: 300">Teléfono: +51 997415650</h3>
            </div>
            <div
                style="
              line-height: 0rem;
              border: 1px solid black;
              border-radius: 0.8rem;
              padding: 0.8rem;
              overflow: hidden;
            ">
                <div
                    style="
                display: grid;
                grid-template-columns: repeat(2, 180px 100%);
                margin-bottom: -0.3rem;
              ">
                    <h3>Fecha de Emisión</h3>
                    <h3 style="font-weight: 300">: {{ $fechaFormateada }}</h3>
                </div>
                <div
                    style="
                display: grid;
                grid-template-columns: repeat(2, 180px 100%);
                margin-bottom: -0.3rem;
              ">
                    <h3>Señor(es)</h3>
                    <h3 style="font-weight: 300">: {{ $cliente->name }} {{ $cliente->paterno }} {{ $cliente->materno }}
                    </h3>
                </div>
                <div
                    style="
                display: grid;
                grid-template-columns: repeat(2, 180px 100%);
                margin-bottom: -0.3rem;
              ">
                    <h3>DNI</h3>
                    <h3 style="font-weight: 300">: {{ $cliente->document }}</h3>
                </div>
                <div style="display: grid; grid-template-columns: repeat(2, 180px 180px)">
                    <h3>Dirección</h3>
                    <h3 style="font-weight: 300">: {{ $cliente->address }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="bve_second_section">
        <table class="bvess_table">
            <tr>
                <th class="bvess_th">Cantidad</th>
                <th class="bvess_th">Unidad</th>
                <th class="bvess_th">Código</th>
                <th class="bvess_th">Nombre del Producto</th>
                <th class="bvess_th">P.U</th>
                <th class="bvess_th">Total</th>
            </tr>
            @foreach ($detalles as $detalle)
                <tr>
                    <td class="bvess_td">{{ $detalle->cantidad }}.00</td>
                    <td class="bvess_td">Unidad</td>
                    <td class="bvess_td">P00001</td>
                    <td class="bvess_td" style="text-align: start;
                    padding-left: 5rem;">
                        {{ $detalle->descripcion }}</td>
                    <td class="bvess_td">{{ $detalle->punitario }}.00</td>
                    <td class="bvess_td">{{ $detalle->importe }}.00</td>
                </tr>
            @endforeach
        </table>
        <div class="bvess_div">
            <h3 style="font-weight: 300; margin: 0px 0px 10px 0px">SUB TOTAL</h3>
            <h3 style="font-weight: 300; margin: 0px 0px 10px 0px">S/.</h3>
            <h3 style="font-weight: 300; margin: 0px 0px 10px 0px; width: 70px;">{{ $proforma->total }}.00</h3>
        </div>
        <div class="bvess_div">
            <h3 style="font-weight: 300; margin: 0px 0px 10px 0px">I.G.V</h3>
            <h3 style="font-weight: 300; margin: 0px 0px 10px 0px">S/.</h3>
            <h3 style="font-weight: 300; margin: 0px 0px 10px 0px; width: 70px;">00.00</h3>
        </div>
        <div class="bvess_div">
            <h3 style="margin: 0px 0px 20px 0px">TOTAL</h3>
            <h3 style="margin: 0px 0px 20px 0px">S/.</h3>
            <h3 style="margin: 0px 0px 20px 0px; width: 70px;">{{ $proforma->total }}.00</h3>
        </div>
    </div>
    <div class="bve_third_section">
        <div class="bvets_content_01">
            <div style="display: flex; gap: 1rem">
                <h3 style="margin: 0px">IMPORTE EN LETRAS :</h3>
                <h3 style="margin: 0px; font-weight: 300">
                    CUARENTA CON 00/100 SOLES
                </h3>
            </div>

            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d7/Commons_QR_code.png" alt="" />
        </div>
        <div class="bvets_content_02">
            <h3 style="margin: 0px">OBSERVACIONES :</h3>
            <h3 style="margin: 0px; font-weight: 300; width: 780px">
                Wasda sd dasdas dasd asdajbskdjaks daksjda sd asdajbskdjaksda asd asda
                sdasdasdasdasdasd solidasdasdasdasdasda.
            </h3>
        </div>
    </div>
    <div class="bve_footer">
        Representación impresa de la Factura electrónica. Consulte su documento en
        este enlace: <strong><a href="">Dale Click</a></strong>
    </div>
    {{-- FOMRATO A4 -> END --}}
</body>

</html>
