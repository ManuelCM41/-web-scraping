<div>
    @inject('translator', 'translatorService')
    <div class="py-10 bg-gray-200">
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
                <div class="w-1/3 flex justify-between h-1 items-center relative">

                    <div class="bg-white h-6 w-6 rounded-full shadow flex items-center justify-center -mr-3 relative">
                        <div class="h-3 w-3 animate-pulse bg-indigo-700 rounded-full"></div>
                    </div>
                    <div class="w-1/3 flex justify-end">
                        <div class="bg-white h-6 w-6 rounded-full shadow"></div>
                    </div>
                </div>
                <div class="w-1/3 flex justify-end">
                    <div class="bg-white h-6 w-6 rounded-full shadow"></div>
                </div>
            </div>
        </div>
        <div class="flex justify-center items-center py-10">
            <div class=" xl:container lg:container md:px-10 sm:px-5">
                <div class="grid grid-cols-1 xl:grid-cols-3 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-2 gap-2">
                    {{-- 1ra COLUMNA -> FORMULARIO DE IDENTIFICACIÓN --}}
                    <div class="flex-row space-y-2 ">
                        <div class="bg-white px-6 py-3 rounded-lg">
                            <span class="font-extrabold text-xl">{{ trans('formclient.Datos de la Entrega') }}</span>
                            <div class="mt-2">
                                {{ trans('formclient.Solicitamos únicamente la información esencial para la finalización de la compra.') }}
                            </div>
                            <div class="flex justify-center items-center">
                                <form autocomplete="off" class="padre w-full max-w-lg px-4">
                                    <input type="hidden" wire:model="entrega.id">

                                    <div class="flex flex-wrap -mx-3 mt-6">
                                        <div class="w-full md:w-12/12 px-3 md:mb-0">
                                            <label for="departamento" class="block">
                                                {{ trans('formclient.Departamento*') }}
                                            </label>
                                            <select wire:model="entrega.departamento" id="departamento"
                                                class="appearance-none block w-full rounded-xl text-black border border-gray-400 py-2 px-2 mb-3 leading-tight focus:outline-none focus:bg-white">
                                                <option value="" selected>
                                                    {{ trans('formclient.Selecciona un departamento') }}</option>
                                                @foreach ($departamentos as $departamento)
                                                    <option value="{{ $departamento->id }}">{{ $departamento->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @unless (!empty($entrega['departamento']))
                                                <x-input-error for="entrega.departamento" />
                                            @endunless
                                        </div>

                                        {{-- Provincia --}}
                                        <div class="w-full md:w-12/12 px-3 md:mb-0">
                                            <label for="provincia" class="block">
                                                {{ trans('formclient.Provincia*') }}
                                            </label>
                                            <select wire:model="entrega.provincia" id="provincia"
                                                class="appearance-none block w-full rounded-xl text-black border border-gray-400 py-2 px-2 mb-3 leading-tight focus:outline-none focus:bg-white"
                                                @if (empty($entrega['departamento'])) disabled @endif>
                                                <option value="" selected>
                                                    {{ trans('formclient.Selecciona una provincia') }}</option>
                                                @foreach ($provincias as $provincia)
                                                    <option value="{{ $provincia->id }}">{{ $provincia->name }}</option>
                                                @endforeach
                                            </select>
                                            @unless (!empty($entrega['provincia']))
                                                <x-input-error for="entrega.provincia" />
                                            @endunless
                                        </div>

                                        {{-- Distrito --}}
                                        <div class="w-full md:w-12/12 px-3 md:mb-0">
                                            <label for="distrito" class="block">
                                                {{ trans('formclient.Distrito*') }}
                                            </label>
                                            <select wire:model="entrega.distrito" id="distrito"
                                                class="appearance-none block w-full rounded-xl text-black border border-gray-400 py-2 px-2 mb-3 leading-tight focus:outline-none focus:bg-white"
                                                @if (empty($entrega['provincia'])) disabled @endif>
                                                <option value="" selected>
                                                    {{ trans('formclient.Selecciona un distrito') }}</option>
                                                @foreach ($distritos as $distrito)
                                                    <option value="{{ $distrito->id }}">{{ $distrito->name }}</option>
                                                @endforeach
                                            </select>
                                            @unless (!empty($entrega['distrito']))
                                                <x-input-error for="entrega.distrito" />
                                            @endunless
                                        </div>

                                        <div class="w-full md:w-12/12 px-3 md:mb-0">
                                            <label class="">
                                                {{ trans('formclient.Domicilio*') }}
                                            </label>
                                            <input wire:model="entrega.domicilio"
                                                class="appearance-none block w-full rounded-xl text-black border border-gray-400 py-2 px-2 mb-3 leading-tight focus:outline-none focus:bg-white"
                                                type="text" placeholder="Ejem: Salida Piura">
                                            @unless (!empty($entrega['domicilio']))
                                                <x-input-error for="entrega.domicilio" />
                                            @endunless
                                        </div>
                                        <div class="w-full md:w-12/12 px-3 md:mb-0">
                                            <label class="">
                                                {{ trans('formclient.Referencia*') }}
                                            </label>
                                            <input wire:model="entrega.referencia"
                                                class="appearance-none block w-full rounded-xl text-black border border-gray-400 py-2 px-2 mb-3 leading-tight focus:outline-none focus:bg-white"
                                                type="text" placeholder="Ejem:Casa de 3 pisos">
                                            @unless (!empty($entrega['referencia']))
                                                <x-input-error for="entrega.referencia" />
                                            @endunless
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-center py-2 gap-1">
                                        <button wire:click.prevent="cancel"
                                            class="disabled:opacity-25 flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-red-400 active:bg-red-900 transition ease-in-out duration-150">
                                            {{ trans('formclient.CANCELAR') }}
                                        </button>
                                        <button wire:click.prevent="store()" wire:loading.attr="disabled"
                                            wire:target="store"
                                            class="disabled:opacity-25 flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-900 transition ease-in-out duration-150">
                                            @if ($ruteCreate)
                                                {{ trans('formclient.REGISTRAR') }}
                                            @else
                                                Actualizar
                                            @endif
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="xl:col-span-2 lg:col-span-2 md:col-span-1 sm:col-span-1">
                        <div class="grid grid-cols-1 xl:grid-cols-2 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 gap-2">
                            {{-- 2da COLUMNA -> PRÓXIMOS PASOS --}}
                            <div class="">
                                <div class="bg-white px-6 py-3 rounded-lg">
                                    <span
                                        class="font-extrabold text-xl">{{ trans('formclient.Próximos pasos') }}</span>
                                    <div class="pro_pa">
                                        <div class="pro_pa_main mt-[1.25rem]">
                                            <div class="border rounded-md px-5 py-4 relative mb-2">
                                                @if (isset($clientData))
                                                    <div class="flex items-center space-x-4 ">
                                                        <div
                                                            class="cursor-pointer space-x-2 px-2 py-2 bg-gray-200 rounded-full">
                                                            <i class="fa-solid fa-user text-orange-600 fa-xl"></i>
                                                        </div>
                                                        <div class="cursor-pointer space-x-2 text-[1rem] font-medium">
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
                                                                        {{ $clientData['materno'] }}
                                                                    </p>
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

                                            <div
                                                class="border rounded-md px-5 py-4 bg-gradient-to-r from-indigo-800 to-blue-600 mb-2">
                                                <div class="flex items-center space-x-4">
                                                    <div
                                                        class="cursor-pointer space-x-2 px-2 py-2 bg-gray-300 rounded-full">
                                                        <i class="fa-solid fa-truck-ramp-box text-white fa-lg"></i>
                                                    </div>
                                                    <div class="cursor-pointer space-x-2 text-gray-200 text-[1rem]">
                                                        {{ trans('formclient.Entrega') }}
                                                    </div>
                                                </div>
                                                <div class="flex text-gray-300 pt-2 justify-center">
                                                    <svg class="animate-spin h-7 w-7 " fill="#E5E7EB"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                        <path
                                                            d="M464 256A208 208 0 1 0 48 256a208 208 0 1 0 416 0zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256z" />
                                                    </svg>
                                                    <samp
                                                        class="text-lg px-2 text-white animate-pulse font-bold">{{ trans('formclient.En proceso...') }}</samp>
                                                </div>
                                            </div>

                                            <div class=" border rounded-md px-5 py-4">
                                                <div class="flex items-center space-x-4 ">
                                                    <div
                                                        class="cursor-pointer space-x-2 px-2 py-2 bg-gray-200 rounded-full">
                                                        <i class="fa-regular fa-credit-card text-orange-600 fa-lg"></i>
                                                    </div>
                                                    <div class="cursor-pointer space-x-2">
                                                        {{ trans('formclient.Pago') }}
                                                    </div>
                                                </div>
                                                <div class="flex justify-between text-gray-600">
                                                    <span>{{ trans('formclient.Esperando a que se complete la entrega') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="border w-full mt-4"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- 3ra COLUMNA -> RESUMEN DE COMPRA --}}
                            <div class="bg-white px-6 py-3 rounded-lg">
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
    </div>

    @include('partials.footer')
</div>
