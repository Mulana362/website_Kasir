<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|integer|min:0',
            'category'          => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'              => $request->name,
            'slug'              => Str::slug($request->name),
            'price'             => $request->price,
            'category'          => $request->category,
            'short_description' => $request->short_description,
            'description'       => $request->description,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('images/produk', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|integer|min:0',
            'category'          => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'              => $request->name,
            'slug'              => Str::slug($request->name),
            'price'             => $request->price,
            'category'          => $request->category,
            'short_description' => $request->short_description,
            'description'       => $request->description,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('images/produk', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus.');
    }
}
