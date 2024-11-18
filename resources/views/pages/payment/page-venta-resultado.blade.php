<div class="py-10 bg-gray-200 text-sm gap-2">
    <div class="w-11/12 lg:w-2/6 mx-auto">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="18"
                        height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>

            </div>
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
            <div class="bg-indigo-700 h-6 w-6 rounded-full shadow flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="18"
                    height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFFFFF" fill="none"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" />
                    <path d="M5 12l5 5l10 -10" />
                </svg>
            </div>
        </div>
    </div>
    <div class="flex px-20 py-10 gap-2 justify-center">
        <div class="flex-row space-y-2">
            @if (session('statusMessage'))
                @php
                    $statusTypeClass = session('statusType') === 'success' ? 'bg-green-500' : 'bg-red-500';
                    $iconClass = session('statusType') === 'success' ? 'fa-check' : 'fa-face-sad-tear';
                @endphp
                <div class="bg-white px-2 py-2 rounded-lg">
                    <div class="text-white {{ $statusTypeClass }} rounded-lg p-5">
                        <div class="flex items-center space-x-4 justify-center">
                            <div class="cursor-pointer space-x-2 px-3 py-3 bg-white rounded-full">
                                <i
                                    class="fa-solid {{ $iconClass }} text-{{ $statusTypeClass === 'bg-green-500' ? 'green' : 'red' }}-500 fa-xl"></i>
                            </div>
                        </div>
                        <span class="font-extrabold text-xl flex items-center space-x-4 justify-center">
                            {{-- {{ session('statusType') === 'success' ? 'Su pago se ha completado con éxito' : 'Lo sentimos, hubo un error al finalizar la compra' }} --}}

                            {{ session('statusType') === 'success' ? 'Su pago se ha completado con éxito' : 'Lo sentimos, hubo un error al finalizar la compra' }}

                        </span>
                    </div>
                    <div class="flex items-center space-x-4 justify-center">
                        <div class="mt-4 w-64 ">
                            <div
                                class="grid {{ session('statusType') === 'success' ? 'grid-rows-2' : 'grid-rows-1' }} gap-4">
                                @if (session('statusType') === 'success')
                                    <p>A la brevedad recibirá su comprobante de pago</p>
                                @endif
                                <a href="/" class="border px-4 py-2 rounded-md text-center">Volver al inicio</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
