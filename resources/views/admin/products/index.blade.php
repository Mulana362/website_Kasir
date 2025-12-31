@extends('layouts.app')

@section('title', 'Admin - Produk')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 fw-semibold mb-0">Manajemen Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-brand text-white">
            + Tambah Produk
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nama</th>
                            <th style="width: 140px;">Kategori</th>

                            <th class="text-end" style="width: 140px;">Harga Jual</th>
                            <th class="text-end" style="width: 140px;">Harga Beli</th>
                            <th class="text-end" style="width: 140px;">Profit / pcs</th>
                            <th class="text-end" style="width: 110px;">Margin</th>

                            <th class="text-center" style="width: 90px;">Stok</th>
                            <th style="width: 260px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                            @php
                                $hargaJual = (int) ($product->price ?? 0);
                                $hargaBeli = (int) ($product->harga_beli ?? 0);
                                $profitPcs = $hargaJual - $hargaBeli;
                                $margin    = $hargaJual > 0 ? round(($profitPcs / $hargaJual) * 100, 2) : 0;
                                $modalId   = 'modalHargaBeli' . $product->id;
                            @endphp

                            <tr>
                                <td>
                                    {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $product->name }}
                                    @if(($product->harga_beli ?? 0) == 0)
                                        <span class="badge bg-warning text-dark ms-2">Modal 0</span>
                                    @endif
                                </td>

                                <td>{{ $product->category ?? '-' }}</td>

                                <td class="text-end">
                                    Rp {{ number_format($hargaJual, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($hargaBeli, 0, ',', '.') }}
                                </td>

                                <td class="text-end">
                                    <span class="{{ $profitPcs < 0 ? 'text-danger fw-semibold' : 'text-success fw-semibold' }}">
                                        Rp {{ number_format($profitPcs, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="text-end">{{ $margin }}%</td>

                                <td class="text-center">
                                    {{ (int) ($product->stock ?? 0) }}
                                </td>

                                <td>
                                    {{-- Set Modal (quick edit) --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#{{ $modalId }}">
                                        Set Modal
                                    </button>

                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            Hapus
                                        </button>
                                    </form>

                                    {{-- Modal Set Harga Beli --}}
                                    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.products.harga-beli', $product) }}">
                                                    @csrf
                                                    @method('PATCH')

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Set Harga Beli (Modal)</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="mb-2">
                                                            <div class="fw-semibold">{{ $product->name }}</div>
                                                            <div class="text-muted small">
                                                                Harga jual: Rp {{ number_format($hargaJual, 0, ',', '.') }}
                                                            </div>
                                                        </div>

                                                        <label class="form-label">Harga Beli / Modal (Rp)</label>
                                                        <input type="number"
                                                               name="harga_beli"
                                                               class="form-control"
                                                               min="0"
                                                               value="{{ old('harga_beli', $hargaBeli) }}"
                                                               required>

                                                        <small class="text-muted">
                                                            Profit/pcs akan jadi: Rp {{ number_format($hargaJual - $hargaBeli, 0, ',', '.') }}
                                                        </small>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- End Modal --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    Belum ada produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection
