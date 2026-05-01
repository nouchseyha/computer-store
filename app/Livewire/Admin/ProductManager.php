<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $modal       = '';
    public ?int   $deleteId    = null;

    public function updatedSearch(): void { $this->resetPage(); }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->modal    = 'delete';
    }

    public function delete(): void
    {
        $product = Product::findOrFail($this->deleteId);
        if ($product->image && $product->image !== 'img/default-product.png') {
            $path = public_path($product->image);
            if (File::exists($path)) File::delete($path);
        }
        $product->delete();
        $this->modal    = '';
        $this->deleteId = null;
        session()->flash('success', 'Product deleted.');
    }

    public function closeModal(): void
    {
        $this->modal    = '';
        $this->deleteId = null;
    }

    public function render()
    {
        return view('livewire.admin.product-manager', [
            'products'   => Product::with('category')
                ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                ->latest()
                ->paginate(15),
        ]);
    }
}
