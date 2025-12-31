@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
<div class="container my-4">
    <h3 class="mb-3">Barang Masuk</h3>

    <a href="{{ route('barang-masuk.create') }}" class="btn btn-brand text-white mb-3">
        + Tambah Pemasukan Barang
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->keterangan }}</td>

                    @php
                        $bulanIndo = [
                            'January'   => 'Januari',
                            'February'  => 'Februari',
                            'March'     => 'Maret',
                            'April'     => 'April',
                            'May'       => 'Mei',
                            'June'      => 'Juni',
                            'July'      => 'Juli',
                            'August'    => 'Agustus',
                            'September' => 'September',
                            'October'   => 'Oktober',
                            'November'  => 'November',
                            'December'  => 'Desember',
                        ];

                        $tanggalAsli = $item->created_at->format('d F Y'); // contoh: 07 December 2025
                        $tanggalIndo = strtr($tanggalAsli, $bulanIndo);    // jadi: 07 Desember 2025
                    @endphp
                    <td>{{ $tanggalIndo }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data barang masuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
