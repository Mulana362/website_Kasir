@extends('layouts.app')

@section('title', 'Dashboard Laporan')

@section('content')
<div class="container my-4">

    <h3 class="mb-3">Dashboard Laporan</h3>

    {{-- Kartu ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="small text-muted">Penjualan Hari Ini</div>
                    <div class="h5 mb-0">Rp {{ number_format($salesTodayTotal, 0, ',', '.') }}</div>
                    <small class="text-muted">{{ $salesTodayCount }} transaksi</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="small text-muted">Penjualan Bulan Ini</div>
                    <div class="h5 mb-0">Rp {{ number_format($salesMonthTotal, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="small text-muted">Barang Masuk (Hari Ini)</div>
                    <div class="h5 mb-0">{{ $barangMasukToday }} pcs</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="small text-muted">Barang Keluar (Hari Ini)</div>
                    <div class="h5 mb-0">{{ $barangKeluarToday }} pcs</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris kedua --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="small text-muted">Total Stok Produk</div>
                    <div class="h5 mb-0">{{ $totalStock }} pcs</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top produk terlaris & transaksi terbaru --}}
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Top 5 Produk Terlaris</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Qty Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item->product->name ?? 'Produk' }}</td>
                                    <td>{{ $item->total_qty }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Terbaru</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $i => $s)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $s->invoice_number }}</td>
                                    <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                                    <td>Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
