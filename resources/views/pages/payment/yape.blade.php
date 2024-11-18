<div class="py-6 pt-0 relative" x-data="{ estadoCarga: @entangle('estadoCarga') }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" x-data="{ openImage: false, imageUrl: '' }">
            <!-- Modal para Visualizar la captura del pago -->
            <div x-show="openImage" @click.away="openImage = false"
                class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-75 overflow-hidden"
                style="display: none;" x-cloak>
                <div @click="openImage = false" class="absolute inset-0"></div>
                <div class="bg-white p-3 relative rounded-md shadow-lg z-10 min-w-[20rem] max-w-[22rem] w-full"
                    style="height: calc(100vh - 1rem);">
                    <div class="w-full h-full flex items-center justify-center">
                        <img :src="imageUrl" alt="Preview"
                            class="object-contain w-full h-full max-h-full max-w-full">
                    </div>
                    <div class="text-center absolute top-0 right-0">
                        <button @click.stop="openImage = false"
                            class="px-3 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-500">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Logo del Yape -->
            <div class="flex justify-center py-5"> <img class="max-w-[80px] rounded-md" src="/img/pay/pay_yape.jpg"
                    alt=""></div>

            <!-- Resumen del Pago -->
            <div class="flex justify-center pb-2">
                <table class="w-2/3 divide-y divide-gray-200">

                    <thead class="bg-gray-50 ">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ trans('formclient.Recibes') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                S/.{{ $total }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ trans('formclient.Moneda') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 ">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ trans('formclient.Comisión') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">S/.0.00</td>
                            <td class="px-6 py-4 whitespace-nowrap">Soles</td>
                        </tr>

                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ trans('formclient.Pagas') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">S/.{{ $total }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Soles</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Formulario de Pago -->
            @foreach ($yapepage as $index => $item)
                <div class="flex justify-center items-center">
                    <div class="flex md:gap-1 lg:gap-5 flex-col md:flex-row">
                        <div class="flex items-center justify-center">
                            <div
                                class="max-w-[300px] max-h-[300px] mr-1 sm:mr-1 xl:mr-10 mb-5 bg-[#7A37AC] p-3 md:p-2 lg:p-5 rounded-md text-white uppercase font-bold text-center">
                                <samp class="line-clamp-1">{{ $item->name }}</samp>
                                @if ($item->image)
                                    <img class="w-full h-auto max-h-[220px] object-cover rounded-md"
                                        src="{{ Storage::url($item->image->url) }}" alt="{{ $item->name }}">
                                @else
                                    <img class="w-full h-full object-cover rounded-md"
                                        src="{{ asset('../img/default.jpg') }}" alt="No Image">
                                @endif
                                <span>{{ $item->telefono }}</span>
                            </div>
                        </div>

                        <div class="col-span-2 relative">

                            <form autocomplete="off" enctype="multipart/form-data">

                                <div class="flex flex-col sm:flex-row justify-between mx-2 pb-2 gap-2">
                                    <!-- Fecha y Hora / Operacion-->
                                    <div class="grid gap-2 w-full">
                                        <div class="mb-2 md:mr-2 md:mb-0 w-full">
                                            <label for="name"
                                                class="font-bold">{{ trans('formclient.Nombre Completo:') }}</label>
                                            <input type="text" id="name" wire:model="yapepayment.name"
                                                class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                                placeholder="Del que envia" required maxlength="255"
                                                @if (auth()->user()->name && auth()->user()->apellido_paterno && auth()->user()->apellido_materno) readonly @endif>
                                        </div>
                                        <div class="mb-2 md:mr-2 md:mb-0 w-full">
                                            <label for="fecha"
                                                class="font-bold">{{ trans('formclient.Fecha y Hora:') }}</label>
                                            <input type="date" id="fecha" wire:model="yapepayment.fecha"
                                                class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm cursor-pointer"
                                                placeholder="00:00:00:2024" required>
                                            @unless (!empty($yapepayment['fecha']))
                                                <x-input-error for="yapepayment.fecha" />
                                            @endunless
                                        </div>
                                        <div class="mb-2 md:mr-2 md:mb-0 w-full">
                                            <label for="operacion" class="font-bold">N° de Operación:</label>
                                            <input type="tel" id="operacion" wire:model="yapepayment.operacion"
                                                class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                                type="tel" pattern="[0-9]*" placeholder=" "
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="7"
                                                required>
                                            @unless (!empty($yapepayment['operacion']) && strlen($yapepayment['operacion']) === 7)
                                                <x-input-error for="yapepayment.operacion" />
                                            @endunless
                                        </div>
                                    </div>
                                    <!-- Imagen -->
                                    <div class="w-full">
                                        <label for="yapepayment.image" class="font-bold">Captura del Pago:</label>
                                        <div class="border border-gray-300 rounded-lg relative">
                                            <label
                                                class="text-white text-sm rounded-t-lg bg-gray-600 focus:bg-gray-600 active:bg-gray-700 inline-flex items-center justify-center w-full px-4 py-2 cursor-pointer">
                                                <i class="fa-solid fa-upload mr-1"></i>Cargar Imagen
                                                <input wire:model="yapepayment.image" type="file" accept="image/*"
                                                    hidden />
                                            </label>
                                            <div wire:loading wire:target="yapepayment.image"
                                                class="absolute top-10 left-0 z-50">
                                                <div id="alert-4"
                                                    class="flex p-3 mb-4 text-yellow-800 rounded-lg bg-yellow-50"
                                                    role="alert">
                                                    <i class="fa-solid fa-circle-exclamation"></i>
                                                    <div class="ml-2 text-sm font-medium">
                                                        Espere un momento por favor, la imagen se está procesando
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Contenedor de la imagen con el botón de Visualizar -->
                                            <div class="p-2 py-4 relative">
                                                @if (!empty($yapepayment['image']))
                                                    <div class="text-center">
                                                        <img id="uploadedImage_{{ $index }}"
                                                            class="mx-auto w-32 h-[8.5rem] md:h-[7.5rem] object-cover rounded-md"
                                                            src="{{ $yapepayment['image']->temporaryUrl() }}"
                                                            alt="">
                                                        <button type="button"
                                                            @click="openImage = true; imageUrl = '{{ $yapepayment['image']->temporaryUrl() }}'"
                                                            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 absolute top-[-2px] right-1">Visualizar</button>
                                                    </div>
                                                @else
                                                    <li id="empty"
                                                        class="h-full w-full text-center flex flex-col items-center justify-center">
                                                        <img class="mx-auto w-32 h-28 md:h-24"
                                                            src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png"
                                                            alt="no data" />
                                                        <span class="text-small text-gray-500">Ningún archivo
                                                            seleccionado</span>
                                                    </li>
                                                @endif
                                            </div>
                                            <!-- Modal para la visualización de la imagen -->
                                            <div id="imageModal_{{ $index }}"
                                                class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-75"
                                                style="display: none;">
                                                <div id="modalOverlay" class="absolute inset-0"></div>
                                                <div
                                                    class="bg-white p-4 rounded-md shadow-lg z-10 min-w-[21rem] max-w-[22rem] w-full h-full max-h-[40rem]">
                                                    <img id="modalImage_{{ $index }}" :src="imageUrl"
                                                        alt="Preview" class="w-auto h-auto">
                                                    <div class="text-center mt-4">
                                                        <button type="button" @click="openImage = false"
                                                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-500">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @unless (!empty($yapepayment['image']))
                                            <x-input-error for="yapepayment.image" />
                                        @endunless
                                    </div>
                                </div>

                                <!-- Mensaje del Yape -->
                                <div class="flex justify-between mx-2 mb-6">
                                    <div class="mb-2 md:mr-2 md:mb-0 w-full">
                                        <label class="font-bold">{{ trans('formclient.Mensaje del Yape') }}</label>
                                        <div id="container"
                                            class="flex items-center justify-between bg-yellow-400 p-2 pt-1 pb-1 rounded-md">
                                            <div id="copyText">
                                                <span>{{ $item->mensaje }}</span>
                                            </div>
                                            <div onclick="copyToClipboard('{{ $item->mensaje }}')"
                                                aria-required="true"
                                                style="text-decoration: none; color: inherit; cursor: pointer;">
                                                <i class="fas fa-copy"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón visible para procesar Pago -->
                                <button type="submit" wire:loading.attr="disabled" wire:target="store, image"
                                    wire:click.prevent="store()"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ trans('formclient.Registrar') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Overlay de Carga para el Envío del Formulario -->
    <div :class="{ 'block': estadoCarga, 'hidden': !estadoCarga }"
        class="bg-slate-900 z-50 fixed inset-0 flex items-center justify-center" style="opacity: 0.5;" x-cloak>
        <div class="text-white text-lg flex items-center justify-center">
            <p>
                Procesando Pago <i class="fas fa-circle-notch fa-spin fa-1x pl-3"></i>
            </p>
        </div>
    </div>

    {{-- <div wire:loading wire:target="store"
        class="bg-slate-900 z-50 fixed inset-0 flex items-center justify-center" style="opacity: 0.5;" x-cloak>
        <div class="text-white text-lg flex items-center justify-center h-full">
                Procesando Pago <i class="fas fa-circle-notch fa-spin fa-1x pl-3"></i>
        </div>
    </div> --}}

    <script>
        function copyToClipboard(text) {
            var dummy = document.createElement("textarea");
            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);
            document.getElementById("container").style.backgroundColor = "orange";
            document.getElementById("copyText").innerText = "Texto Copiado";
            setTimeout(function() {
                document.getElementById("copyText").style.display = "block";
                document.getElementById("container").style.backgroundColor = "yellow";
                document.getElementById("copyText").innerText = "{{ $item->mensaje }}";
            }, 1000);
        }
    </script>
</div>
