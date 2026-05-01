<?php

namespace App\Livewire\Shop;

use App\Helpers\Cart;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    #[Url(as: 'search')]
    public string $search = '';

    #[Url(as: 'category')]
    public string $category = '';

    #[Url(as: 'brand')]
    public string $brand = '';

    #[Url(as: 'sort')]
    public string $sort = 'latest';

    public function updatedSearch(): void  { $this->resetPage(); }
    public function updatedCategory(): void { $this->resetPage(); }
    public function updatedBrand(): void   { $this->resetPage(); }
    public function updatedSort(): void    { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->category = '';
        $this->brand = '';
        $this->sort = 'latest';
        $this->resetPage();
    }

    public function addToCart(int $productId): void
    {
        Cart::add($productId, 1);
        $this->dispatch('cart-updated');
        session()->flash('cart_success', 'Product added to cart!');
    }

    public function render()
    {
        $query = Product::with('category')->where('is_active', true);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        if ($this->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $this->category));
        }
        if ($this->brand) {
            $query->where('brand', $this->brand);
        }

        match ($this->sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name'       => $query->orderBy('name'),
            default      => $query->latest(),
        };

        return view('livewire.shop.product-list', [
            'products'   => $query->paginate(12),
            'categories' => Category::where('is_active', true)->get(),
            'brands'     => Product::where('is_active', true)->whereNotNull('brand')->distinct()->pluck('brand'),
        ]);
    }
}
