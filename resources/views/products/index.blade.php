@extends('layouts.app')

@section('title', 'Produk - Toko Serba-Serbi Banten')

@section('content')
<div class="container">
    <h1 class="h3 fw-semibold mb-4">Daftar Produk</h1>

    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Cari produk... (dummy)">
        </div>
    </div>

    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset($product['image']) }}" class="card-img-top" alt="{{ $product['name'] }}"
                         onerror="this.src='https://via.placeholder.com/400x250?text=Produk+Banten';">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-warning text-dark mb-2">{{ $product['category'] }}</span>
                        <h5 class="card-title">{{ $product['name'] }}</h5>
                        <p class="card-text text-muted">{{ $product['short_description'] }}</p>
                        <p class="fw-bold mb-3">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                        <a href="{{ route('products.show', $product['slug']) }}"
                           class="btn btn-sm btn-brand mt-auto text-white">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p>Belum ada produk.</p>
        @endforelse
    </div>
</div>
@endsection
