@section('header', __('Artículos'))
@section('section', __('Listado de Artículos'))

<div>
    <style>
        .data-display {
            padding: 20px;
        }

        /* Contenedor para las categorías */
        .categories-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        /* Sección para cada categoría */
        .category-section {
            flex: 1;
            margin: 0 10px;
        }

        /* Contenedor para los datos */
        .data-container {
            margin-top: 20px;
        }

        /* Grid para los datos obtenidos */
        .data-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 4 columnas iguales */
            gap: 20px;
        }

        /* Estilo para cada ítem de datos */
        .data-item {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Imagen del ítem */
        .item-image {
            object-fit: cover;
            width: 100%;
            max-width: 292px;
            min-height: 182px;
            max-height: 182px;
            height: 100vh;
            margin-bottom: 10px;
        }

        /* Título del ítem */
        .item-title {
            margin: 10px 0;
            font-size: 0.8em;
            text-align: center;
            height: 40px;
        }

        /* Información del ítem */
        .data-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }

        /* Avatar */
        .avatar {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }

        /* Autor y fecha */
        .author,
        .date {
            font-size: 0.7em;
        }
    </style>
    <div class="relative w-full max-w-2xl px-2 lg:max-w-7xl mx-auto pb-5">
        <header class="grid grid-cols-3 gap-4 py-5">

            <a href="/">
                <div class="flex gap-5 items-center">
                    <svg class="h-12 w-auto text-white lg:h-16 lg:text-[#FF2D20]" viewBox="0 0 62 65" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253ZM59.893 27.9844V16.6121L55.7013 19.0252L49.9104 22.3593V33.7317L59.8942 27.9844H59.893ZM47.9149 48.5566V37.1768L42.2187 40.4299L25.953 49.7133V61.2003L47.9149 48.5566ZM1.99677 9.83281V48.5566L23.9562 61.199V49.7145L12.4841 43.2219L12.4804 43.2194L12.4754 43.2169C12.4368 43.1945 12.4044 43.1621 12.3682 43.1347C12.3371 43.1097 12.3009 43.0898 12.2735 43.0624L12.271 43.0586C12.2386 43.0275 12.2162 42.9888 12.1887 42.9539C12.1638 42.9203 12.1339 42.8916 12.114 42.8567L12.1127 42.853C12.0903 42.8156 12.0766 42.7707 12.0604 42.7283C12.0442 42.6909 12.023 42.656 12.013 42.6161C12.0005 42.5688 11.998 42.5177 11.9931 42.4691C11.9881 42.4317 11.9781 42.3943 11.9781 42.3569V15.5801L6.18848 12.2446L1.99677 9.83281ZM12.9777 2.36177L2.99764 8.10652L12.9752 13.8513L22.9541 8.10527L12.9752 2.36177H12.9777ZM18.1678 38.2138L23.9574 34.8809V9.83281L19.7657 12.2459L13.9749 15.5801V40.6281L18.1678 38.2138ZM48.9133 9.14105L38.9344 14.8858L48.9133 20.6305L58.8909 14.8846L48.9133 9.14105ZM47.9149 22.3593L42.124 19.0252L37.9323 16.6121V27.9844L43.7219 31.3174L47.9149 33.7317V22.3593ZM24.9533 47.987L39.59 39.631L46.9065 35.4555L36.9352 29.7145L25.4544 36.3242L14.9907 42.3482L24.9533 47.987Z"
                            fill="currentColor" />
                    </svg>
                    <span class="text-3xl">Web Scraping</span>
                </div>
            </a>

            <div class="flex gap-4 items-center justify-center">
                <a href="{{ route('articles') }}" class="hover:text-red-600">
                    <span class="text-md">Inicio</span>
                </a>
                <a href="{{ route('planes') }}" class="hover:text-red-600">
                    <span class="text-md">Planes</span>
                </a>
            </div>

            @if (Route::has('login'))
                <nav class="flex flex-1 justify-end gap-3 items-center">
                    @auth
                        <a href="{{ route('admin.home') }}"
                            class="rounded-lg px-4 py-2 text-white font-semibold transition duration-300 ease-in-out transform bg-gradient-to-r from-[#FF2D20] to-[#FF6A3D] shadow-lg hover:shadow-xl hover:from-[#FF6A3D] hover:to-[#FF2D20] focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#FF2D20]">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="rounded-lg px-4 py-2 bg-[#FF2D20] text-white font-semibold shadow-md transition duration-300 ease-in-out transform hover:bg-[#e6221d] hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#FF2D20] focus:ring-opacity-50">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="rounded-lg px-4 py-2 bg-[#FF2D20] text-white font-semibold shadow-md transition duration-300 ease-in-out transform hover:bg-[#e6221d] hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#FF2D20] focus:ring-opacity-50">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        <x-card>
            <div class="relative flex flex-col w-full h-full text-gray-700 gap-2">
                <div class="flex flex-col justify-between gap-8 md:flex-row md:items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-full md:w-72">
                            <x-input-label wire:model.live="search" search label="Buscar" />
                        </div>

                        <div wire:loading>
                            <i class="fa-solid fa-spinner fa-spin fa-lg"></i>
                        </div>
                    </div>

                    <x-toasts :showModal="$showModal" />

                    <div class="grid grid-cols-2 gap-3 max-w-md w-full">
                        <x-select-label label="Diario" wire:model.live="diarioSelected">
                            <option value="" selected>Todos</option>
                            @foreach ($diarios as $url => $nombre)
                                <option value="{{ $url }}">{{ $nombre }}</option>
                            @endforeach
                        </x-select-label>

                        @if ($diariosCategoria->isNotEmpty())
                            <x-select-label label="Categoría" wire:model.live="categoriaSelected">
                                <option value="" selected>Todos</option>
                                @foreach ($diariosCategoria as $categoria)
                                    <option value="{{ $categoria->name }}">{{ $categoria->name }}</option>
                                @endforeach
                            </x-select-label>
                        @endif
                    </div>
                    <div class="flex justify-center gap-2">
                        <x-button-gradient class="flex items-center gap-2" wire:click="descargarCSV()">
                            <i class="fa-solid fa-plus"></i>
                            <span class="hidden sm:block">Decargar CSV</span>
                        </x-button-gradient>
                    </div>
                </div>
                <x-table-container>
                    <x-loading class="pt-10" for="userState">Cargando...</x-loading>
                    <div class="data-display">
                        <div class="data-container">
                            <div class="data-grid">
                                @foreach ($articulos as $articulo)
                                    <a href="{{ route('article-detail', ['titulo' => $articulo['path']]) }}"
                                        target="_blank">
                                        <div class="data-item">
                                            <img class="item-image" src="{{ $articulo->imagen }}"
                                                alt="{{ $articulo->titulo }}" />
                                            <h3 class="item-title">{{ $articulo->titulo }}</h3>
                                            <div class="data-info">
                                                <img class="avatar"
                                                    src="{{ $articulo->avatar !== 'Sin avatar' ? $articulo->avatar : 'images/usuario.png' }}"
                                                    alt="Avatar" />
                                                <span class="author">{{ $articulo->autor }}</span>
                                                <span class="date">{{ $articulo->fecha }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @if (!$articulos->count())
                        <div class="no-resultados">
                            <div class="mensaje">
                                <p class="text-center text-sm p-3">No existe ningún registro coincidente con la
                                    búsqueda.
                                </p>
                            </div>
                            <div class="imagen-sin-resultados">
                                <img src="images/resultados_sin_busqueda.png" alt="Sin resultados" class="mx-auto w-96">
                            </div>
                        </div>
                    @endif
                </x-table-container>
                @if ($articulos->count())
                    {{ $articulos->links() }}
                @endif
            </div>
        </x-card>
    </div>
    <?php

    use Symfony\Component\DomCrawler\Crawler;
    use GuzzleHttp\Client;
    use App\Models\Moneda;

    // Función para obtener el contenido HTML de la página
    function obtenerContenidoHTML($url)
    {
        // Crear una instancia del cliente Guzzle
        $client = new Client();

        try {
            // Configurar opciones para la solicitud
            $options = [
                'connect_timeout' => 100,
                'timeout' => 100,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0',
                ],
                'verify' => false, // Desactivar verificación SSL si es necesario
            ];

            // Hacer la solicitud GET a la URL con las opciones configuradas
            $response = $client->request('GET', $url, $options);

            // Verificar si la respuesta fue exitosa (código de estado 200)
            if ($response->getStatusCode() === 200) {
                // Obtener el contenido HTML de la respuesta
                return $response->getBody()->getContents();
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    // Función para extraer y actualizar los datos de la moneda
    function extraerYActualizarDatosMoneda($html)
    {
        // Crear una instancia de Crawler para analizar el HTML
        $crawler = new Crawler($html);

        // Buscar la fila específica por su ID
        $fila = $crawler->filter('table.table.table-sm.tableFlex');
        // dd($fila);

        if ($fila->count() > 0) {
            // Obtener las celdas de la fila
            $celdas = $fila->filter('td');
            // dd($celdas);
            // Verificar si hay al menos 2 celdas en la fila
            if ($celdas->count() >= 0) {
                // Obtener el valor de la moneda y el promedio ponderado
                $moneda = $celdas->eq(0)->text();
                $promedio_ponderado = $celdas->eq(3)->text();

                // Usamos una expresión regular para extraer el número decimal
                preg_match('/\d+,\d+/', $promedio_ponderado, $matches);

                if (!empty($matches)) {
                    // Convertimos el número al formato correcto para operaciones (de coma a punto)
                    $numero = str_replace(',', '.', $matches[0]);

                    // Redondeamos a dos decimales
                    $numero_redondeado = round((float) $numero, 2);

                    // dd($numero_redondeado); // Mostrará 3.80
                } else {
                    // dd('No se encontró un número decimal');
                }
                // Actualizar en la base de datos
                $monedaUpdate = Moneda::find(1);

                if ($monedaUpdate) {
                    $monedaUpdate->update([
                        'tasa' => $numero_redondeado,
                    ]);

                    return true;
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    function monedaActualizadaHoy()
    {
        // Obtener la instancia de Moneda
        $moneda = Moneda::find(1);

        // Verificar si la moneda existe antes de acceder a sus propiedades
        if ($moneda) {
            // Verificar si la moneda ha sido actualizada en el día actual
            return $moneda->updated_at->isToday();
        }

        // Si no se encuentra la moneda, devolver false o manejar el caso según sea necesario
        return false;
    }

    // Verificar si la moneda ha sido actualizada hoy antes de hacer una nueva solicitud
    if (!monedaActualizadaHoy()) {
        $url = 'https://www.eleconomista.es/cruce/USDPEN';
        // Obtener el contenido HTML de la página
        $html = obtenerContenidoHTML($url);

        // dd($html);

        if ($html !== false) {
            // dd('pasó');
            // Extraer y actualizar los datos de la moneda
            if (extraerYActualizarDatosMoneda($html)) {
                echo "<div style='display: none;'>";
                echo '<span>Datos actualizados correctamente.</span><br>';
                echo '</div>';
            } else {
                echo "<div style='display: none;'>";
                echo '<span>La fila de datos no tiene suficientes celdas.</span><br>';
                echo '</div>';
            }
        } else {
            echo "<div style='display: none;'>";
            echo '<span>La solicitud no fue exitosa. No se pudo obtener el contenido HTML o se excedió el tiempo de espera.</span><br>';
            echo '</div>';
        }
    } else {
        echo "<div style='display: none;'>";
        echo '<span>La moneda ya ha sido actualizada hoy.</span><br>';
        echo '</div>';
    }

    ?>
</div>
