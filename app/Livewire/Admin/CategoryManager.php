<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public string $modal       = '';
    public ?int   $editId      = null;
    public string $name        = '';
    public string $description = '';
    public bool   $is_active   = true;

    protected function rules(): array
    {
        $unique = $this->editId ? 'required|string|max:255|unique:categories,name,' . $this->editId : 'required|string|max:255|unique:categories,name';
        return [
            'name'        => $unique,
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }

    public function openCreate(): void { $this->resetForm(); $this->modal = 'create'; }

    public function openEdit(int $id): void
    {
        $c = Category::findOrFail($id);
        $this->editId      = $c->id;
        $this->name        = $c->name;
        $this->description = $c->description ?? '';
        $this->is_active   = $c->is_active;
        $this->modal       = 'edit';
    }

    public function openDelete(int $id): void { $this->editId = $id; $this->modal = 'delete'; }

    public function save(): void
    {
        $this->validate();
        $data = ['name' => $this->name, 'slug' => Str::slug($this->name), 'description' => $this->description ?: null, 'is_active' => $this->is_active];

        if ($this->editId) {
            Category::findOrFail($this->editId)->update($data);
            session()->flash('success', 'Category updated.');
        } else {
            Category::create($data);
            session()->flash('success', 'Category created.');
        }
        $this->closeModal();
    }

    public function delete(): void
    {
        Category::findOrFail($this->editId)->delete();
        session()->flash('success', 'Category deleted.');
        $this->closeModal();
    }

    public function closeModal(): void { $this->modal = ''; $this->resetForm(); }

    private function resetForm(): void
    {
        $this->editId = null;
        $this->name = $this->description = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.category-manager', [
            'categories' => Category::withCount('products')->latest()->paginate(15),
        ]);
    }
}
