<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PagePlanes extends Component
{
    public $pagoIniciado;
    public $flashMessage;
    public $modalMessage;
    public $selectedCurrency = 'PEN';
    public $showModal = false;

    protected $listeners = ['redirectToCliente', 'mostrarModal'];

    public function mount()
    {
        // Restablecer el estado de pagoIniciado a su valor predeterminado
        $this->pagoIniciado = false;

        // Restablecer el valor en la sesión
        session(['pagoIniciado' => false]);
    }

    public function mostrarModal($message)
    {
        $this->modalMessage = $message;
        // $this->dispatchBrowserEvent('mostrarModal');
    }

    public function iniciarPago($nombrePlan, $precioPlan)
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            $authenticatedUser = Auth::user();

            // Verificación: ¿El usuario tiene su correo electrónico verificado?
            if ($authenticatedUser->email != null) {
                // Lógica para iniciar el proceso de pago
                $this->pagoIniciado = true;

                // Establecer las variables en la sesión
                session([
                    'nombrePlan' => $nombrePlan,
                    'precioPlan' => $precioPlan,
                ]);

                return redirect('cliente/' . $precioPlan);
            } else {
                $message = trans('modales.¡Para continuar, necesitas verificar su dirección de correo electrónico!');
                $this->dispatch('mostrarModalGmail', $message);
            }
        } else {
            $this->openModal();
            // $message = trans('modales.¡Para continuar, necesitas Iniciar Sesión!');
            // $this->dispatch('mostrarModal', $message);
        }
    }


    public function redirectToCliente($total)
    {
        return redirect()->route('cliente', ['total' => $total]);
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function render()
    {
        session()->forget('voucher');
        session()->forget('entrega_data');
        session()->forget('client_data');
        session()->forget('client');

        $user = Auth::user();

        return view('pages.plan.planes', compact('user'));
    }
}
