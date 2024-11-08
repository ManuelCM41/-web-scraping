<?php

namespace App\Livewire\Client;

use App\Models\Article as ModelsArticle;
use App\Models\Category;
use App\Models\Scraping;
use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;
use Usernotnull\Toast\Concerns\WireToast;

class Article extends Component
{
    use WithPagination;
    use WireToast;

    public $search;
    public $category;
    public $diarios, $diariosCategoria;
    public $diarioSelected, $categoriaSelected;

    public $showModal = false;

    protected $listeners = ['render', 'delete', 'openModal', 'closeModal'];

    public function refreshArticle()
    {
        $this->render();
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function render()
    {
        $articulos = ModelsArticle::orderBy('created_at', 'desc')->paginate(16);

        if ($this->search != null || $this->diarioSelected != null || $this->categoriaSelected != null) {
            // Verifica si el usuario está autenticado
            if (!Auth::check()) {
                $this->search = '';
                $this->diarioSelected = '';
                $this->categoriaSelected = '';
                $this->openModal();
            }
        }

        // Definimos los diarios
        $this->diarios = [
            'https://diariosinfronteras.com.pe/' => 'Diario Sin Fronteras',
            'https://losandes.com.pe/' => 'Los Andes',
            'https://larepublica.pe/' => 'La República',
        ];

        $this->diariosCategoria = Category::all();

        if (Auth::check()) {
            $this->guardarArticulos($this->search);
            $articulos = ModelsArticle::where('url', 'like', '%' . $this->search . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(16);

            // Si se ha seleccionado un diario, filtramos las categorías por la URL correspondiente
            if ($this->diarioSelected) {
                $this->diariosCategoria = Category::where('urlPrincipal', $this->diarioSelected)->get();
                $articulos = ModelsArticle::where('urlPrincipal', $this->diarioSelected)
                    ->orderBy('created_at', 'desc')
                    ->paginate(16);
                if ($this->categoriaSelected) {
                    # code...
                    $this->guardarArticulosCategoria($this->diarioSelected, $this->categoriaSelected);
                    $articulos = ModelsArticle::where('urlPrincipal', $this->diarioSelected)
                        ->where('categoria', $this->categoriaSelected)
                        ->orderBy('created_at', 'desc')
                        ->paginate(16);
                }
            } else {
                // Si no hay diario seleccionado, mostramos todas las categorías
                $this->diariosCategoria = Category::all();
            }
        }

        return view('livewire.client.article', compact('articulos'));
    }

    public function normalizarTexto($texto)
    {
        $transliteraciones = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ü' => 'u',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'Ü' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N',
        ];

        // Reemplazar los caracteres especiales
        $texto = strtr($texto, $transliteraciones);

        // Reemplazar comillas curvadas y otros caracteres no deseados
        $texto = str_replace(['“', '”', '‘', '’'], '', $texto);

        // Eliminar signos de interrogación, exclamación y otros caracteres no deseados
        $texto = str_replace(['?', '!', '¡', '¿'], '', $texto);

        return $texto;
    }

    public function cargarArticulos($categoria)
    {
        $this->category = $categoria;
    }

    public function obtenerContenidoHTML($url)
    {
        $client = new Client();

        try {
            $options = [
                'connect_timeout' => 5,
                'timeout' => 5,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
                ],
                'verify' => false, // Desactivar verificación SSL si es necesario
            ];

            $response = $client->request('GET', $url, $options);

            if ($response->getStatusCode() === 200) {
                return $response->getBody()->getContents();
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function extraerDatos($html)
    {
        $crawler = new Crawler($html);

        // Seleccionar todos los artículos por el selector CSS de clase
        $articulos = $crawler->filter('.tdi_88, .td-category-pos-above, .sp-pcp-post-thumb-area, .bd-fm-post-0, fm-post-sec, .ws-post-first, .ws-post-sec, .MainSpotlight_primary__other__PEhAc, .MainSpotlight_secondarySpotlight__item__UWjdv, .MainSpotlight_lateral__item__PuIEF, .ItemSection_itemSection__D8r12');
        $categorias = $crawler->filter('.menu-item-object-category, .Header_container-header_menu-secciones-item__3sngP, .bd_menu_item');

        $datosArticulos = [];
        $datosCategorias = [];

        // Recorrer cada artículo y extraer el título, el extracto, la categoría y la imagen
        $articulos->each(function (Crawler $articulo) use (&$datosArticulos) {
            $titulo = $articulo->filter('.entry-title a, figcaption h1, .extend-link')->count() > 0 ? $articulo->filter('.entry-title a, figcaption h1, .extend-link')->text() : 'Sin título';
            $extracto = $articulo->filter('.td-excerpt')->count() > 0 ? $articulo->filter('.td-excerpt')->text() : 'Sin extracto';
            $categoria = $articulo->filter('.td-post-category')->count() > 0 ? $articulo->filter('.td-post-category')->text() : 'Sin categoria';

            // Primero intenta con 'data-img-url', luego con 'src'
            $imagen = 'Sin imagen';

            // Verificar si existe un enlace con clase 'img' y atributo 'style' que contiene la imagen
            if ($articulo->filter('a.img, .ws-post-first, .ws-post-sec')->count() > 0) {
                $style = $articulo->filter('a.img, .ws-post-first, .ws-post-sec')->attr('style');
                // Usar una expresión regular para extraer la URL dentro de 'background-image:url(...)'
                preg_match('/background-image:url\((.*?)\)/', $style, $matches);

                // Si se encuentra una coincidencia, el segundo elemento del array contiene la URL de la imagen
                if (isset($matches[1])) {
                    $imagen = $matches[1];
                }
            }

            // Si no se encontró imagen en el 'style', intentar con los atributos 'data-img-url' o 'src'
            if ($imagen === 'Sin imagen' && $articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->count() > 0) {
                // Intentar extraer primero el atributo 'data-img-url'
                if ($articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('data-img-url')) {
                    $imagen = $articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('data-img-url');
                }
                // Si no existe 'data-img-url', intentar con 'src'
                elseif ($articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('src')) {
                    $imagen = $articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('src');
                }
            }

            $elementosFecha = $articulo->filter('.entry-date, .fmm-date span, span.ws-info span, div.post-date-bd span');

            if ($elementosFecha->count() > 0) {
                // Muestra los elementos encontrados
                // dd($elementos->html()); // Muestra el HTML de los elementos encontrados

                // Muestra el texto del primer elemento
                $fecha = $elementosFecha->first()->text();
            } elseif ($elementosFecha->count() > 0) {
                // Muestra un mensaje si no se encuentran elementos
                // dd('No se encontraron elementos con los selectores especificados.');
                $fecha = $elementosFecha->text();
            } else {
                $fecha = 'Sin fecha';
            }

            // Extraer el separador
            $separador = $articulo->filter('.td-post-author-name span')->count() > 0 ? $articulo->filter('.td-post-author-name span')->text() : 'Sin separador';

            // Extraer el avatar (imagen)
            $avatar = $articulo->filter('.td-author-photo img')->count() > 0 ? $articulo->filter('.td-author-photo img')->attr('src') : 'Sin avatar';

            // Extraer la URL del artículo
            $urlCompleta = $articulo->filter('.entry-title a')->count() > 0 ? $articulo->filter('.entry-title a')->attr('href') : 'Sin URL';

            // Extraer la parte específica de la URL
            $url = 'Sin URL';
            $href = $articulo->filter('.entry-title a, .td-image-wrap, .sp-pcp-thumb, a.extend-link')->attr('href');

            $href = ltrim($href, '/');

            // Verificar si la URL del 'href' es relativa y completarla
            if (strpos($href, 'http') === false) {
                $baseUrl = $this->search; // Cambia esto por la URL base correcta si es necesario
                $url = $baseUrl . $href;
            } else {
                $url = $href;
            }

            // Separar URL principal y el resto del path
            if ($url !== 'Sin URL') {
                // Extraer la parte principal de la URL
                $urlPrincipal = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . '/';

                if ($urlPrincipal === 'https://losandes.com.pe/') {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');

                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
                        // dd('No se encontraron elementos con los selectores especificados.');
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario los Andes';
                    }
                } elseif ($urlPrincipal === 'https://diariosinfronteras.com.pe/') {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');
                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
                        // dd('No se encontraron elementos con los selectores especificados.');
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario Sin Fronteras';
                    }
                } else {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');
                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
                        // dd('No se encontraron elementos con los selectores especificados.');
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario la República';
                    }
                }

                // Extraer la parte restante (path)
                $path = str_replace($urlPrincipal, '', $url);
            }

            $datosArticulos[] = [
                'titulo' => $titulo,
                'extracto' => $extracto,
                'categoria' => $categoria,
                'imagen' => $imagen,
                'autor' => $autor,
                'separador' => $separador,
                'fecha' => $fecha,
                'avatar' => $avatar,
                'url' => $url,
                'urlPrincipal' => $urlPrincipal,
                'path' => $path,
            ];
        });

        $categorias->each(function (Crawler $categoria) use (&$datosCategorias) {
            $titulo = $categoria->filter('div.tdb-menu-item-text, .Header_container-header_menu-secciones-link__gOmTh, span.menu-label')->count() > 0 ? $categoria->filter('div.tdb-menu-item-text, .Header_container-header_menu-secciones-link__gOmTh, span.menu-label')->text() : 'Sin título';
            // $url = $categoria->filter('a')->count() > 0 ? $categoria->filter('a')->attr('href') : 'Sin URL';
            $slug = Str::slug($titulo);

            $url = 'Sin URL';
            $href = $categoria->filter('a')->attr('href');
            // Quitar el '/' inicial si está presente
            $href = ltrim($href, '/');
            // Verificar si la URL del 'href' es relativa y completarla
            if (strpos($href, 'http') === false) {
                $baseUrl = $this->search; // Cambia esto por la URL base correcta si es necesario
                $url = $baseUrl . $href;
            } else {
                $url = $href;
            }

            // Separar URL principal y el resto del path
            if ($url !== 'Sin URL') {
                // Extraer la parte principal de la URL
                $urlPrincipal = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . '/';
            }

            $datosCategorias[] = [
                'titulo' => $titulo,
                'url' => $url,
                'urlPrincipal' => $urlPrincipal,
                'slug' => $slug,
            ];
        });

        return [
            'articulos' => $datosArticulos,
            'categorias' => $datosCategorias,
        ];
    }

    public function reemplazarGuionPorEspacio($categoria)
    {
        // Reemplaza el guion por un espacio solo si el guion está presente en la categoría
        if (strpos($categoria, '-') !== false) {
            return str_replace('-', ' ', $categoria);
        } else {
            return $categoria;
        }
    }

    public function guardarArticulos($datosArticulos)
    {
        if ($datosArticulos) {
            $url = $datosArticulos;
            // dd($url);

            // Obtener el contenido HTML de la página
            $html = $this->obtenerContenidoHTML($url);

            if ($html !== false) {
                $user = Auth::user();

                // Obtiene el límite de `cantidad_veces` desde la membresía del usuario
                $cantidadLimite = $user->membership->cantidad_veces;

                // Busca o crea el registro en la tabla `Scraping`
                $scraping = Scraping::firstOrNew([
                    'url' => $url,
                    'user_id' => $user->id,
                ]);
                // dd($cantidadLimite);
                // Incrementa la cantidad solo si aún no se ha alcanzado el límite
                if ($scraping->cantidad < $cantidadLimite) {
                    $scraping->cantidad = $scraping->cantidad + 1;
                    $scraping->save();
                } else {
                    $this->search = '';
                    toast()->danger('Has alcanzado el límite de uso para tu plan.', 'Mensaje de Error')->push();
                }

                // Extraer los datos
                // $articulos = $this->extraerDatos($html);
                $datosExtraidos = $this->extraerDatos($html);
                $articulos = $datosExtraidos['articulos']; // Acceder al array de artículos
                $categorias = $datosExtraidos['categorias']; // Acceder al array de categorías
                // dd($categorias);
                foreach ($articulos as $articulo) {
                    ModelsArticle::updateOrCreate(
                        [
                            'url' => $articulo['url'],
                        ],
                        [
                            'urlPrincipal' => $datosArticulos,
                            'path' => $articulo['path'],
                            'titulo' => $articulo['titulo'],
                            'imagen' => $articulo['imagen'] !== 'Sin imagen' ? $articulo['imagen'] : null,
                            'categoria' => $articulo['categoria'],
                            'autor' => $articulo['autor'],
                            'fecha' => $articulo['fecha'],
                            'avatar' => $articulo['avatar'],
                            'extracto' => $articulo['extracto'] !== 'Sin extracto' ? $articulo['extracto'] : null,
                        ],
                    );
                }

                foreach ($categorias as $categoria) {
                    if ($categoria['titulo'] != 'Sin título') {
                        if ($categoria['titulo'] == 'INICIO' || $categoria['titulo'] == 'SUSCRÍBETE') {
                            // No hacer nada si el título es "INICIO" o "SUSCRÍBETE"
                        } else {
                            Category::updateOrCreate(
                                [
                                    'url' => $categoria['url'],
                                ],
                                [
                                    'urlPrincipal' => $categoria['urlPrincipal'],
                                    'name' => $categoria['titulo'],
                                    'slug' => $categoria['slug'],
                                ],
                            );
                        }
                    }
                }
            } else {
                return;
            }
        } else {
            return;
        }
    }

    public function descargarCSV()
    {
        if (!Auth::check()) {
            $this->search = '';
            $this->diarioSelected = '';
            $this->categoriaSelected = '';
            $this->openModal();
        } else {
            if ($this->diarioSelected != null && $this->categoriaSelected != null) {
                $articulos = ModelsArticle::where('urlPrincipal', $this->diarioSelected)
                    ->where('categoria', $this->categoriaSelected)
                    ->orderBy('created_at', 'desc')
                    ->paginate(16);

                // Crear el archivo CSV
                $response = new StreamedResponse(function () use ($articulos) {
                    $handle = fopen('php://output', 'w');

                    // Enviar encabezado UTF-8 BOM
                    fwrite($handle, "\xEF\xBB\xBF");

                    // Encabezados del CSV
                    fputcsv($handle, ['Título', 'Extracto', 'Categoría', 'Imagen', 'Autor', 'Fecha']);

                    foreach ($articulos as $articulo) {
                        // Convertir caracteres a UTF-8
                        $titulo = mb_convert_encoding($articulo['titulo'], 'UTF-8', 'auto');
                        $extracto = mb_convert_encoding($articulo['extracto'], 'UTF-8', 'auto');
                        $categoria = mb_convert_encoding($articulo['categoria'], 'UTF-8', 'auto');
                        $imagen = mb_convert_encoding($articulo['imagen'], 'UTF-8', 'auto');
                        $autor = mb_convert_encoding($articulo['autor'], 'UTF-8', 'auto');
                        $fecha = mb_convert_encoding($articulo['fecha'], 'UTF-8', 'auto');

                        fputcsv($handle, [$titulo, $extracto, $categoria, $imagen, $autor, $fecha]);
                    }

                    fclose($handle);
                });

                // Configurar el encabezado HTTP para descargar el archivo
                $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
                $response->headers->set('Content-Disposition', 'attachment; filename="articulos_' . $this->categoriaSelected . '.csv"');

                toast()->success('Archivo descargado correctamente', 'Mensaje de éxito')->push();

                return $response;
            } else {
                if ($this->diarioSelected) {
                    toast()->warning('Selecciona la Categoría', 'Mensaje de Advertencia')->push();
                } elseif ($this->categoriaSelected) {
                    toast()->warning('Selecciona el Diario', 'Mensaje de Advertencia')->push();
                } else {
                    toast()->warning('Selecciona el Diario y la Categoría', 'Mensaje de Advertencia')->push();
                }
            }
        }
    }

    public function guardarArticulosCategoria($diarios, $categorias)
    {
        if ($diarios) {
            $diarioCategoria = Category::where('name', $categorias)->first();
            // $url = $diarios . 'category/' . $categorias;
            // dd($diarioCategoria->slug);
            if ($diarios === 'https://losandes.com.pe/') {
                $url = $diarios . 'category/' . $diarioCategoria->slug;
                // dd($url);
            } elseif ($diarios === 'https://diariosinfronteras.com.pe/') {
                $url = $diarios . '' . $diarioCategoria->slug;
            } else {
                $url = $diarios . '' . $diarioCategoria->slug;
            }
            // dd($url);
            // Obtener el contenido HTML de la página
            $html = $this->obtenerContenidoHTML($url);

            if ($html !== false) {
                // Extraer los datos
                // $articulos = $this->extraerDatos($html);
                $datosExtraidos = $this->extraerDatosCategoria($html);
                $articulos = $datosExtraidos['articulos']; // Acceder al array de artículos
                // dd($articulos);
                foreach ($articulos as $articulo) {
                    ModelsArticle::updateOrCreate(
                        [
                            'url' => $articulo['url'],
                        ],
                        [
                            'urlPrincipal' => $diarios,
                            'path' => $articulo['path'],
                            'titulo' => $articulo['titulo'], // Condición para buscar el artículo existente
                            'imagen' => $articulo['imagen'] !== 'Sin imagen' ? $articulo['imagen'] : null,
                            'categoria' => $diarioCategoria->name,
                            'autor' => $articulo['autor'],
                            'fecha' => $articulo['fecha'],
                            'avatar' => $articulo['avatar'],
                            'extracto' => $articulo['extracto'] !== 'Sin extracto' ? $articulo['extracto'] : null,
                        ],
                    );
                }
            } else {
                return;
            }
        } else {
            return;
        }
    }

    public function extraerDatosCategoria($html)
    {
        $crawler = new Crawler($html);

        $articulos = $crawler->filter('.tdi_88, .tdb_module_loop, .td-category-pos-above, .sp-pcp-post-thumb-area, .extend-link--outside, .ListSection_list__section--item__zeP_z, .bd-fm-post-0, fm-post-sec, .ws-post-first, .ws-post-sec, .MainSpotlight_primary__other__PEhAc, .MainSpotlight_secondarySpotlight__item__UWjdv, .MainSpotlight_lateral__item__PuIEF, .ItemSection_itemSection__D8r12');

        $datosArticulos = [];

        // Recorrer cada artículo y extraer el título, el extracto, la categoría y la imagen
        $articulos->each(function (Crawler $articulo) use (&$datosArticulos) {
            $titulo = $articulo->filter('.entry-title a, figcaption h1, .extend-link')->count() > 0 ? $articulo->filter('.entry-title a, figcaption h1, .extend-link')->text() : 'Sin título';
            $extracto = $articulo->filter('.td-excerpt')->count() > 0 ? $articulo->filter('.td-excerpt')->text() : 'Sin extracto';
            $categoria = $articulo->filter('.td-post-category .post-cats-bd .fmm-cats')->count() > 0 ? $articulo->filter('.td-post-category')->text() : 'Sin categoria';

            // Primero intenta con 'data-img-url', luego con 'src'
            $imagen = 'Sin imagen';

            // Verificar si existe un enlace con clase 'img' y atributo 'style' que contiene la imagen
            if ($articulo->filter('a.img, .ws-post-first, .ws-post-sec')->count() > 0) {
                $style = $articulo->filter('a.img, .ws-post-first, .ws-post-sec')->attr('style');
                // Usar una expresión regular para extraer la URL dentro de 'background-image:url(...)'
                preg_match('/background-image:url\((.*?)\)/', $style, $matches);

                // Si se encuentra una coincidencia, el segundo elemento del array contiene la URL de la imagen
                if (isset($matches[1])) {
                    $imagen = $matches[1];
                }
            }

            // Si no se encontró imagen en el 'style', intentar con los atributos 'data-img-url' o 'src'
            if ($imagen === 'Sin imagen' && $articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->count() > 0) {
                // Intentar extraer primero el atributo 'data-img-url'
                if ($articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('data-img-url')) {
                    $imagen = $articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('data-img-url');
                }
                // Si no existe 'data-img-url', intentar con 'src'
                elseif ($articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('src')) {
                    $imagen = $articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('src');
                }
            }

            $elementosFecha = $articulo->filter('.entry-date, .fmm-date span, span.ws-info span, div.post-date-bd span');

            if ($elementosFecha->count() > 0) {
                // Muestra los elementos encontrados
                // dd($elementos->html()); // Muestra el HTML de los elementos encontrados

                // Muestra el texto del primer elemento
                $fecha = $elementosFecha->first()->text();
            } elseif ($elementosFecha->count() > 0) {
                // Muestra un mensaje si no se encuentran elementos
                // dd('No se encontraron elementos con los selectores especificados.');
                $fecha = $elementosFecha->text();
            } else {
                $fecha = 'Sin fecha';
            }

            $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');

            if ($elementos->count() > 0) {
                // Muestra los elementos encontrados
                // dd($elementos->html()); // Muestra el HTML de los elementos encontrados

                // Muestra el texto del primer elemento
                $autor = $elementos->text();
            } elseif ($elementos->count() > 0) {
                // Muestra un mensaje si no se encuentran elementos
                // dd('No se encontraron elementos con los selectores especificados.');
                $autor = $elementos->first()->text();
            } else {
                $autor = 'Sin autor';
            }

            // Extraer el separador
            $separador = $articulo->filter('.td-post-author-name span')->count() > 0 ? $articulo->filter('.td-post-author-name span')->text() : 'Sin separador';

            // Extraer el avatar (imagen)
            $avatar = $articulo->filter('.td-author-photo img')->count() > 0 ? $articulo->filter('.td-author-photo img')->attr('src') : 'Sin avatar';

            // Extraer la URL del artículo
            $urlCompleta = $articulo->filter('.entry-title a')->count() > 0 ? $articulo->filter('.entry-title a')->attr('href') : 'Sin URL';

            $url = 'Sin URL';
            $href = $articulo->filter('.entry-title a, .td-image-wrap, .sp-pcp-thumb, a.extend-link')->attr('href');
            // Verificar si la URL del 'href' es relativa y completarla
            if (strpos($href, 'http') === false) {
                $baseUrl = $this->search; // Cambia esto por la URL base correcta si es necesario
                $url = $baseUrl . $href;
            } else {
                $url = $href;
            }

            // Separar URL principal y el resto del path
            if ($url !== 'Sin URL') {
                // Extraer la parte principal de la URL
                $urlPrincipal = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . '/';

                if ($urlPrincipal === 'https://losandes.com.pe/') {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');

                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
                        // dd('No se encontraron elementos con los selectores especificados.');
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario los Andes';
                    }
                } elseif ($urlPrincipal === 'https://diariosinfronteras.com.pe/') {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');
                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
                        // dd('No se encontraron elementos con los selectores especificados.');
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario Sin Fronteras';
                    }
                } else {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');
                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
                        // dd('No se encontraron elementos con los selectores especificados.');
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario la República';
                    }
                }

                // Extraer la parte restante (path)
                $path = str_replace($urlPrincipal, '', $url);
            }

            $datosArticulos[] = [
                'titulo' => $titulo,
                'extracto' => $extracto,
                'categoria' => $categoria,
                'imagen' => $imagen,
                'autor' => $autor,
                'separador' => $separador,
                'fecha' => $fecha,
                'avatar' => $avatar,
                'url' => $url,
                'urlPrincipal' => $urlPrincipal,
                'path' => $path,
            ];
        });

        return [
            'articulos' => $datosArticulos,
        ];
    }
}
