<?php

namespace App\Livewire\Client;

use App\Models\Article;
use Livewire\Component;
use App\Models\ArticleDetail as ModelsArticleDetail;
use App\Models\Category;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;

class ArticleDetail extends Component
{
    public $titulo;

    public function mount($titulo)
    {
        $this->titulo = $titulo;
    }

    // Función para reemplazar tildes y caracteres especiales
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

    public function render()
    {
        // dd($this->titulo.''."/");
        $tituloId = Article::where('path', $this->titulo . '' . '/')->first();

        // dd($tituloId);

        $this->guardarArticulos($tituloId->url);

        $titulo = Article::where('url', $tituloId->url)->first();

        $detail = ModelsArticleDetail::where('titulo', $titulo->titulo)->first();

        return view('livewire.client.article-detail', compact('detail'));
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
        $articulos = $crawler->filter('.tdc-zone');

        $datosArticulos = [];

        // Recorrer cada artículo y extraer el título, el extracto, la categoría y la imagen
        $articulos->each(function (Crawler $articulo) use (&$datosArticulos) {
            // $titulo = $articulo->filter('.tdb-title-text')->count() > 0 ? $articulo->filter('.tdb-title-text')->text() : 'Sin título';
            // $titulo = $articulo->filter('h1.tdb-title-text')->count() > 0 ? $articulo->filter('h1.tdb-title-text')->text() : 'Sin título';
            $titulo = $articulo->filter('.tdb-title-text')->count() > 0 ? $articulo->filter('.tdb-title-text')->text() : 'Sin título';
            // $extracto = $articulo->filter('.td-excerpt')->count() > 0 ? $articulo->filter('.td-excerpt')->text() : 'Sin extracto';
            $categoria = $articulo->filter('.tdb-entry-category')->count() > 0 ? $articulo->filter('.tdb-entry-category')->text() : 'Sin categoria';
            // $imagen = $articulo->filter('.entry-thumb')->count() > 0 ? $articulo->filter('.entry-thumb')->attr('data-img-url') : 'Sin imagen';
            $fecha = $articulo->filter('.entry-date')->count() > 0 ? $articulo->filter('.entry-date')->text() : 'Sin fecha';

            // Extraer el nombre del autor
            $autor = $articulo->filter('.tdb-author-name a')->count() > 0 ? $articulo->filter('.tdb-author-name a')->text() : 'Sin autor';

            // Extraer el avatar (imagen)
            $avatar = $articulo->filter('.tdb-author-photo img')->count() > 0 ? $articulo->filter('.tdb-author-photo img')->attr('src') : 'Sin avatar';

            // Extraer el contenido separado por párrafos
            $contenido = [];
            $articulo->filter('.tdi_107 p')->each(function (Crawler $parrafo) use (&$contenido) {
                $contenido[] = $parrafo->text();
            });

            $imagen = 'Sin imagen';

            // Verificar si el div con la clase `.tdb-featured-image-bg` existe
            $div = $articulo->filter('.tdb-featured-image-bg');
            if ($div->count() > 0) {
                // Obtener el contenido de todos los bloques <style>
                $styles = $articulo->filter('style')->each(function ($style) {
                    return $style->text();
                });

                // Combinar todos los bloques <style> en una sola cadena
                $styleContent = implode(' ', $styles);

                // Buscar y extraer la URL de la imagen usando una expresión regular en el contenido de los estilos
                if (preg_match('/\.tdi_85\s*\.tdb-featured-image-bg\s*\{[^}]*background:\s*url\(["\'](.*?)["\']\)/', $styleContent, $matches)) {
                    $imagen = $matches[1]; // La URL está en la primera captura
                }
                // dd($imagen);
            }

            $datosArticulos[] = [
                'titulo' => $titulo,
                'imagen' => $imagen,
                'categoria' => $categoria,
                'autor' => $autor,
                'fecha' => $fecha,
                'avatar' => $avatar,
                'contenido' => $contenido, // Aquí se almacena el contenido por párrafos
            ];
        });

        return $datosArticulos;
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
                // Extraer los datos
                $articulos = $this->extraerDatos($html);

                // Filtrar los artículos que tienen información más completa
                $articulosCompletos = array_filter($articulos, function ($articulo) {
                    return $articulo['titulo'] != 'Sin título' && $articulo['imagen'] != 'Sin imagen' && $articulo['categoria'] != 'Sin categoria' || $articulo['fecha'] != 'Sin fecha' && $articulo['contenido'] != [];
                });

                // Si existen artículos completos, tomar el primero o el más completo
                $articuloCompleto = reset($articulosCompletos);

                // dd($articuloCompleto); // Ver el artículo más completo

                if ($articuloCompleto) {
                    // Procesar el artículo completo
                    $parrafos = $articuloCompleto['contenido'];
                    $imagenArticle = Article::where('url', $datosArticulos)->first();

                    // dd($articuloCompleto);

                    if ($articuloCompleto['categoria'] != 'Sin categoria') {
                        $imagenArticle->titulo = $articuloCompleto['titulo'];
                        $imagenArticle->categoria = $articuloCompleto['categoria'];
                        $imagenArticle->fecha = $articuloCompleto['fecha'];
                        $imagenArticle->save();

                        // dd($imagenArticle);
                        // Definir los campos de la base de datos para los párrafos
                        $parrafoCampos = [];
                        for ($i = 0; $i < count($parrafos); $i++) {
                            $campo = 'p' . ($i + 1); // Genera los campos p1, p2, p3, etc.
                            $parrafoCampos[$campo] = $parrafos[$i]; // Asigna el contenido del párrafo a cada campo
                        }

                        // Guardar o actualizar el artículo en la base de datos
                        ModelsArticleDetail::updateOrCreate(
                            [
                                'titulo' => $articuloCompleto['titulo'], // Condición para buscar el artículo existente
                            ],
                            array_merge(
                                [
                                    'categoria' => $articuloCompleto['categoria'],
                                    'autor' => $articuloCompleto['autor'],
                                    'fecha' => $articuloCompleto['fecha'],
                                    'imagen' => $articuloCompleto['imagen'] !== 'Sin imagen' ? $articuloCompleto['imagen'] : $imagenArticle['imagen'],
                                ],
                                $parrafoCampos, // Agregar los párrafos como parte de los campos de actualización
                            ),
                        );

                        if ($imagenArticle->urlPrincipal === 'https://losandes.com.pe/') {
                            $category = 'category/';
                        } elseif ($imagenArticle->urlPrincipal === 'https://diariosinfronteras.com.pe/') {
                            $category = '';
                        }
                        
                        $categoria = Str::slug($articuloCompleto['categoria']);

                        Category::updateOrCreate(
                            [
                                'name' => $articuloCompleto['categoria'],
                            ],
                            [
                                'urlPrincipal' => $imagenArticle->urlPrincipal,
                                'url' => $imagenArticle->urlPrincipal.''.$category.''.$categoria,
                                'slug' => $categoria,
                            ],
                        );
                    }
                }
            } else {
                echo '<p>No se pudo obtener el contenido de la categoría.</p>';
            }
        } else {
            echo '<p>Debe seleccionar una categoría.</p>';
        }
    }
}
