<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $items = BarangMasuk::with('product')->latest()->get();
        return view('barang_masuk.index', compact('items'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('barang_masuk.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // ✅ simpan riwayat barang masuk (lebih aman daripada $request->all())
        BarangMasuk::create([
            'product_id' => $validated['product_id'],
            'qty'        => $validated['qty'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        // ✅ tambahkan stok produk
        $product = Product::findOrFail($validated['product_id']);
        $product->increment('stock', $validated['qty']);

        return redirect()->route('barang-masuk.index')
            ->with('success', 'Barang masuk berhasil ditambahkan & stok bertambah.');
    }
}
