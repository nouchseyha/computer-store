<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }
        if ($request->filled('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        match ($request->get('sort', 'latest')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name'       => $query->orderBy('name'),
            default      => $query->latest(),
        };

        return ProductResource::collection($query->paginate($request->get('per_page', 15)));
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'brand'       => 'nullable|string|max:100',
            'sku'         => 'nullable|string|unique:products,sku',
            'image_file'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'category_id', 'price', 'sale_price', 'stock', 'brand', 'sku', 'short_description', 'description']);
        $data['slug']        = Str::slug($request->name);
        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);

        if ($request->hasFile('image_file')) {
            $path = public_path('img/products');
            File::ensureDirectoryExists($path);
            $name = time() . '.' . $request->file('image_file')->getClientOriginalExtension();
            $request->file('image_file')->move($path, $name);
            $data['image'] = 'img/products/' . $name;
        }

        $product = Product::create($data);
        return new ProductResource($product->load('category'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'price'       => 'sometimes|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'stock'       => 'sometimes|integer|min:0',
            'sku'         => 'nullable|string|unique:products,sku,' . $product->id,
            'image_file'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name', 'category_id', 'price', 'sale_price', 'stock', 'brand', 'sku', 'short_description', 'description']);
        if ($request->filled('name')) $data['slug'] = Str::slug($request->name);
        if ($request->has('is_active'))   $data['is_active']   = $request->boolean('is_active');
        if ($request->has('is_featured')) $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image_file')) {
            if ($product->image && $product->image !== 'img/default-product.png') {
                $old = public_path($product->image);
                if (File::exists($old)) File::delete($old);
            }
            $name = time() . '.' . $request->file('image_file')->getClientOriginalExtension();
            $request->file('image_file')->move(public_path('img/products'), $name);
            $data['image'] = 'img/products/' . $name;
        }

        $product->update($data);
        return new ProductResource($product->load('category'));
    }

    public function destroy(Product $product)
    {
        if ($product->image && $product->image !== 'img/default-product.png') {
            $path = public_path($product->image);
            if (File::exists($path)) File::delete($path);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted.']);
    }
}
