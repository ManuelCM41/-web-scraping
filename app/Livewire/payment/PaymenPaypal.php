<?php

namespace App\Livewire\Payment;

use App\Models\Client;
use App\Models\DetailVoucher;
use App\Models\Entrega;
use App\Models\Factura;
use App\Models\Membership;
use App\Models\Moneda;
use App\Models\Voucher;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use Illuminate\Support\Facades\Log;

class PaymenPaypal extends Component
{
    private $apiContext;
    public $carrito;
    public $id;

    public function __construct()
    {
        $payPalConfig = Config::get('paypal');

        // $this->apiContext = new ApiContext(new OAuthTokenCredential($payPalConfig['client_id'], $payPalConfig['secret']));
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                'AQoTa_YLikH7MfbnfGHoEefa2jhN_UTU3NUkvCLLC32L-XSlR8laoew8sMdNoA-VCERwaydPgoQt-fqq',  // Reemplaza con tu Client ID de PayPal
                'EM4_w51U8h-zDT6lAvNXObmMwCouWDoPHi56NJIwQNxo0Os82t59BYZK5_9IuPS4aBknk3Tw-b6mpP99'      // Reemplaza con tu Secret de PayPal
            )
        );

        // $this->apiContext->setConfig($payPalConfig['settings']);
        $this->apiContext->setConfig([
            'mode' => 'sandbox',  // O 'live' si estás en producción
            'http.CURLOPT_SSL_VERIFYHOST' => 0, // Desactiva la verificación del host
            'http.CURLOPT_SSL_VERIFYPEER' => 0  // Desactiva la verificación del peer
        ]);
    }

    private function prepareAmountFromCart($cartDetails)
    {
        $total = 0;
        $monedaLocal = 'PEN'; // Moneda local (por ejemplo, PEN)
        // Obten la tasa de cambio desde la base de datos
        $exchangeRate = $this->obtener_tipo_de_cambio($monedaLocal, 'USD');

        $total = $cartDetails;

        $totalInUSD = $total / $exchangeRate;
        $amount = new Amount();
        $amount->setTotal($totalInUSD);
        $amount->setCurrency('USD');

        return $amount;
    }

    public function pagarPaypal()
    {
        $clientData = session('client_data');
        $client = session('client');
        //dd($client);
        //dd($clientData['email'] != $client->email);
        if ($clientData != $client) {
            // Guardar en la Base de datos -> START
            $clientData = session('client_data');
            $cliente = Client::create([
                'email' => $clientData['email'],
                'name' => $clientData['name'],
                'paterno' => $clientData['paterno'],
                'materno' => $clientData['materno'],
                'document' => $clientData['document'],
                'tdatos' => $clientData['tdatos'] == 'on' ? '1' : '0',
            ]);

            // Almacenar el cliente en la sesión
            session(['client' => $cliente]);

            // Obtener la información del cliente desde la sesión
            $client = session('client');
            $total = 0;

            $cartDetails = session('precioPlan');

            $total = $cartDetails;

            if ($total == 0) {
                return redirect()->route('carrito-venta')->with('mensaje', 'El monto total de la proforma es 0.00, no se puede completar el pago.');
            }

            $ultimoCnumero = Voucher::max('numero');

            if (!$ultimoCnumero) {
                $numero = 'SCP009000'; // Agregamos 'IFP' al principio si no hay número anterior
            } else {
                $numero_parte_numerica = intval(substr($ultimoCnumero, 3));
                $numero_parte_numerica++;
                $numero_parte_numerica = str_pad($numero_parte_numerica, 6, '0', STR_PAD_LEFT); // Cambiado 8 por 6
                $numero = 'SCP' . $numero_parte_numerica;
            }

            $proforma = new Voucher();
            $proforma->client_id = $client->id;
            $proforma->tipo = 'TICKET DE COMPRA';
            $proforma->numero = $numero;
            $proforma->fecha = date('Y-m-d');
            $proforma->total = $total;
            $proforma->status = 'PE';
            $proforma->leido = '1';
            $proforma->metodo_pago = 'Paypal';

            $proforma->save();

            session(['voucher' => $proforma]);
            // Obtener la información del cliente desde la sesión
            $voucherId = session('voucher');

            $this->generarCodigoQR($voucherId->id);
        }

        $cartDetails = session('precioPlan');
        Log::info('Detalles del carrito de compras para el pago', ['cartDetails' => $cartDetails]);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $transaction = new Transaction();
        $transaction->setAmount($this->prepareAmountFromCart($cartDetails));

        $callbackUrl = url('/paypal/status');
        Log::info('URL de callback para PayPal', ['callbackUrl' => $callbackUrl]);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($callbackUrl)->setCancelUrl($callbackUrl);

        $payment = new Payment();
        $payment
            ->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            Log::info('Intentando crear el pago de PayPal');
            $payment->create($this->apiContext);

            Log::info('Redirigiendo a PayPal con el link de aprobación', ['approval_link' => $payment->getApprovalLink()]);
            return redirect()->away($payment->getApprovalLink());
        } catch (PayPalConnectionException $ex) {
            Log::error('Error al crear el pago de PayPal', [
                'message' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'data' => $ex->getData(), // Aquí se capturan detalles de la respuesta de PayPal
                'trace' => $ex->getTraceAsString() // Traza de la excepción
            ]);
            // Para una mejor depuración
            Log::error('Error completo:', ['exception' => $ex]);

            echo $ex->getMessage(); // Mostrar el mensaje de la excepción
        }
    }

    public function generarCodigoQR($id)
    {
        // Contenido del código QR, puedes ajustar según tus necesidades
        $contenido = route('voucher.visualizar', ['id' => $id]);

        // Configuración del código QR
        $qrCode = Builder::create()
            ->data($contenido)
            ->encoding(new Encoding('UTF-8'))
            ->size(400) // Tamaño del código QR
            ->margin(0) // Margen del código QR
            ->build();

        // Obtener la cadena base64 del código QR
        $base64 = $qrCode->getDataUri();

        // Decodificar la cadena base64
        $imagen = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));

        // Especificar la ruta donde se guardará el archivo
        $rutaImagen = public_path('images/qrcode_' . $id . '.png');

        // Guardar la imagen decodificada en un archivo PNG
        file_put_contents($rutaImagen, $imagen);

        // Puedes retornar la ruta de la imagen o cualquier otra respuesta que necesites
        return response()->json(['ruta_imagen' => $rutaImagen]);
    }

    public function PaypalStatus(Request $request)
    {
        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');
        $token = $request->input('token');

        if (!$paymentId || !$payerId || !$token) {
            $this->borrar_pedido();
            $this->id = uniqid();
            $cartDetails = session('precioPlan');
            $statusMessage = 'Lo sentimos! El pago a través de PayPal no se pudo realizar.';
            $statusType = 'error'; // Podrías usar 'success' y 'error' para distinguir los casos
            return redirect('cliente/' . $cartDetails)->with(compact('statusMessage', 'statusType'));
        }

        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        /** Execute the payment **/
        $result = $payment->execute($execution, $this->apiContext);

        if ($result->getState() === 'approved') {

            $user = Auth::user(); // Obtener el objeto User
            $nombrePlan = session('nombrePlan');
            $plan = Membership::where('plan', $nombrePlan)->first();

            if ($plan) {
                $user->membership_id = $plan->id; // Asignar el valor a la propiedad del modelo User
                $user->save(); // Guardar los cambios
            }


            $cartDetails = session('cart');

            // Obtener la proforma asociada al pago desde la sesión
            $proforma = session('voucher');

            $statusMessage = 'Gracias! El pago a través de PayPal se ha realizado correctamente.';
            $statusType = 'success';

            // Redirigir o realizar otras acciones según tus necesidades
            return redirect('/results')->with(compact('statusMessage', 'statusType', 'proforma'));
        }

        $statusMessage = 'Lo sentimos! El pago a través de PayPal no se pudo realizar.';
        $statusType = 'error'; // Puedes usar 'success' y 'error' para distinguir los casos

        return redirect('/planes')->with(compact('statusMessage', 'statusType'));
    }

    public function borrar_pedido()
    {
        $voucherId = session('voucher');

        // Buscar la proforma y los detalles asociados
        $proforma = Voucher::with('cliente')->find($voucherId->id);

        if ($proforma) {
            // Eliminar la proforma
            $proforma->delete();

            // Eliminar el cliente si existe
            if ($proforma->cliente) {
                $proforma->cliente->delete();
            }
        } else {
            // Redirigir con un mensaje de error si la proforma no se encuentra
            return redirect()->route('planes')->with('mensaje', 'Error al cancelar el pedido. La proforma no se encontró.');
        }
    }

    //monedas
    private function obtener_tipo_de_cambio($monedaOrigen, $monedaDestino)
    {
        // Consulta la tasa de cambio desde la base de datos
        $tasadeCambio = Moneda::where('moneda_origen', $monedaOrigen)->where('moneda_destino', $monedaDestino)->first();

        return $tasadeCambio->tasa ?? 1; // Si no se encuentra, asume una tasa de 1:1
    }
}
