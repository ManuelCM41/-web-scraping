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
            'https://diariosinfronteras.com.pe/' => 'Sin Fronteras',
            'https://losandes.com.pe/' => 'Los Andes',
            'https://larepublica.pe/' => 'La República',
            'https://elcomercio.pe/' => 'El Comercio',
        ];

        $this->diariosCategoria = Category::all();

        if (Auth::check()) {
            $this->guardarArticulos($this->search);

            $categories = Category::where('urlPrincipal', $this->search)->pluck('name');

            if($this->search != 'https://elcomercio.pe/') {
                $this->guardarArticulosCategoria($this->search, $categories);
            }

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

    public function obtenerContenidoHTML($url)
    {
        $client = new Client();

        try {
            $options = [
                'connect_timeout' => 100,
                'timeout' => 100,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0',
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
            // dd('Error al obtener contenido HTML: ' . $e->getMessage());
            return false;
        }
    }

    public function extraerDatos($html, $link)
    {
        $crawler = new Crawler($html);

        if ($link === 'https://larepublica.pe/') {
            $articulos = $crawler->filter(
                'div.wrapper__content.mh-600 .MainSpotlight_primary__other__PEhAc,
            div.wrapper__content.mh-600 .MainSpotlight_secondarySpotlight__item__UWjdv,
            div.wrapper__content.mh-600 .MainSpotlight_lateral__item__PuIEF,
            div.wrapper__content.mh-600 .ItemSection_itemSection__D8r12'
            );
        } elseif ($link === 'https://losandes.com.pe/') {
            $articulos = $crawler->filter('div.tdc_zone.tdi_80 .sp-pcp-post-thumb-area, div.tdc_zone.tdi_80 .td-category-pos-above');
        } elseif ($link === 'https://diariosinfronteras.com.pe/') {
            $articulos = $crawler->filter('div#featured-main.featured-main .fm-story, div#main.main div.post');
        } elseif ($link === 'https://elcomercio.pe/') {
            $articulos = $crawler->filter('div.grid.grid--content.content-layout.grid--col-1.grid--col-2.grid--col-3.mt-20.mb-20 div.fs-wi.fs-container');
        }

        $categorias = $crawler->filter('.menu-item-object-category, .Header_container-header_menu-secciones-item__3sngP, .bd_menu_item');

        $datosArticulos = [];
        $datosCategorias = [];

        // Recorrer cada artículo y extraer el título, el extracto, la categoría y la imagen
        $articulos->each(function (Crawler $articulo) use (&$datosArticulos) {
            $titulo = $articulo->filter('.entry-title a, h3.entry-title.td-module-title, figcaption h1, .extend-link, h3.uk-card-title, h2.fs-wis__title-text, h2.fs-wi__title')->count() > 0 ? $articulo->filter('.entry-title a, h3.entry-title.td-module-title, figcaption h1, .extend-link, h3.uk-card-title, h2.fs-wis__title-text, h2.fs-wi__title')->text() : 'Sin título';
            $extracto = $articulo->filter('.td-excerpt')->count() > 0 ? $articulo->filter('.td-excerpt')->text() : 'Sin extracto';
            $categoria = $articulo->filter('.td-post-category')->count() > 0 ? $articulo->filter('.td-post-category')->text() : 'Sin categoria';

            // Primero intenta con 'data-img-url', luego con 'src'
            $imagen = 'Sin imagen';

            if ($this->search === 'https://elcomercio.pe/') {
                $imagen = $articulo->filter('.fs-wi__img-link img, div img, a img')->attr('data-src');
            } else {
                // Verificar si existe un enlace con clase 'img' y atributo 'style' que contiene la imagen
                if ($articulo->filter('a.img, .ws-post-first, .ws-post-sec, div img.uk-width-1-1')->count() > 0) {
                    $style = $articulo->filter('a.img, .ws-post-first, .ws-post-sec, div img.uk-width-1-1')->attr('style');
                    // Usar una expresión regular para extraer la URL dentro de 'background-image:url(...)'
                    preg_match('/background-image:url\((.*?)\)/', $style, $matches);

                    // Si se encuentra una coincidencia, el segundo elemento del array contiene la URL de la imagen
                    if (isset($matches[1])) {
                        $imagen = $matches[1];
                    }
                }

                // Si no se encontró imagen en el 'style', intentar con los atributos 'data-img-url' o 'src'
                if ($imagen === 'Sin imagen' && $articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img, .fs-wi__img-link img')->count() > 0) {
                        // Intentar extraer primero el atributo 'data-img-url'
                        if ($articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('data-img-url')) {
                            $imagen = $articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('data-img-url');
                        } // Si no existe 'data-img-url', intentar con 'src'
                        elseif ($articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('src')) {
                            $imagen = $articulo->filter('.entry-thumb, .sp-pcp-thumb img, a.img, div.ws-thumbnail img, figure.undefined img, div img')->attr('src');
                        }

                }
            }

            $elementosFecha = $articulo->filter('.entry-date, .fmm-date span, span.ws-info span, div.post-date-bd span');

            if ($elementosFecha->count() > 0) {
                // Muestra el texto del primer elemento
                $fecha = $elementosFecha->first()->text();
            } elseif ($elementosFecha->count() > 0) {
                // Muestra un mensaje si no se encuentran elementos
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
            $href = $articulo->filter('.entry-title a, .td-image-wrap, .sp-pcp-thumb, a.extend-link, a.uk-link-reset, .fs-wi__title a, .fs-wis__title a')->attr('href');

            // Condicional para verificar si el URL tiene un esquema (http/https) y un dominio
            if (filter_var($href, FILTER_VALIDATE_URL)) {
                // Si el URL es válido, lo dejamos tal cual está
                $hrefPrincipal = $href;
            } else {
                $this->search = rtrim($this->search, '/');

                // Luego concatenamos el href
                $hrefPrincipal = $this->search . $href;
            }

            // Separar URL principal y el resto del path
            if ($href !== 'Sin URL') {
                // Extraer la parte principal de la URL
                $scheme = parse_url($href, PHP_URL_SCHEME);
                $host = parse_url($href, PHP_URL_HOST);

                // Condicional para construir el URL principal o usar solo el path si falta el dominio
                if ($scheme && $host) {
                    // Si ambos están presentes, construimos la URL principal
                    $urlPrincipal = $scheme . '://' . $host . '/';
                } else {
                    // Si faltan, asumimos que es una ruta relativa y no asignamos un dominio
                    $urlPrincipal = $this->search;
                }

                // Extraer el path de la URL
                $pathPri = parse_url($href, PHP_URL_PATH);
                // Si el path es válido, quitar las barras iniciales
                if ($pathPri !== null) {
                    $pathSinBarras = preg_replace('#^/+#', '', $pathPri);
                } else {
                    $pathSinBarras = "";
                }

                if ($urlPrincipal === 'https://losandes.com.pe/') {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');

                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
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
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario Sin Fronteras';
                    }
                } elseif ($urlPrincipal === 'https://www.expreso.com.pe/') {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');
                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario Expreso';
                    }
                } elseif ($this->search === 'https://elcomercio.pe') {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');
                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario El Comercio';
                    }
                } else {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');
                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario la República';
                    }
                }
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
                'url' => $hrefPrincipal,
                'urlPrincipal' => $urlPrincipal,
                'path' => $pathSinBarras,
            ];
        });

        $categorias->each(function (Crawler $categoria) use (&$datosCategorias) {
            $titulo = $categoria->filter('div.tdb-menu-item-text, .Header_container-header_menu-secciones-link__gOmTh, span.menu-label')->count() > 0 ? $categoria->filter('div.tdb-menu-item-text, .Header_container-header_menu-secciones-link__gOmTh, span.menu-label')->text() : 'Sin título';

            $slug = Str::slug($titulo);

            $url = 'Sin URL';
            $href = $categoria->filter('a')->attr('href');

            // Condicional para verificar si el URL tiene un esquema (http/https) y un dominio
            if (filter_var($href, FILTER_VALIDATE_URL)) {
                // Si el URL es válido, lo dejamos tal cual está
                $url = $href;
            } else {
                // Luego concatenamos el href
                $url = $this->search . $href;
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

    public function guardarArticulos($datosArticulos)
    {
        if ($datosArticulos) {
            $url = $datosArticulos;

            // Obtener el contenido HTML de la página
            $html = $this->obtenerContenidoHTML($url);

            if ($html !== false) {
                $user = Auth::user();

                // Obtiene el límite de cantidad_veces desde la membresía del usuario
                $cantidadLimite = $user->membership->cantidad_veces;
                $cantidadLimiteUrl = $user->membership->cantidad_urls;

                // Busca o crea el registro en la tabla Scraping
                $scraping = Scraping::firstOrNew([
                    'url' => $url,
                    'user_id' => $user->id,
                ]);

                // Si es una nueva URL y el total de URLs del usuario está dentro del límite
                if (!$scraping->exists && Scraping::where('user_id', $user->id)->count() >= $cantidadLimiteUrl) {
                    $this->search = '';
                    toast()->danger('Has alcanzado el límite de URLs únicas para tu plan.', 'Mensaje de Error')->push();
                    return;
                }

                // Incrementa la cantidad para la URL actual solo si no se ha alcanzado el límite de cantidad_veces
                if ($scraping->cantidad < $cantidadLimite) {
                    $scraping->cantidad += 1;
                    $scraping->save();
                } else {
                    $this->search = '';
                    toast()->danger('Has alcanzado el límite de uso para esta URL en tu plan.', 'Mensaje de Error')->push();
                    return;
                }

                // Extraer los datos
                $datosExtraidos = $this->extraerDatos($html, $this->search);
                $categorias = $datosExtraidos['categorias']; // Acceder al array de categorías

                foreach ($categorias as $categoria) {
                    if ($categoria['titulo'] != 'Sin título') {
                        if ($categoria['titulo'] == 'INICIO' || $categoria['titulo'] == 'CINE Y SERIES' || $categoria['titulo'] == 'SUSCRÍBETE' || $categoria['titulo'] == 'PERÚ' || $categoria['titulo'] == 'NEWSLETTERS' || $categoria['titulo'] == 'ÚLTIMAS NOTICIAS') {
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

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://diariosinfronteras.com.pe/category/espectaculos/',
                                ],
                                [
                                    'urlPrincipal' => 'https://diariosinfronteras.com.pe/',
                                    'name' => 'ESPÉCTACULOS',
                                    'slug' => 'espectaculos',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://diariosinfronteras.com.pe/category/diariosf/',
                                ],
                                [
                                    'urlPrincipal' => 'https://diariosinfronteras.com.pe/',
                                    'name' => 'DIARIOSF',
                                    'slug' => 'diariosf',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://diariosinfronteras.com.pe/category/escenario/',
                                ],
                                [
                                    'urlPrincipal' => 'https://diariosinfronteras.com.pe/',
                                    'name' => 'ESCENARIO',
                                    'slug' => 'escenario',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://larepublica.pe/carlincatura',
                                ],
                                [
                                    'urlPrincipal' => 'https://larepublica.pe/',
                                    'name' => 'CARLINCATURA',
                                    'slug' => 'carlincatura',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://larepublica.pe/publirreportajes',
                                ],
                                [
                                    'urlPrincipal' => 'https://larepublica.pe/',
                                    'name' => 'PUBLIREPORTAJES',
                                    'slug' => 'publirreportajes',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://larepublica.pe/entretenimiento',
                                ],
                                [
                                    'urlPrincipal' => 'https://larepublica.pe/',
                                    'name' => 'ENTRETENIMIENTO',
                                    'slug' => 'entretenimiento',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://larepublica.pe/ciencia',
                                ],
                                [
                                    'urlPrincipal' => 'https://larepublica.pe/',
                                    'name' => 'CIENCIA',
                                    'slug' => 'ciencia',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://larepublica.pe/espectaculos',
                                ],
                                [
                                    'urlPrincipal' => 'https://larepublica.pe/',
                                    'name' => 'ESPECTÁCULOS',
                                    'slug' => 'espectaculos',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://larepublica.pe/tecnologia',
                                ],
                                [
                                    'urlPrincipal' => 'https://larepublica.pe/',
                                    'name' => 'TECNOLOGÍAS',
                                    'slug' => 'tecnologia',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://larepublica.pe/tendencias',
                                ],
                                [
                                    'urlPrincipal' => 'https://larepublica.pe/',
                                    'name' => 'TENDENCIAS',
                                    'slug' => 'tendencias',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://larepublica.pe/cine-series',
                                ],
                                [
                                    'urlPrincipal' => 'https://larepublica.pe/',
                                    'name' => 'CINE Y SERIES',
                                    'slug' => 'cine-series',
                                ],
                            );

                            Category::updateOrCreate(
                                [
                                    'url' => 'https://larepublica.pe/gastronomia',
                                ],
                                [
                                    'urlPrincipal' => 'https://larepublica.pe/',
                                    'name' => 'GASTRONOMÍA',
                                    'slug' => 'gastronomia',
                                ],
                            );
                        }
                    }

                    if ($this->search === 'https://losandes.com.pe/') {
                        // Agregar categorías manualmente
                        Category::updateOrCreate(
                            [
                                'url' => 'https://losandes.com.pe/diario-virtual/',
                            ],
                            [
                                'urlPrincipal' => 'https://losandes.com.pe/',
                                'name' => 'DIARIO VIRTUAL',
                                'slug' => 'diario-virtual',
                            ],
                        );
                    } elseif ($this->search === 'https://larepublica.pe/') {
                        // Agregar categorías manualmente
                        Category::updateOrCreate(
                            [
                                'url' => 'https://larepublica.pe/carlincatura',
                            ],
                            [
                                'urlPrincipal' => 'https://larepublica.pe/',
                                'name' => 'CARLINCATURA',
                                'slug' => 'carlincatura',
                            ],
                        );
                    }
                }

                $articulos = $datosExtraidos['articulos']; // Acceder al array de artículos
                // dd($articulos);

                foreach ($articulos as $articulo) {
                    // Extraer solo la primera parte del path antes del primer '/'
                    if ($this->search === 'https://losandes.com.pe/') {
                        if ($articulo['categoria'] === 'Sin categoria') {
                            return; // Salta al siguiente artículo
                        }
                        $categoriaFinal = $articulo['categoria'];
                    } elseif ($this->search === 'https://larepublica.pe') {
                        $pathParts = explode('/', trim($articulo['path'], '/')); // Elimina los '/' extra al principio y al final
                        $categoriaPath = $pathParts[0];
                        if ($categoriaPath === 'nota-de-prensa' || $categoriaPath === 'estados-unidos' || $categoriaPath === 'domingo') {
                            continue; // Salta al siguiente artículo
                        }
                        $categoriaSlug = Category::where('slug', $categoriaPath)->first();
                        $categoriaFinal = $categoriaSlug['name'];
                    } elseif ($this->search === 'https://diariosinfronteras.com.pe/') {
                        // Verificar si la imagen es null o vacía
                        if (empty($articulo['imagen']) || $articulo['imagen'] === 'Sin imagen' || $articulo['categoria'] === 'Sin categoria') {
                            continue; // Salta al siguiente artículo si no hay imagen
                        }
                        $categoriaFinal = $articulo['categoria'];
                    } elseif ($this->search === 'https://elcomercio.pe') {
                        $pathParts = explode('/', trim($articulo['path'], '/')); // Elimina los '/' extra al principio y al final
                        $categoriaPath = $pathParts[0];

                        Category::updateOrCreate(
                            [
                                'url' => $this->search . '/' . $categoriaPath,
                            ],
                            [
                                'urlPrincipal' => $this->search . '/',
                                'name' => strtoupper($categoriaPath),
                                'slug' => $categoriaPath,
                            ],
                        );

                        $categoriaFinal = strtoupper(str_replace('-', ' ', $categoriaPath));
                    }

                    // Si la categoría es válida, registra el artículo
                    ModelsArticle::updateOrCreate(
                        [
                            'url' => $articulo['url'], // Usa la URL corregida
                        ],
                        [
                            'urlPrincipal' => $datosArticulos,
                            'path' => $articulo['path'],
                            'titulo' => $articulo['titulo'],
                            'imagen' => $articulo['imagen'] !== 'Sin imagen' ? $articulo['imagen'] : null,
                            'categoria' => $categoriaFinal,
                            'autor' => $articulo['autor'],
                            'fecha' => $articulo['fecha'],
                            'avatar' => $articulo['avatar'],
                            'extracto' => $articulo['extracto'] !== 'Sin extracto' ? $articulo['extracto'] : null,
                        ]
                    );
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
            foreach ($categorias as $categoria) {
                $diarioCategoria = Category::where('name', $categoria)->first();

                if (!$diarioCategoria) {
                    continue;
                }

                if ($diarios === 'https://losandes.com.pe/') {
                    $url = $diarios . 'category/' . $diarioCategoria->slug;
                } elseif ($diarios === 'https://diariosinfronteras.com.pe/') {
                    $url = $diarios . 'category/' . $diarioCategoria->slug;
                } else {
                    // Obtén el slug de la categoría y reemplaza 'y' con un guion
                    $slug = str_replace('-y-', '-', $diarioCategoria->slug);

                    // Combina el URL base con el slug modificado
                    $url = $diarioCategoria->url;
                }

                $html = $this->obtenerContenidoHTML($url);

                if ($html !== false) {
                    $datosExtraidos = $this->extraerDatosCategoria($html);
                    $articulos = $datosExtraidos['articulos']; // Acceder al array de artículos

                    // dd($articulos);
                    foreach ($articulos as $articulo) {
                        if ($articulo['categoria'] === 'Sin categoria') {
                            continue;
                        }

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
            }
            toast()->success('Se escrapeó correctamente.', 'Mensaje de Éxito')->push();
            $this->search = '';
        } else {
            return;
        }
    }

    public function extraerDatosCategoria($html)
    {
        $crawler = new Crawler($html);

        // $articulos = $crawler->filter('.tdi_88, .tdb_module_loop, .td-category-pos-above, .sp-pcp-post-thumb-area, .extend-link--outside, .ListSection_list__section--item__zeP_z, .bd-fm-post-0, fm-post-sec, .ws-post-first, .ws-post-sec, .MainSpotlight_primary__other__PEhAc, .MainSpotlight_secondarySpotlight__item__UWjdv, .MainSpotlight_lateral__item__PuIEF, .ItemSection_itemSection__D8r12, .top-nav li');

        if ($this->search === 'https://larepublica.pe/') {
            $articulos = $crawler->filter(
                'div.wrapper__content.mh-600 .MainSpotlight_primary__other__PEhAc,
                        div.wrapper__content.mh-600 .MainSpotlight_secondarySpotlight__item__UWjdv,
                        div.wrapper__content.mh-600 .MainSpotlight_lateral__item__PuIEF,
                        div.wrapper__content.mh-600 .ItemSection_itemSection__D8r12'
            );
        } elseif ($this->search === 'https://losandes.com.pe/') {
            $articulos = $crawler->filter('div.tdc_zone.tdi_80.wpb_row.td-pb-row div.td-module-container');
        } elseif ($this->search === 'https://diariosinfronteras.com.pe/') {
            $articulos = $crawler->filter('div.layout-inner div.layout-wrap');
        } elseif ($this->search === 'https://www.expreso.com.pe/') {
            $articulos = $crawler->filter('div.uk-home-body div.uk-inline, div.uk-home-body div.uk-card.uk-card-simple, div.uk-home-body div.uk-cover-container');
        }

        $datosArticulos = [];

        // Recorrer cada artículo y extraer el título, el extracto, la categoría y la imagen
        $articulos->each(function (Crawler $articulo) use (&$datosArticulos) {
            //$titulo = $articulo->filter('h3.entry-title.td-module-title a, figcaption h1, .extend-link, a')->count() > 0 ? $articulo->filter('h3.entry-title.td-module-title a, figcaption h1, .extend-link, a')->text() : 'Sin título';
            $extracto = $articulo->filter('.td-excerpt')->count() > 0 ? $articulo->filter('.td-excerpt')->text() : 'Sin extracto';
            $categoria = $articulo->filter('.td-post-category, div.post-cats a')->count() > 0 ? $articulo->filter('.td-post-category, div.post-cats a')->text() : 'Sin categoria';

            $titulo = $articulo->filter('h3.entry-title.td-module-title a, h3.entry-title a')->count() > 0
                ? $articulo->filter('h3.entry-title.td-module-title a, h3.entry-title a')->text()
                : 'Sin título';

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
                $fecha = $elementosFecha->first()->text();
            } elseif ($elementosFecha->count() > 0) {
                $fecha = $elementosFecha->text();
            } else {
                $fecha = 'Sin fecha';
            }

            $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');

            if ($elementos->count() > 0) {
                $autor = $elementos->text();
            } elseif ($elementos->count() > 0) {
                $autor = $elementos->first()->text();
            } else {
                $autor = 'Sin autor';
            }

            // Extraer el separador
            $separador = $articulo->filter('.td-post-author-name span')->count() > 0 ? $articulo->filter('.td-post-author-name span')->text() : 'Sin separador';

            // Extraer el avatar (imagen)
            $avatar = $articulo->filter('.td-author-photo img')->count() > 0 ? $articulo->filter('.td-author-photo img')->attr('src') : 'Sin avatar';

            $url = 'Sin URL';

            $href = $articulo->filter('.entry-title a, .td-image-wrap, .sp-pcp-thumb, a.extend-link, a')->attr('href');

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
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario Sin Fronteras';
                    }
                } elseif ($urlPrincipal === 'https://www.expreso.com.pe/') {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');
                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
                        $autor = $elementos->first()->text();
                    } else {
                        $autor = 'Diario Expreso';
                    }
                } else {
                    $elementos = $articulo->filter('.td-post-author-name a, .fmm-author a, .ws-info a, .post-author-bd a');
                    if ($elementos->count() > 0) {
                        $autor = $elementos->text();
                    } elseif ($elementos->count() > 0) {
                        // Muestra un mensaje si no se encuentran elementos
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
