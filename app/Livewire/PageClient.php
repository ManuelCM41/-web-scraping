<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
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
    public $client = ['tdatos' => false];
    protected $listeners = ['render', 'optionSelected' => 'selectOption'];
    protected $rules = [
        'client.email' => 'required|email',
        'client.name' => 'required',
        'client.paterno' => 'required',
        'client.materno' => 'required',
        'client.document' => 'required|digits:8',
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
