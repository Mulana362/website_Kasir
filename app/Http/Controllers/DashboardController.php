<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        // Penjualan hari ini
        $salesTodayTotal = Sale::whereDate('created_at', $today)->sum('total');
        $salesTodayCount = Sale::whereDate('created_at', $today)->count();

        // Penjualan bulan ini
        $salesMonthTotal = Sale::whereBetween('created_at', [$monthStart, Carbon::now()])->sum('total');

        // Barang masuk / keluar hari ini
        $barangMasukToday = BarangMasuk::whereDate('created_at', $today)->sum('qty');
        $barangKeluarToday = BarangKeluar::whereDate('created_at', $today)->sum('qty');

        // Total stok
        $totalStock = Product::sum('stock');

        // Top 5 produk terlaris (berdasarkan qty)
        $topProducts = SaleItem::with('product')
            ->selectRaw('product_id, SUM(qty) as total_qty')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Transaksi terbaru
        $recentSales = Sale::latest()->take(5)->get();

        return view('dashboard.index', compact(
            'salesTodayTotal',
            'salesTodayCount',
            'salesMonthTotal',
            'barangMasukToday',
            'barangKeluarToday',
            'totalStock',
            'topProducts',
            'recentSales'
        ));
    }
}
