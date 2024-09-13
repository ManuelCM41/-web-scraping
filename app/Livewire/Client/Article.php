<?php

namespace App\Livewire\Client;

use App\Models\Article as ModelsArticle;
use App\Models\Category;
use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;

class Article extends Component
{
    use WithPagination;

    public $search;
    public $category, $categoriaSeleccionada;

    protected $listeners = ['render', 'delete'];

    public function render()
    {
        if ($this->search != null) {
            $this->guardarArticulos($this->search);
        }

        $articulos = ModelsArticle::where('url', 'like', '%' . $this->search . '%')->paginate(16);

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
        // $articulos = $crawler->filter('.td-module-container');
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
            // dd($imagen);

            // $fecha = $articulo->filter('.entry-date')->count() > 0 ? $articulo->filter('.entry-date')->text() : 'Sin fecha';
            // $fecha = 'Sin fecha';
            // if ($articulo->filter('.entry-date, .fmm-date span')->count() > 0) {
            //     $fecha = $articulo->filter('.entry-date, .fmm-date span')->text() ?? $articulo->filter('.entry-date, .fmm-date span')->text();
            // }

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

            // Extraer el nombre del autor
            // $autor = $articulo->filter('.td-post-author-name a')->count() > 0 ? $articulo->filter('.td-post-author-name a')->text() : 'Sin autor';
            // $autor = 'Sin autor';
            // if ($articulo->filter('.td-post-author-name a, .fmm-author a')->count() > 0) {
            //     $autor = $articulo->filter('.td-post-author-name a, .fmm-author a')->text() ?? $articulo->filter('.td-post-author-name a, .fmm-author a')->text();
            // }

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

            // Extraer la parte específica de la URL
            // $url = $urlCompleta !== 'Sin URL' ? basename(parse_url($urlCompleta, PHP_URL_PATH)) : 'Sin URL';
            // $url = $articulo->filter('.entry-title a, .td-image-wrap, .sp-pcp-thumb, a.extend-link')->count() > 0 ? $articulo->filter('.entry-title a, .td-image-wrap, .sp-pcp-thumb, a.extend-link')->attr('href') : 'Sin URL';
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

            // Obtener el contenido HTML de la página
            $html = $this->obtenerContenidoHTML($url);

            if ($html !== false) {
                // Extraer los datos
                // $articulos = $this->extraerDatos($html);
                $datosExtraidos = $this->extraerDatos($html);
                $articulos = $datosExtraidos['articulos']; // Acceder al array de artículos
                $categorias = $datosExtraidos['categorias']; // Acceder al array de categorías
                // dd($datosExtraidos);
                foreach ($articulos as $articulo) {
                    ModelsArticle::updateOrCreate(
                        [
                            'url' => $articulo['url'],
                        ],
                        [
                            'urlPrincipal' => $articulo['urlPrincipal'],
                            'path' => $articulo['path'],
                            'titulo' => $articulo['titulo'], // Condición para buscar el artículo existente
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

    public function descargarCSV($categoria)
    {
        // Llama a las funciones existentes para obtener el contenido HTML y extraer los datos
        $url = 'https://losandes.com.pe/category/' . $categoria;
        $html = $this->obtenerContenidoHTML($url);

        if ($html === false) {
            return response()->json(['error' => 'No se pudieron cargar los datos.'], 500);
        }

        $articulos = $this->extraerDatos($html);

        // Filtrar los artículos según la categoría seleccionada
        $articulosFiltrados = array_filter($articulos, function ($articulo) use ($categoria) {
            return strtolower($this->reemplazarGuionPorEspacio($articulo['categoria'])) === strtolower($categoria);
        });

        // Crear el archivo CSV
        $response = new StreamedResponse(function () use ($articulosFiltrados) {
            $handle = fopen('php://output', 'w');

            // Enviar encabezado UTF-8 BOM
            fwrite($handle, "\xEF\xBB\xBF");

            // Encabezados del CSV
            fputcsv($handle, ['Título', 'Extracto', 'Categoría', 'Imagen', 'Autor', 'Fecha']);

            foreach ($articulosFiltrados as $articulo) {
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
        $response->headers->set('Content-Disposition', 'attachment; filename="articulos_' . $categoria . '.csv"');

        return $response;
    }
}
