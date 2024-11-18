<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nuevo Mensaje</title>
</head>

<body style="font-family: Arial, sans-serif; padding: 20px; margin: 0; padding-top:5px;">
    <div
        style="
        background-image: url('https://drive.google.com/uc?export=view&id=1JJdpzGtm0WNbLtIvIT0GZ92wZk3t46eO&rl');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        padding: 20px;
    ">
        @if ($voucher['status'] == 'PA')
            <div
                style="
                background-color: rgba(255, 255, 255, 0.9);
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            ">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #2258d5; font-size: 16px; font-weight: bold;">¡Tu Pago ha sido Exitoso!</h1>
                    <img style="max-width: 10rem; height: auto; display: block; margin: 0 auto;"
                        src="{{ $message->embed($imagePathLogo) }}" alt="Icono INFOTELPerú">
                </div>
                <div style="font-size: 12px; padding: 1rem 2rem; text-align: center;">
                    <strong>{{ $cliente->name }} {{ $cliente->paterno }} {{ $cliente->materno }}</strong>, tu cambio de Plan se
                    realizó exitosamente, espero disfrutes de los beneficios, te esperamos, hasta la próxima.
                </div>
                <div>
                    <h1 style="font-size: 13px; color: #2258d5; margin-bottom: 10px;">Ticket de Pago</h1>
                    <div
                        style="
                        background-color: #2258d5;
                        text-align: center;
                        padding: 10px;
                        color: white;
                        border-radius: 5px;
                    ">
                        <h1 style="margin: 0; font-size: 13px; font-weight: normal;">Código de Pago</h1>
                        <h2 style="margin: 0; font-size: 16px; font-weight: 700;">{{ $voucher['numero'] }}</h2>
                    </div>
                </div>
                <div>
                    <h1 style="font-size: 13px; color: #2258d5; margin-bottom: 10px;">Estado de Pago</h1>
                    <div
                        style="
                        background-color: #2258d5;
                        text-align: center;
                        padding: 10px;
                        color: white;
                        border-radius: 5px;
                    ">
                        <h1 style="margin: 0; font-size: 13px; font-weight: normal;">Estado</h1>
                        <h2 style="margin: 0; font-size: 16px; font-weight: 700;">
                            @if ($voucher['status'] == 'PA')
                                PAGADO
                            @elseif ($voucher['status'] == 'PE')
                                PENDIENTE
                            @elseif ($voucher['status'] == 'RE')
                                EN REVISION
                            @endif
                        </h2>
                    </div>
                </div>
                <div style="text-align: center;">
                    <div style="text-align: justify; padding: 2rem;">
                        <h1
                            style="
                            font-size: 12px;
                            position: relative;
                            color: #527cdb;
                            float: left;
                            bottom: 2.5rem;
                            right: 15px;
                        ">
                            Monto Pagado</h1>
                        <h2
                            style="
                            position: relative;
                            font-size: 12px;
                            color: #1b55db;
                            font-weight: 800;
                            float: right;
                            bottom: 2.5rem;
                            left: 15px;
                        ">
                            S/. {{ $voucher['total'] }}</h2>
                    </div>
                    <div style="background-color: #b5c6ed96; padding: 1rem;">
                        <h1 style="margin: 0; font-size: 12px; color: #3468e2b7;">Tu Pago ha sido procesado el</h1>
                        <h2 style="margin: 0; font-size: 16px; color: #3468e2;">{{ $fechaFormateada }}</h2>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <img style="max-width: 10rem; height: auto; display: block; margin: 0 auto;"
                        src="{{ $message->embed($qrcodePath) }}" alt="QR Code">
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="{{ route('voucher.visualizar') }}?id={{ $voucher['id'] }}" target="_blank"
                        style="
                            display: inline-block;
                            padding: 10px 20px;
                            font-size: 14px;
                            font-weight: bold;
                            text-align: center;
                            text-decoration: none;
                            background-color: #2258d5;
                            color: white;
                            border-radius: 5px;
                        ">
                        Visualizar voucher
                    </a>
                    {{-- <a href="{{ route('ticket.generate', ['proformaId' => $voucher['id']]) }}" class="btn btn-primary">Descargar Ticket PDF</a> --}}
                </div>
            </div>
        @else
            <div
                style="
                background-color: rgba(255, 255, 255, 0.9);
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            ">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="color: #2258d5; font-size: 16px; font-weight: bold;">¡Tu Pago esta en proceso de
                        revisión!</h1>
                    <img style="max-width: 10rem; height: auto; display: block; margin: 0 auto;"
                        src="{{ $message->embed($imagePathLogo) }}" alt="Icono INFOTELPerú">
                </div>
                <div style="font-size: 12px; padding: 1rem 2rem; text-align: center;">
                    <strong>{{ $cliente->name }} {{ $cliente->paterno }} {{ $cliente->materno }}</strong>, tu compra
                    se
                    realizó exitosamente por yape , estamos validando el pago.
                </div>
                <div>
                    <h1 style="font-size: 13px; color: #2258d5; margin-bottom: 10px;">Ticket de Pago</h1>
                    <div
                        style="
                        background-color: #2258d5;
                        text-align: center;
                        padding: 10px;
                        color: white;
                        border-radius: 5px;
                    ">
                        <h1 style="margin: 0; font-size: 13px; font-weight: normal;">Código de Pago</h1>
                        <h2 style="margin: 0; font-size: 16px; font-weight: 700;">{{ $voucher['numero'] }}</h2>
                    </div>
                </div>
                <div>
                    <h1 style="font-size: 13px; color: #2258d5; margin-bottom: 10px;">Estado de Pago</h1>
                    <div
                        style="
                        background-color: #2258d5;
                        text-align: center;
                        padding: 10px;
                        color: white;
                        border-radius: 5px;
                    ">
                        <h1 style="margin: 0; font-size: 13px; font-weight: normal;">Estado</h1>
                        <h2 style="margin: 0; font-size: 16px; font-weight: 700;">{{ $voucher['status'] }}</h2>
                    </div>
                </div>
                <div style="text-align: center;">
                    <div style="text-align: justify; padding: 2rem;">
                        <h1
                            style="
                            font-size: 12px;
                            position: relative;
                            color: #527cdb;
                            float: left;
                            bottom: 2.5rem;
                            right: 15px;
                        ">
                            Monto Pagado</h1>
                        <h2
                            style="
                            position: relative;
                            font-size: 12px;
                            color: #1b55db;
                            font-weight: 800;
                            float: right;
                            bottom: 2.5rem;
                            left: 15px;
                        ">
                            S/. {{ $voucher['total'] }}</h2>
                    </div>
                    <div style="background-color: #b5c6ed96; padding: 1rem;">
                        <h1 style="margin: 0; font-size: 12px; color: #3468e2b7;">Tu Pago ha sido procesado el</h1>
                        <h2 style="margin: 0; font-size: 16px; color: #3468e2;">{{ $fechaFormateada }}</h2>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>

</html>
