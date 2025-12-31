@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="mb-1">Detail Transaksi</h3>
      <div class="text-muted">
        {{ $sale->invoice_number }} • {{ $sale->created_at->format('d/m/Y H:i') }}
      </div>
    </div>
    <a href="{{ route('laporan.penjualan') }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <div class="card shadow-sm"><div class="card-body">
        <div class="text-muted small">Total</div>
        <div class="fw-bold fs-5">Rp {{ number_format($sale->total,0,',','.') }}</div>
      </div></div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm"><div class="card-body">
        <div class="text-muted small">Bayar</div>
        <div class="fw-bold fs-5">Rp {{ number_format($sale->paid_amount,0,',','.') }}</div>
      </div></div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm"><div class="card-body">
        <div class="text-muted small">Kembalian</div>
        <div class="fw-bold fs-5">Rp {{ number_format($sale->change_amount,0,',','.') }}</div>
      </div></div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm"><div class="card-body">
        <div class="text-muted small">Benefit</div>
        <div class="fw-bold fs-5">Rp {{ number_format($sale->profit,0,',','.') }}</div>
      </div></div>
    </div>
  </div>

  <div class="alert alert-info">
    Detail item (produk per transaksi) akan tampil setelah relasi <b>sale_items</b> dipastikan kolomnya.
  </div>
</div>
@endsection
