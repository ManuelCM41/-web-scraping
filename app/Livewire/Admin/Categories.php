<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;
use Illuminate\Support\Str;

class Categories extends Component
{
    use WithPagination;
    use WireToast;

    public $search;
    public $isOpen = false,
        $showCategory = false,
        $isOpenDelete = false;
    public $itemId;
    public CategoryForm $form;
    public ?Category $category;

    protected $listeners = ['render', 'delete' => 'delete'];

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')->latest('id')->paginate(10);
        return view('livewire.admin.categories', compact('categories'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isOpen = true;
        $this->itemId = $id;
        $this->category = Category::findOrFail($id);
        $this->form->fill($this->category);
    }

    public function store()
    {
        $this->validate();
        $categoryData = $this->form->toArray();

        if (!isset($this->category->id)) {
            $slug = Str::slug($this->form->name);
            $categoryData['slug'] = $slug;
            $categoryData['url'] = 'Sin url';
            Category::create($categoryData);

            toast()->success('Categoría creado correctamente', 'Mensaje de éxito')->push();
        } else {
            $this->category->update($categoryData);
            toast()->success('Categoría actualizado correctamente', 'Mensaje de éxito')->push();
        }
        $this->closeModals();
    }

    public function deleteItem($id)
    {
        $this->itemId = $id;
        $this->isOpenDelete = true;
    }

    public function delete()
    {
        Category::find($this->itemId)->delete();
        toast()->success('Categoría eliminado correctamente', 'Mensaje de éxito')->push();
        $this->reset('isOpenDelete', 'itemId');
    }

    public function showCategoryDetail($category)
    {
        $this->showCategory = true;
        $this->edit($category);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function closeModals()
    {
        $this->isOpen = false;
        $this->showCategory = false;
        $this->isOpenDelete = false;
    }

    private function resetForm()
    {
        $this->form->reset();
        $this->reset(['category', 'itemId']);
        $this->resetValidation();
    }
}
