<?php

namespace App\Livewire\Cart;

use App\Helpers\Cart;
use Livewire\Component;

class CartPage extends Component
{
    public function updateQty(int $productId, int $quantity): void
    {
        Cart::update($productId, $quantity);
        $this->dispatch('cart-updated');
    }

    public function remove(int $productId): void
    {
        Cart::remove($productId);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.cart.cart-page', [
            'cart'  => Cart::get(),
            'total' => Cart::total(),
        ]);
    }
}
