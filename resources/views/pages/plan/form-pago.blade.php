<div>
    @inject('translator', 'translatorService')
    <div class="py-10 bg-gray-200 ">
        @if ($isOpen)
            @include('admin.modals.form-pago')
        @endif
        <div class="w-11/12 lg:w-2/6 mx-[auto] pb-5">
            <div class="bg-gray-200 h-1 flex items-center justify-between">
                <div class="w-1/3 bg-indigo-700 h-1 flex items-center">
                    <div class="bg-indigo-700 h-6 w-6 rounded-full shadow flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="18"
                            height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                    </div>
                </div>
                <div class="w-1/3 flex justify-between bg-indigo-700 h-1 items-center relative">
                    <div class="bg-indigo-700 h-6 w-6 rounded-full shadow flex items-center justify-center -ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check"
                            width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                    </div>
                    <div class="bg-white h-6 w-6 rounded-full shadow flex items-center justify-center -mr-3 relative">
                        <div class="h-3 w-3 animate-pulse bg-indigo-700 rounded-full"></div>
                    </div>
                </div>
                <div class="w-1/3 flex justify-end">
                    <div class="bg-white h-6 w-6 rounded-full shadow"></div>
                </div>
            </div>
        </div>
        <div class="flex justify-center items-center py-10">
            <div class="xl:container lg:container md:px-10 sm:px-5">
                <div class="grid grid-cols-1 xl:grid-cols-3 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-2 gap-2">
                    {{-- 1ra COLUMNA -> FORMULARIO DE IDENTIFICACIÓN --}}
                    <div class="flex-row space-y-2 ">
                        <div class="bg-white px-6 py-3 rounded-lg">
                            <span class="font-extrabold text-xl mb-2">{{ trans('formclient.Formas de pago') }}</span>

                            <div class="grid grid-cols-2 gap-4 m-5">
                                <button wire:click.prevent="storePaypal()" wire:target="storePaypal"
                                    class="border rounded-md p-2"><img class="rounded-md" src="/img/pay/pay_paypal.png"
                                        alt=""></button>
                                <a href="#" class="border rounded-md p-2"><img class="rounded-md"
                                        src="/img/pay/pay_pagoefectivo.png" alt=""></a>
                                <button wire:click.prevent="storeYape()" wire:target="storeYape"
                                    class="border rounded-md p-2"><img class="rounded-md" src="/img/pay/pay_yape.jpg"
                                        alt=""></button>
                                <a href="#" class="border rounded-md p-2"><img class="rounded-md"
                                        src="/img/pay/pay_plin.png" alt=""></a>
                            </div>
                            <style>
                                /* Estilos para el switch */
                                .switch {
                                    position: relative;
                                    display: inline-block;
                                    width: 50px;
                                    height: 24px;
                                }

                                .switch input {
                                    opacity: 0;
                                    width: 0;
                                    height: 0;
                                }

                                .slider {
                                    position: absolute;
                                    cursor: pointer;
                                    top: 0;
                                    left: 0;
                                    right: 0;
                                    bottom: 0;
                                    background-color: #ccc;
                                    transition: .4s;
                                    border-radius: 34px;
                                }

                                .slider:before {
                                    position: absolute;
                                    content: "";
                                    height: 16px;
                                    width: 16px;
                                    left: 4px;
                                    bottom: 4px;
                                    background-color: white;
                                    transition: .4s;
                                    border-radius: 50%;
                                }

                                input:checked+.slider {
                                    background-color: #4CAF50;
                                }

                                input:focus+.slider {
                                    box-shadow: 0 0 1px #2196F3;
                                }

                                input:checked+.slider:before {
                                    transform: translateX(26px);
                                }

                                /* Estilos para el formulario */
                                #formulario {
                                    margin-top: 20px;
                                }
                            </style>
                            <div>
                                <div class="flex justify-evenly">
                                    <h1 class="text-xl font-bold">{{ trans('formpago.¿Necesitas una factura?') }}</h1>

                                    <div class="switch">
                                        <!-- Agrega el atributo autocomplete="off" -->
                                        <input type="checkbox" id="switch" autocomplete="off"
                                            wire:model.defer="factura.activar_factura">
                                        <label for="switch" class="slider"></label>
                                    </div>
                                </div>

                                <!-- Formulario -->
                                <div id="formulario">
                                    <form autocomplete="off">
                                        {{-- <h2 class="text-center">Rellena el formulario de facturación:</h2> --}}
                                        <div class="flex gap-3 items-center">
                                            <i class="fa-solid fa-circle-exclamation"></i>
                                            <h1>{{ trans('formpago.Factura válida para personas y empresas inscritas en su país:') }}
                                            </h1>
                                        </div>
                                        <div class="pr-1 pl-5 pt-2">
                                            <div class="flex gap-1">
                                                <span>&#8226;</span>
                                                <h1>{{ trans('formpago.Para justificar costos y gastos, ingresar el RUC, de lo contrario el documento tendrá validez de bolenta de venta.') }}
                                                </h1>
                                            </div>
                                            <div class="flex gap-1">
                                                <span>&#8226;</span>
                                                <h1>{{ trans('formpago.Aplica sólo un RUC para la compra.') }}
                                                </h1>
                                            </div>
                                        </div>
                                        <input type="hidden" wire:model="factura.id">
                                        <div class="grid gap-2 py-2">
                                            <div class="py-2">
                                                <div class="input-container">
                                                    <input wire:model="factura.razon_social"
                                                        class="input_contact shadow-md pl-4 pt-5 border-white rounded-lg focus:border-green-600 focus:ring-green-600"
                                                        type="text" id="razon_social" placeholder=" ">
                                                    <label class="label_contact" for="razon_social">Razón
                                                        social*</label>
                                                </div>
                                                @unless (!empty($factura['razon_social']))
                                                    <x-input-error for="factura.razon_social" />
                                                @endunless
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                <div class="py-2">
                                                    <div class="input-container">
                                                        <input wire:model="factura.ruc"
                                                            class="input_contact shadow-md pl-4 pt-5 border-white rounded-lg focus:border-green-600 focus:ring-green-600"
                                                            type="tel" id="ruc" placeholder=" "
                                                            pattern="[0-9]*"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                            maxlength="11">
                                                        <label class="label_contact" for="ruc">RUC*</label>
                                                    </div>
                                                    @unless (!empty($factura['ruc']) && strlen($factura['ruc']) === 11)
                                                        <x-input-error for="factura.ruc" />
                                                    @endunless
                                                </div>
                                                <div class="py-2">
                                                    <div class="input-container">
                                                        <input wire:model="factura.pais"
                                                            class="input_contact shadow-md pl-4 pt-5 border-white rounded-lg focus:border-green-600 focus:ring-green-600"
                                                            type="text" id="pais" placeholder=" ">
                                                        <label class="label_contact" for="pais">País*</label>
                                                    </div>
                                                    @unless (!empty($factura['pais']))
                                                        <x-input-error for="factura.pais" />
                                                    @endunless
                                                </div>
                                            </div>
                                            <div class="py-2">
                                                <div class="input-container">
                                                    <input wire:model="factura.ciudad"
                                                        class="input_contact shadow-md pl-4 pt-5 border-white rounded-lg focus:border-green-600 focus:ring-green-600"
                                                        type="text" id="ciudad" placeholder=" ">
                                                    <label class="label_contact" for="ciudad">Ciudad*</label>
                                                </div>
                                                @unless (!empty($factura['ciudad']))
                                                    <x-input-error for="factura.ciudad" />
                                                @endunless
                                            </div>

                                            <div class="py-2">
                                                <div class="input-container">
                                                    <input wire:model="factura.email"
                                                        class="input_contact shadow-md pl-4 pt-5 border-white rounded-lg focus:border-green-600 focus:ring-green-600"
                                                        type="text" id="email" placeholder=" ">
                                                    <label class="label_contact" for="email">Email*</label>
                                                </div>
                                                @unless (!empty($factura['email']))
                                                    <x-input-error for="factura.email" />
                                                @endunless
                                            </div>

                                            <style>
                                                .input-container {
                                                    position: relative;
                                                }

                                                .label_contact {
                                                    position: absolute;
                                                    left: 1rem;
                                                    top: 50%;
                                                    transform: translateY(-50%);
                                                    pointer-events: none;
                                                    transition: 0.3s;
                                                    color: #777;
                                                }

                                                .input_contact:focus+.label_contact,
                                                .input_contact:not(:placeholder-shown)+.label_contact {
                                                    top: 0.875rem;
                                                    font-size: 0.875rem;
                                                    color: #16a34a;
                                                }

                                                .input_contact {
                                                    width: 100%;
                                                    font-size: 0.875rem;
                                                    outline: none;
                                                    box-sizing: border-box;
                                                    margin: auto;
                                                    display: block;
                                                }

                                                .input_contact:hover {
                                                    border-color: #16a34a;
                                                }

                                                .input_contact:focus {
                                                    border-color: #16a34a;
                                                }
                                            </style>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <script>
                                var formulario = document.getElementById("formulario");
                                var switchInput = document.getElementById("switch");

                                // Ocultar el formulario al cargar la página
                                formulario.style.display = "none";

                                switchInput.addEventListener("change", function() {
                                    if (this.checked) { // Verifica si el checkbox está marcado
                                        formulario.style.display = "block"; // Muestra el formulario
                                        validarFormulario(); // Llama a la función para validar el formulario
                                    } else {
                                        formulario.style.display = "none"; // Oculta el formulario solo si se desactiva el switch
                                    }
                                });

                                function validarFormulario() {
                                    // Livewire.emit('validateFactura');
                                    Livewire.emitTo('sis-crud-pago', 'validateFactura');
                                }
                            </script>
                            <button class="text-red-500 text-xs" type="submit">
                                {{ trans('formclient.Cancela tu pedido y regresa a la tienda') }}
                            </button>
                        </div>
                    </div>

                    <div class="xl:col-span-2 lg:col-span-2 md:col-span-1 sm:col-span-1">
                        <div
                            class="grid grid-cols-1 xl:grid-cols-2 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-2">
                            {{-- 2da COLUMNA -> PRÓXIMOS PASOS --}}
                            <div class="">
                                <div class="bg-white px-6 py-3 rounded-lg">
                                    <span
                                        class="font-extrabold text-xl">{{ trans('formclient.Próximos pasos') }}</span>
                                    <div class="pro_pa">
                                        <div class="pro_pa_main mt-[1.25rem]">
                                            <div class="w-full border rounded-md px-5 py-4 relative mb-2">
                                                @if (isset($clientData))
                                                    <button wire:click="editClient()"
                                                        class="cursor-pointer px-2 space-x-2 rounded-full"
                                                        style="position: absolute; right: 0; transform: translateY(-50%);">
                                                        <i class="fa-solid fa-pen-to-square text-orange-600"></i>
                                                    </button>
                                                    <div class="flex items-center space-x-4 ">
                                                        <div
                                                            class="cursor-pointer space-x-2 px-2 py-2 bg-gray-200 rounded-full">
                                                            <i class="fa-solid fa-user text-orange-600 fa-xl"></i>
                                                        </div>
                                                        <div class="cursor-pointer space-x-2">
                                                            {{ trans('formclient.Datos del Comprador') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-between text-gray-600 pt-4">
                                                        <span>
                                                            @if (isset($clientData))
                                                                <div class="flex gap-2">
                                                                    <p class="text-black font-bold">
                                                                        {{ trans('formclient.Nombre:') }} </p>
                                                                    <p class="col-span-2">{{ $clientData['name'] }}
                                                                        {{ $clientData['paterno'] }}
                                                                        {{ $clientData['materno'] }}</p>
                                                                </div>
                                                                <div class="flex gap-2">
                                                                    <p class="text-black font-bold">
                                                                        {{ trans('formclient.Email:') }}</p>
                                                                    <p class="col-span-2">{{ $clientData['email'] }}
                                                                    </p>
                                                                </div>
                                                                <div class="flex gap-2">
                                                                    <p class="text-black font-bold">
                                                                        {{ trans('formclient.Teléfono:') }}</p>
                                                                    <p class="col-span-2">{{ $clientData['phone'] }}
                                                                    </p>
                                                                </div>
                                                            @else
                                                                <p>{{ trans('formclient.No se encontraron detalles del cliente.') }}
                                                                </p>
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="w-full border rounded-md px-5 py-4 relative mb-2">
                                                @if (isset($entregaData))
                                                    <button wire:click="editEntrega()"
                                                        class="cursor-pointer px-2 space-x-2 rounded-full"
                                                        style="position: absolute; right: 0; transform: translateY(-50%);">
                                                        <i class="fa-solid fa-pen-to-square text-orange-600"></i>
                                                    </button>
                                                    <div class="flex items-center space-x-4 ">
                                                        <div
                                                            class="cursor-pointer space-x-2 px-2 py-2 bg-gray-200 rounded-full">
                                                            <i
                                                                class="fa-solid fa-truck-ramp-box text-orange-600 fa-lg"></i>
                                                        </div>
                                                        <div class="cursor-pointer space-x-2">
                                                            {{ trans('formclient.Datos de la Entrega') }}</p>
                                                        </div>
                                                    </div>

                                                    <div class="flex justify-between text-gray-600 pt-4">
                                                        <span>
                                                            @if (isset($entregaData))
                                                                <div class="flex gap-2">
                                                                    <p class="text-black font-bold">
                                                                        {{ trans('formclient.Departamento:') }} </p>
                                                                    <p class="col-span-2">{{ $nombreDepartamento }}
                                                                    </p>
                                                                </div>
                                                                <div class="flex gap-2">
                                                                    <p class="text-black font-bold">
                                                                        {{ trans('formclient.Provincia:') }}</p>
                                                                    <p class="col-span-2">{{ $nombreProvincia }}</p>
                                                                </div>
                                                                <div class="flex gap-2">
                                                                    <p class="text-black font-bold">
                                                                        {{ trans('formclient.Distrito:') }}</p>
                                                                    <p class="col-span-2">{{ $nombreDistrito }}</p>
                                                                </div>
                                                            @else
                                                                <p>{{ trans('formclient.No se encontraron detalles de la entrega.') }}
                                                                </p>
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div
                                                class="w-full border rounded-md px-5 py-4 bg-gradient-to-r from-indigo-800 to-[#00B3FF]">
                                                <div class="flex items-center space-x-4">
                                                    <div
                                                        class="cursor-pointer space-x-2 px-2 py-2 bg-gray-300 rounded-full">
                                                        <i class="fa-regular fa-credit-card text-white fa-lg"></i>
                                                    </div>
                                                    <div class="cursor-pointer space-x-2 text-gray-200 text-[1rem]">
                                                        {{ trans('formclient.Pago') }}
                                                    </div>
                                                </div>
                                                <div class="flex text-gray-300 pt-2 justify-center">
                                                    <svg class="animate-spin h-7 w-7 " fill="#E5E7EB"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                        <path
                                                            d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z" />
                                                    </svg>
                                                    <samp
                                                        class="text-lg px-2 text-white animate-pulse font-bold">{{ trans('formclient.En proceso...') }}</samp>
                                                </div>
                                            </div>
                                            <style>
                                                .ppm_content_03 {
                                                    width: 100%;
                                                }

                                                @media (min-width: 768px) {
                                                    .ppm_content_03 {
                                                        width: 70%;
                                                    }
                                                }

                                                @media (min-width: 1366px) {
                                                    .ppm_content_03 {
                                                        width: 100%;
                                                    }
                                                }
                                            </style>
                                        </div>
                                        <div class="border w-full mt-4"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- 3ra COLUMNA -> RESUMEN DE COMPRA --}}
                            <div class="bg-white px-6 py-3 rounded-lg h-max">
                                <span
                                    class="font-extrabold text-xl">{{ trans('formclient.Resumen de tu compra') }}</span>
                                <div class="overflow-y-auto max-h-56 sm:max-h-56 md:max-h-56 lg:max-h-none divide-y">
                                    <?php $total = 0; ?>
                                    @if (session('cart'))
                                        @foreach (session('cart') as $id => $details)
                                            <?php $total += $details['price'] * $details['cantidad']; ?>
                                            <div class="flex items-center gap-2 py-2">

                                                <div>
                                                    <img class="object-cover rounded-lg w-[6rem] border"
                                                        src="@if (is_array($details)) {{ Storage::disk('devaperu')->url($details['images'][0]['url']) }}@else /img/default.jpg @endif">
                                                </div>
                                                <div
                                                    class="flex flex-wrap sm:flex-nowrap xl:flex-nowrap lg:flex-nowrap md:flex-nowrap flex-1 justify-between items-center">
                                                    <div class="col-span-1 w-full">
                                                        <div class="flex-row space-y-2 pr-2 text-sm">
                                                            <span
                                                                class="bg-yellow-400 rounded-tl-xl rounded-br-xl px-3 shadow">
                                                                {{ $translator->translate($details['sub_category']) }}
                                                            </span>
                                                            {{-- <div> {{ trans('carritoventa.Cantidad:') }}
                                                                        {{ $details['cantidad'] }}
                                                                        {{ trans('carritoventa.productos') }}</div> --}}
                                                            <div>
                                                                @if ($details['color_defecto'] == 'default' && $details['talla_defecto'] == 'default')
                                                                @else
                                                                    <div
                                                                        class="flex justify-between bg-zinc-100 rounded-tl-xl rounded-br-xl shadow divide-x">
                                                                        <div class="px-3">
                                                                            {{ trans('carritoventa.Color:') }}:
                                                                            {{ $translator->translate($details['color_defecto']) }}
                                                                        </div>
                                                                        @if ($details['talla_defecto'] == 'default')
                                                                        @else
                                                                            <div class="px-3">
                                                                                {{ trans('carritoventa.Talla:') }}:
                                                                                {{ $details['talla_defecto'] }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="text-md font-semi-bold">
                                                                {{ $translator->translate($details['name']) }}
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <div class="text-indigo-700 font-bold">
                                                                    {{ trans('formclient.Precio:') }}
                                                                    @php
                                                                        $selectedCurrency = Session::get(
                                                                            'selected_currency',
                                                                            'PEN',
                                                                        );
                                                                    @endphp
                                                                    @if ($selectedCurrency === 'PEN')
                                                                        S/ {{ number_format($details['price'], 2) }}
                                                                    @else
                                                                        @php
                                                                            $precioInUSD = $details['price'] / $tasa;
                                                                        @endphp
                                                                        $ {{ number_format($precioInUSD, 2) }}
                                                                    @endif
                                                                </div>
                                                                <div> {{ trans('carritoventa.Cantidad:') }}
                                                                    {{ $details['cantidad'] }} </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mt-4 flex justify-between gap-16">
                                    <span>{{ trans('formclient.Total a pagar') }}</span>
                                    <span class="text-lg font-bold">
                                        @php
                                            $selectedCurrency = Session::get('selected_currency', 'PEN');
                                        @endphp
                                        @if ($selectedCurrency === 'PEN')
                                            S/ {{ number_format($total, 2) }}
                                        @else
                                            @php
                                                $precioInUSD = $total / $tasa;
                                            @endphp
                                            $ {{ number_format($precioInUSD, 2) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Scripts - Sweetalert   -->
        @push('js')
            <script>
                Livewire.on('deleteItem', id => {
                    Swal.fire({
                        title: '¿Estas seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '¡Sí, bórralo!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            //alert("del");
                            Livewire.emitTo('sis-crud-pago', 'delete', id);
                            Swal.fire(
                                '¡Eliminado!',
                                'El registro ha sido eliminado.',
                                'success'
                            )

                        }
                    })
                });
            </script>
        @endpush
        {{-- Script para el icon del select (selected-option -> onclick="toggleOptions) --}}
        <script>
            // Función para mostrar/ocultar las opciones y cambiar el ícono
            function togglesOptions() {
                var optionsList = document.getElementById('tdocumentoInput');
                var icon = document.getElementById('dropdownIcon');

                if (optionsList.style.display === 'none' || optionsList.style.display === '') {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }

            // Cierra las opciones si el usuario hace clic fuera de ellas
            window.onclick = function(event) {
                var icon = document.getElementById('dropdownIcon');

                if (icon && !event.target.closest('#customDropdown')) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }
        </script>
    </div>
    @include('partials.footer')
</div>
