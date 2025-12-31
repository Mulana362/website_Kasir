@extends('layouts.app')

@section('title', 'Tambah Barang Masuk')

@section('content')
<div class="container my-4">

    <h3 class="mb-3">Tambah Barang Masuk</h3>

    <form action="{{ route('barang-masuk.store') }}" method="POST" class="card p-3 shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Produk</label>
            <select name="product_id" class="form-control" required>
                <option value="">-- Pilih Produk --</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->stock }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Qty Masuk</label>
            <input type="number" name="qty" class="form-control" required min="1">
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <input type="text" name="keterangan" class="form-control">
        </div>

        <button class="btn btn-brand text-white">Simpan</button>
        <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
