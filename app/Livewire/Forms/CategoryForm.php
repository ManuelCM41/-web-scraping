<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class CategoryForm extends Form
{
    public $id;
    public $name;
    public $slug;
    public $url;
    public $urlPrincipal;

    protected function rules()
    {
        return [
            'name' => 'required',
            'slug' => 'nullable',
            'url' => 'nullable',
            'urlPrincipal' => 'nullable',
        ];
    }

    public function resetFields()
    {
        $this->id = null;
        $this->name = null;
        $this->slug = null;
        $this->url = null;
        $this->urlPrincipal = null;
    }
}
