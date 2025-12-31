<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * Halaman kasir – pilih produk & input transaksi.
     */
    public function index()
    {
        $products = Product::orderBy('name')->get();
        return view('kasir.index', compact('products'));
    }

    /**
     * Simpan transaksi kasir.
     * - Validasi input
     * - Cek stok
     * - Hitung total + profit (benefit)
     * - Simpan ke sales & sale_items
     * - Kurangi stok
     * - Catat otomatis ke barang_keluar
     */
    public function store(Request $request)
    {
        // 1) Validasi input dasar
        $request->validate([
            'product_id'   => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'integer', 'exists:products,id'],
            'qty'          => ['required', 'array', 'min:1'],
            'qty.*'        => ['required', 'integer', 'min:1'],
            'paid_amount'  => ['required', 'integer', 'min:0'],
        ]);

        $productIds = $request->input('product_id', []);
        $qtys       = $request->input('qty', []);

        // Validasi tambahan: jumlah product dan qty harus sama
        if (count($productIds) !== count($qtys)) {
            return back()->withErrors(['qty' => 'Qty tidak sesuai jumlah produk.'])->withInput();
        }

        // 2) Ambil semua produk sekaligus
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // 3) Cek stok + siapkan items + hitung total & profit (DI LUAR TRANSACTION)
        $itemsData   = [];
        $total       = 0;
        $profitTotal = 0;

        foreach ($productIds as $index => $pid) {
            $product = $products->get($pid);

            if (!$product) {
                return back()->withErrors(['product_id' => 'Produk tidak ditemukan.'])->withInput();
            }

            $qty = (int) $qtys[$index];

            if ($qty > (int) $product->stock) {
                return back()->withErrors([
                    'stok' => "Stok produk {$product->name} tidak cukup. Stok: {$product->stock}, diminta: {$qty}."
                ])->withInput();
            }

            $price = (int) ($product->price ?? 0);        // harga jual
            $cost  = (int) ($product->harga_beli ?? 0);   // modal / harga beli

            $subtotal = $price * $qty;
            $profit   = ($price - $cost) * $qty;          // benefit per item (bisa minus)

            $itemsData[] = [
                'product_id' => $product->id,
                'qty'        => $qty,
                'price'      => $price,
                'cost'       => $cost,
                'subtotal'   => $subtotal,
                'profit'     => $profit,
            ];

            $total       += $subtotal;
            $profitTotal += $profit;
        }

        // 4) Hitung kembalian
        $paid   = (int) $request->paid_amount;
        $change = $paid - $total;

        // kalau bayar kurang, kamu bisa blok atau biarkan 0
        if ($change < 0) {
            // Kalau mau ditolak, pakai ini:
            // return back()->withErrors(['paid_amount' => 'Uang bayar kurang.'])->withInput();
            $change = 0;
        }

        // 5) Simpan semua dalam transaction
        $sale = DB::transaction(function () use ($itemsData, $total, $paid, $change, $profitTotal) {

            // invoice lebih aman unik
            $invoice = 'INV-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            // Simpan transaksi utama ke tabel sales + profit total
            $sale = Sale::create([
                'invoice_number' => $invoice,
                'total'          => $total,
                'paid_amount'    => $paid,
                'change_amount'  => $change,
                'profit'         => $profitTotal, // ✅ masuk laporan
                // 'user_id'      => auth()->id(), // ✅ aktifkan kalau sales punya user_id
            ]);

            // Simpan item + kurangi stok + catat barang keluar
            foreach ($itemsData as $item) {

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty'        => $item['qty'],
                    'price'      => $item['price'],
                    'cost'       => $item['cost'],
                    'subtotal'   => $item['subtotal'],
                    'profit'     => $item['profit'],
                ]);

                // kurangi stok
                Product::where('id', $item['product_id'])->decrement('stock', $item['qty']);

                // catat barang keluar
                BarangKeluar::create([
                    'product_id' => $item['product_id'],
                    'qty'        => $item['qty'],
                    'keterangan' => 'Penjualan ' . $sale->invoice_number,
                ]);
            }

            return $sale;
        });

        // 6) Redirect ke halaman struk
        return redirect()->route('kasir.struk', $sale);
    }

    /**
     * Halaman struk thermal kecil – siap untuk dicetak.
     */
    public function struk(Sale $sale)
    {
        $sale->load(['items.product']);
        return view('kasir.struk', compact('sale'));
    }
}
