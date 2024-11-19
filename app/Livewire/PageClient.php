<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Validator;
use Usernotnull\Toast\Concerns\WireToast;

class PageClient extends Component
{
    use WithFileUploads;
    use WithPagination;
    use WireToast;

    public $dropdownOpen;
    public $id;
    public $pagoIniciado;
    public $selectedCurrency = 'PEN';
    public $ruteCreate = true;
    public $persona;
    public $client = ['tdatos' => false];
    protected $listeners = ['render', 'optionSelected' => 'selectOption'];
    protected $rules = [
        'client.email' => 'required|email',
        'client.name' => 'required',
        'client.paterno' => 'required',
        'client.materno' => 'required',
        'client.document' => 'required',
        'client.tdatos' => 'accepted',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function cancel()
    {
        $this->reset('client');
        return redirect()->to('/carrito-venta');
    }

    public function selectOption($value)
    {
        $this->client['tdocumento'] = $value;
        $this->validate();
    }

    public function render()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Utilizar los datos del usuario para prellenar el formulario
        $this->client['email'] = $user->email;
        if (empty($this->client['name'])) {
            // $this->client['email'] = $user->email;
            $this->client['name'] = $user->names;
            $this->client['paterno'] = $user->apellido_paterno;
            $this->client['materno'] = $user->apellido_materno;
        }

        $readOnly = ($user->names && $user->apellido_paterno && $user->apellido_materno);
        $total = null;

        return view('pages.plan.client', compact('readOnly', 'total'));
    }

    public function buscarPersona()
    {
        if (!empty($this->client['document']) && strlen($this->client['document']) === 8) {
            // Datos
            $token = 'apis-token-7646.3r4-XouHFdDhoNuccXrsnQrfZFHthQu5';

            $dni = '' . $this->client['document'];

            // Iniciar llamada a API
            $curl = curl_init();

            // Buscar dni
            curl_setopt_array($curl, array(
                // para user api versión 2
                CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 2,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Referer: https://apis.net.pe/consulta-dni-api',
                    'Authorization: Bearer ' . $token
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $this->persona = json_decode($response, true);

            if (
                (isset($this->persona['message']) && ($this->persona['message'] === 'dni no valido' || $this->persona['message'] === 'not found')) ||
                (isset($this->persona['nombres']) && $this->persona['nombres'] === '') ||
                (isset($this->persona['apellidoPaterno']) && $this->persona['apellidoPaterno'] === '') ||
                (isset($this->persona['apellidoMaterno']) && $this->persona['apellidoMaterno'] === '')
            ) {
                toast()->danger('El DNI no es válido, ingrese nuevamente', 'Mensaje de error')->push();

                $this->client['name'] = '';
                $this->client['paterno'] = '';
                $this->client['materno'] = '';

                throw new \Illuminate\Validation\ValidationException(Validator::make([], []));
            }

            toast()->success('DNI encontrado y validado correctamente', 'Mensaje de éxito')->push();

            $this->client['name'] = $this->persona['nombres'];
            $this->client['paterno'] = $this->persona['apellidoPaterno'];
            $this->client['materno'] = $this->persona['apellidoMaterno'];
        }
    }

    public function create()
    {
        $this->ruteCreate = false;
        $this->reset('client');
        $this->resetValidation();
    }

    public function storePaypal()
    {
        $this->validate();

        $this->id = uniqid();

        // Guardar los datos en sesión
        session(['client_data' => $this->client]);

        toast()->success('Registro creado satisfactoriamente', 'Mensaje de éxito')->push();
        $this->dispatch('SisCrudclient', 'render');

        $this->reset(['client']);

        // $this->reset(['factura']);
        return redirect()->to('/paypal/pay');
    }
}
