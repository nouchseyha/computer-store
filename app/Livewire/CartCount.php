<?php

namespace App\Livewire;

use App\Helpers\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCount extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->count = Cart::count();
    }

    #[On('cart-updated')]
    public function refresh(): void
    {
        $this->count = Cart::count();
    }

    public function render()
    {
        return view('livewire.cart-count');
    }
}
