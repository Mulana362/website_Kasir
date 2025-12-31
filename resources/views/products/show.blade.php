@extends('layouts.app')

@section('title', $product['name'] . ' - Toko Serba-Serbi Banten')

@section('content')
<div class="container">
    <div class="row my-4">
        <div class="col-md-5">
            <img src="{{ asset($product['image']) }}" class="img-fluid rounded-4 shadow-sm w-100"
                 alt="{{ $product['name'] }}"
                 onerror="this.src='https://via.placeholder.com/500x400?text=Produk+Banten';">
        </div>
        <div className="col-md-7">
            <span class="badge bg-warning text-dark mb-2">{{ $product['category'] }}</span>
            <h1 class="h3 fw-bold">{{ $product['name'] }}</h1>
            <p class="fw-bold fs-4 brand-primary">
                Rp {{ number_format($product['price'], 0, ',', '.') }}
            </p>
            <p class="text-muted">
                {{ $product['description'] }}
            </p>

            <div class="mt-4">
                <p class="mb-1"><strong>Catatan:</strong></p>
                <ul>
                    <li>Untuk pemesanan silakan hubungi WhatsApp di halaman Kontak.</li>
                    <li>Harga dapat berubah sewaktu-waktu.</li>
                </ul>
            </div>

            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-3">
                &larr; Kembali ke daftar produk
            </a>
        </div>
    </div>
</div>
@endsection
