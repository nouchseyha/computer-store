<?php

namespace App\Http\Controllers;

use App\Helpers\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'integer|min:1']);
        Cart::add($request->product_id, $request->quantity ?? 1);
        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate(['product_id' => 'required', 'quantity' => 'required|integer|min:0']);
        Cart::update($request->product_id, $request->quantity);
        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request)
    {
        Cart::remove($request->product_id);
        return back()->with('success', 'Item removed from cart.');
    }
}
