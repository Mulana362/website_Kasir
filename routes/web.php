<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (HARUS LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Kasir
    |--------------------------------------------------------------------------
    */
    Route::get('/', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');
    Route::get('/kasir/struk/{sale}', [KasirController::class, 'struk'])->name('kasir.struk');

    /*
    |--------------------------------------------------------------------------
    | Laporan Penjualan (Omzet + Benefit/Profit)
    |--------------------------------------------------------------------------
    */
    Route::get('/laporan/penjualan', [ReportController::class, 'sales'])
        ->name('laporan.penjualan');

    // ✅ EXPORT (WAJIB di atas {sale})
    Route::get('/laporan/penjualan/pdf', [ReportController::class, 'salesPdf'])
        ->name('laporan.penjualan.pdf');

    Route::get('/laporan/penjualan/csv', [ReportController::class, 'salesCsv'])
        ->name('laporan.penjualan.csv');

    // ✅ HAPUS DATA LAMA (purge)
    Route::post('/laporan/penjualan/purge', [ReportController::class, 'purgeSales'])
        ->name('laporan.penjualan.purge');

    // ✅ Detail transaksi (taruh setelah pdf/csv biar tidak ketabrak)
    Route::get('/laporan/penjualan/{sale}', [ReportController::class, 'showSale'])
        ->name('laporan.penjualan.show');

    /*
    |--------------------------------------------------------------------------
    | Dashboard Laporan
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Produk (Admin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::resource('products', AdminProductController::class);

        // ✅ quick edit Harga Beli (Modal)
        Route::patch('products/{product}/harga-beli', [AdminProductController::class, 'updateHargaBeli'])
            ->name('products.harga-beli');
    });

    /*
    |--------------------------------------------------------------------------
    | Barang Masuk
    |--------------------------------------------------------------------------
    */
    Route::prefix('barang-masuk')->name('barang-masuk.')->group(function () {
        Route::get('/', [BarangMasukController::class, 'index'])->name('index');
        Route::get('/create', [BarangMasukController::class, 'create'])->name('create');
        Route::post('/store', [BarangMasukController::class, 'store'])->name('store');
    });

    /*
    |--------------------------------------------------------------------------
    | Barang Keluar
    |--------------------------------------------------------------------------
    */
    Route::prefix('barang-keluar')->name('barang-keluar.')->group(function () {
        Route::get('/', [BarangKeluarController::class, 'index'])->name('index');
        Route::get('/create', [BarangKeluarController::class, 'create'])->name('create');
        Route::post('/store', [BarangKeluarController::class, 'store'])->name('store');
    });

});
