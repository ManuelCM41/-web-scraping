<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class ArticleForm extends Form
{
    public $id;
    public $titulo;
    public $url;
    public $urlPrincipal;
    public $path;
    public $extracto;
    public $categoria;
    public $imagen;
    public $autor;
    public $fecha;
    public $avatar;

    protected function rules()
    {
        return [
            'titulo' => 'required',
            'url' => 'required',
            'urlPrincipal' => 'required',
            'path' => 'required',
            'extracto' => 'required',
            'categoria' => 'required',
            'imagen' => 'required',
            'autor' => 'required',
            'fecha' => 'required',
            'avatar' => 'required',
        ];
    }

    public function resetFields()
    {
        $this->id = null;
        $this->titulo = null;
        $this->url = null;
        $this->urlPrincipal = null;
        $this->path = null;
        $this->extracto = null;
        $this->categoria = null;
        $this->imagen = null;
        $this->autor = null;
        $this->fecha = null;
        $this->avatar = null;
    }
}
