<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\DetailVoucher;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Voucher;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PageVoucher extends Component
{
    public function createPDF($id)
    {
        $cliente = Client::find($id);
        //dd($cliente['id']);
        $proforma = Voucher::find($id);

        $fechaFormateada = date('Y-m-d');
        //$fechaFormateada = Carbon::parse($proforma['updated_at'])->format('Y-m-d h:i A');

        $pdf = FacadePdf::loadView('reports.bve_pdf', compact('cliente', 'proforma', 'fechaFormateada'));
        $pdf->setPaper('a4', 'landscape');
        //return $pdf->download('pdf_file.pdf');    //desacarga automaticamente
        return $pdf->stream('reports.bve_pdf'); //abre en una pestaña como pdf
    }

    public function proforma($id)
    {
        $cliente = Client::find($id);
        $proforma = Voucher::find($id);
        $pdf = FacadePdf::loadView('reports.proforma', compact('cliente', 'proforma'));
        return $pdf->stream('reports/proforma', compact('cliente', 'proforma'));
    }

    public function emailvoucher($id)
    {
        $proforma = Voucher::find($id);

        if (!$proforma) {
            abort(404, 'Proforma no encontrada');
        }
        // Llama a la vista de correo electrónico
        return view('emails.contactMail', compact('proforma'));
    }

    public function validarProforma(Request $request)
    {
        $idDeseado = $request->input('id');

        $cliente = Client::find($idDeseado);

        $proforma = Voucher::find($idDeseado);

        if (!$cliente || !$proforma) {
            abort(404, 'Proforma no encontrada');
        }

        $fechaFormateada = Carbon::parse($proforma['updated_at'])->format('Y/m/d ');
        $imagePathLogo = public_path('img/infotel.png');

        $totalEnLetras = '';
        if (isset($proforma->total)) {
            try {
                $totalEnLetras = $this->numeroEnLetras($proforma->total);
            } catch (\Exception $e) {
                $totalEnLetras = '';
            }
        }

        return view('reports.validarvoucher', compact('cliente', 'proforma', 'fechaFormateada', 'totalEnLetras'));
    }

    public function numeroEnLetras($monto)
    {
        // Convertir el monto a palabras
        $montoEnLetras = $this->convertirMontoEnLetras($monto);

        // Extraer la parte entera y decimal del monto
        $parteEntera = floor($monto);
        $parteDecimal = number_format(($monto - $parteEntera) * 100, 0, '', ''); // Ajuste en la parte decimal

        // Concatenar el monto en letras con la parte decimal
        $montoTotalEnLetras = ucfirst($montoEnLetras) . ' con ' . str_pad($parteDecimal, 2, '0', STR_PAD_LEFT) . '/100 soles';

        return $montoTotalEnLetras;
    }

    public function convertirMontoEnLetras($monto)
    {
        // Array con nombres de números en español
        $numeros = [
            0 => 'cero',
            1 => 'uno',
            2 => 'dos',
            3 => 'tres',
            4 => 'cuatro',
            5 => 'cinco',
            6 => 'seis',
            7 => 'siete',
            8 => 'ocho',
            9 => 'nueve',
            10 => 'diez',
            11 => 'once',
            12 => 'doce',
            13 => 'trece',
            14 => 'catorce',
            15 => 'quince',
            16 => 'dieciséis',
            17 => 'diecisiete',
            18 => 'dieciocho',
            19 => 'diecinueve',
            20 => 'veinte',
            21 => 'veintiuno',
            22 => 'veintidós',
            23 => 'veintitrés',
            24 => 'veinticuatro',
            25 => 'veinticinco',
            26 => 'veintiséis',
            27 => 'veintisiete',
            28 => 'veintiocho',
            29 => 'veintinueve',
            30 => 'treinta',
            40 => 'cuarenta',
            50 => 'cincuenta',
            60 => 'sesenta',
            70 => 'setenta',
            80 => 'ochenta',
            90 => 'noventa',
        ];

        $divisores = ['', 'mil', 'millón', 'mil', 'billón', 'mil', 'trillón']; // Escala numérica

        // Manejar el caso especial de cero
        if ($monto == 0) {
            return 'cero';
        }

        $montoEnLetras = '';

        // Verificar si el monto es negativo
        if ($monto < 0) {
            $montoEnLetras = 'menos ';
            $monto = abs($monto);
        }

        // Verificar si el monto está en el array de números definidos
        if (isset($numeros[$monto])) {
            return $montoEnLetras . $numeros[$monto];
        }

        // Convertir la parte entera a palabras
        $parteEnteraEnLetras = $this->convertirParteEntera($monto, $numeros, $divisores);

        // Concatenar la parte entera al resultado final
        $montoEnLetras .= $parteEnteraEnLetras;

        return trim($montoEnLetras);
    }

    private function convertirParteEntera($monto, $numeros, $divisores)
    {
        $parteEnLetras = '';

        // Iterar sobre cada división de la parte entera del monto
        for ($i = 0; $i < count($divisores) && $monto > 0; $i++) {
            $unidad = $monto % 1000; // Obtener la parte de tres dígitos actual
            $monto = floor($monto / 1000); // Actualizar la parte entera

            // Convertir la parte de tres dígitos a palabras
            $parteActualEnLetras = '';
            if ($unidad > 0) {
                if ($unidad >= 100) {
                    if ($unidad == 100) {
                        $parteActualEnLetras .= 'cien';
                    } elseif ($unidad > 100 && $unidad < 200) {
                        $parteActualEnLetras .= 'ciento';
                    } elseif ($unidad >= 200 && $unidad < 300) {
                        $parteActualEnLetras .= 'doscientos';
                    } elseif ($unidad >= 300 && $unidad < 400) {
                        $parteActualEnLetras .= 'trescientos';
                    } elseif ($unidad >= 400 && $unidad < 500) {
                        $parteActualEnLetras .= 'cuatrocientos';
                    } elseif ($unidad >= 500 && $unidad < 600) {
                        $parteActualEnLetras .= 'quinientos';
                    } elseif ($unidad >= 600 && $unidad < 700) {
                        $parteActualEnLetras .= 'seiscientos';
                    } elseif ($unidad >= 700 && $unidad < 800) {
                        $parteActualEnLetras .= 'setecientos';
                    } elseif ($unidad >= 800 && $unidad < 900) {
                        $parteActualEnLetras .= 'ochocientos';
                    } elseif ($unidad >= 900 && $unidad < 1000) {
                        $parteActualEnLetras .= 'novecientos';
                    }
                    $unidad %= 100;
                }
                if ($unidad >= 20) {
                    $decenas = floor($unidad / 10) * 10;
                    $parteActualEnLetras .= ($parteActualEnLetras != '' ? ' ' : '') . $numeros[$decenas];
                    $unidad %= 10;
                }
                if ($unidad >= 16 && $unidad <= 19) {
                    $parteActualEnLetras .= ($parteActualEnLetras != '' ? ' y ' : '') . $numeros[$unidad];
                    $unidad = 0; // Establecer a cero para evitar duplicación
                }
                if ($unidad > 0 && $unidad < 16) {
                    $parteActualEnLetras .= ($parteActualEnLetras != '' ? ' y ' : '') . $numeros[$unidad];
                    $unidad = 0; // Establecer a cero para evitar duplicación
                }
                $parteActualEnLetras .= ' ' . $divisores[$i]; // Agregar la escala numérica
            }

            // Concatenar la parte en letras al resultado final
            if ($parteActualEnLetras != '') {
                if ($parteEnLetras != '') {
                    $parteEnLetras = $parteActualEnLetras . ' ' . $parteEnLetras;
                } else {
                    $parteEnLetras = $parteActualEnLetras;
                }
            }
        }

        return $parteEnLetras;
    }
}
