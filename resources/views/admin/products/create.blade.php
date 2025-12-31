@extends('layouts.app')

@section('title', 'Tambah Produk - Admin')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 fw-semibold mb-0">Tambah Produk Baru</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Harga Jual (Rp)</label>
                <input type="number" name="price" class="form-control"
                       value="{{ old('price', 0) }}" min="0" required>
                <small class="text-muted">Harga jual ke pelanggan.</small><br>
                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Harga Beli / Modal (Rp)</label>
                <input type="number" name="harga_beli" class="form-control"
                       value="{{ old('harga_beli', 0) }}" min="0" required>
                <small class="text-muted">Modal untuk hitung benefit/profit.</small><br>
                @error('harga_beli') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Stok</label>
                <input type="number" name="stock" class="form-control"
                       value="{{ old('stock', 0) }}" min="0" required>
                @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <input type="text" name="category" class="form-control"
                       value="{{ old('category') }}" placeholder="Contoh: Minuman / Snack">
                @error('category') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <hr class="my-4">

        <div class="mb-3">
            <label class="form-label">Deskripsi Singkat</label>
            <input type="text" name="short_description" class="form-control"
                   value="{{ old('short_description') }}"
                   placeholder="Contoh: Botol 600ml, rasa jeruk">
            @error('short_description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="4" class="form-control"
                      placeholder="Detail produk (opsional)">{{ old('description') }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Produk</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Opsional, maksimal 2MB (jpg/png/webp).</small><br>
            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="d-flex gap-2 mt-3">
            <button class="btn btn-brand text-white">Simpan</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
