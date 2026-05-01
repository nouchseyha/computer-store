<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index');
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'brand'             => 'nullable|string|max:100',
            'sku'               => 'nullable|string|unique:products,sku',
            'image_file'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'name', 'category_id', 'price', 'sale_price',
            'stock', 'short_description', 'description', 'brand', 'sku',
        ]);
        $data['slug']        = Str::slug($request->name);
        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['sale_price']  = $request->filled('sale_price') ? $request->sale_price : null;

        if ($request->hasFile('image_file')) {
            $dir = public_path('img/products');
            File::ensureDirectoryExists($dir);
            $name = time() . '.' . $request->file('image_file')->getClientOriginalExtension();
            $request->file('image_file')->move($dir, $name);
            $data['image'] = 'img/products/' . $name;
        } else {
            $data['image'] = 'img/default-product.png';
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'brand'             => 'nullable|string|max:100',
            'sku'               => 'nullable|string|unique:products,sku,' . $product->id,
            'image_file'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'name', 'category_id', 'price', 'sale_price',
            'stock', 'short_description', 'description', 'brand', 'sku',
        ]);
        $data['slug']        = Str::slug($request->name);
        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['sale_price']  = $request->filled('sale_price') ? $request->sale_price : null;

        if ($request->hasFile('image_file')) {
            // Delete old image
            if ($product->image && $product->image !== 'img/default-product.png') {
                $old = public_path($product->image);
                if (File::exists($old)) File::delete($old);
            }
            $dir  = public_path('img/products');
            File::ensureDirectoryExists($dir);
            $name = time() . '.' . $request->file('image_file')->getClientOriginalExtension();
            $request->file('image_file')->move($dir, $name);
            $data['image'] = 'img/products/' . $name;
        }
        // No file = keep existing image (don't touch $data['image'])

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && $product->image !== 'img/default-product.png') {
            $path = public_path($product->image);
            if (File::exists($path)) File::delete($path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}
