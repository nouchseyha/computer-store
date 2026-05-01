<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'image_file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Delete old image
        if ($product->image && $product->image !== 'img/default-product.png') {
            $old = public_path($product->image);
            if (File::exists($old)) File::delete($old);
        }

        $dir  = public_path('img/products');
        File::ensureDirectoryExists($dir);
        $name = time() . '_' . $product->id . '.' . $request->file('image_file')->getClientOriginalExtension();
        $request->file('image_file')->move($dir, $name);

        $product->update(['image' => 'img/products/' . $name]);

        return back()->with('success', 'Product image updated.');
    }
}
