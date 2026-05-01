<?php

namespace App\Livewire\Shop;

use App\Helpers\Cart;
use App\Models\Product;
use Livewire\Component;

class ProductShow extends Component
{
    public Product $product;
    public int $quantity = 1;

    public function mount(Product $product): void
    {
        $this->product = $product->load('category');
    }

    public function addToCart(): void
    {
        $qty = max(1, min($this->quantity, $this->product->stock));
        Cart::add($this->product->id, $qty);
        $this->dispatch('cart-updated');
        session()->flash('cart_success', 'Added to cart!');
    }

    public function render()
    {
        $related = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->where('is_active', true)
            ->take(4)->get();

        return view('livewire.shop.product-show', compact('related'));
    }
}
