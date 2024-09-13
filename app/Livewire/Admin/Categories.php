<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

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

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')->latest('id')->paginate(10);
        return view('livewire.admin.categories', compact('categories'));
    }

    public function changeStatus($id){
        $category = Category::find($id);
        $category->update(['status' => !$category->status]);
        toast()->success('Registro actualizado correctamente', 'Mensaje de éxito')->push();
    }
    public function create()
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function edit(Category $category)
    {
        $this->resetForm();
        $this->isOpen = true;
        $this->itemId = $category->id;
        $this->category = $category;
        $this->form->fill($category);
    }

    public function store()
    {
        $this->validate();
        $categoryData = $this->form->toArray();
        if (!isset($this->category->id)) {
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

    public function showCategoryDetail(Category $category)
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
