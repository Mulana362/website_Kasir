<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Product;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $items = BarangKeluar::with('product')
            ->latest()
            ->get();

        return view('barang_keluar.index', compact('items'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('barang_keluar.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->qty > $product->stock) {
            return back()->withErrors([
                'qty' => 'Stok tidak cukup untuk pengeluaran barang.'
            ])->withInput();
        }

        // catat barang keluar
        BarangKeluar::create([
            'product_id' => $product->id,
            'qty'        => $request->qty,
            'keterangan' => $request->keterangan ?? 'Pengeluaran manual',
        ]);

        // kurangi stok
        $product->decrement('stock', $request->qty);

        return redirect()->route('barang-keluar.index')
                         ->with('success', 'Pengeluaran barang berhasil dicatat.');
    }
}
