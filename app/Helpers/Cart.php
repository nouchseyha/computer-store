<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class Cart
{
    public static function get(): array
    {
        return Session::get('cart', []);
    }

    public static function add(int $productId, int $quantity = 1): void
    {
        $cart = self::get();
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $product = Product::findOrFail($productId);
            $cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->sale_price ?? $product->price,
                'image'    => $product->image,
                'quantity' => $quantity,
            ];
        }
        Session::put('cart', $cart);
    }

    public static function update(int $productId, int $quantity): void
    {
        $cart = self::get();
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }
        Session::put('cart', $cart);
    }

    public static function remove(int $productId): void
    {
        $cart = self::get();
        unset($cart[$productId]);
        Session::put('cart', $cart);
    }

    public static function clear(): void
    {
        Session::forget('cart');
    }

    public static function count(): int
    {
        return array_sum(array_column(self::get(), 'quantity'));
    }

    public static function total(): float
    {
        $total = 0;
        foreach (self::get() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
