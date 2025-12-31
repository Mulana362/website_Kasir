<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'harga_beli'        => 'required|integer|min:0', // ✅ modal
            'stock'             => 'required|integer|min:0',
            'category'          => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'              => $request->name,
            'slug'              => Str::slug($request->name),
            'price'             => (int) $request->price,
            'harga_beli'        => (int) $request->harga_beli,
            'stock'             => (int) $request->stock,
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
            'harga_beli'        => 'required|integer|min:0', // ✅ modal
            'stock'             => 'required|integer|min:0',
            'category'          => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:255',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'              => $request->name,
            'slug'              => Str::slug($request->name),
            'price'             => (int) $request->price,
            'harga_beli'        => (int) $request->harga_beli,
            'stock'             => (int) $request->stock,
            'category'          => $request->category,
            'short_description' => $request->short_description,
            'description'       => $request->description,
        ];

        // Update image (hapus yang lama biar gak numpuk)
        if ($request->hasFile('image')) {
            if (!empty($product->image_path) && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('images/produk', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate.');
    }

    /**
     * ✅ QUICK UPDATE: Set Harga Beli (Modal) dari tabel index (Modal Bootstrap)
     * Route: admin.products.harga-beli (PATCH)
     */
    public function updateHargaBeli(Request $request, Product $product)
    {
        $data = $request->validate([
            'harga_beli' => ['required', 'integer', 'min:0'],
        ]);

        $product->update([
            'harga_beli' => (int) $data['harga_beli'],
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Harga beli (modal) berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        // Hapus file image kalau ada
        if (!empty($product->image_path) && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
