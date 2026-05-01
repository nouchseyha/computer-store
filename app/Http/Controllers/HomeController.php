<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(8)->get();

        $latestProducts = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)->get();

        $categories = Category::where('is_active', true)->take(6)->get();

        return view('home', compact('featuredProducts', 'latestProducts', 'categories'));
    }
}
