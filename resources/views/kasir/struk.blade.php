@extends('layouts.app')

@section('title', 'Struk Pembelian')

@section('content')
<style>
    /* Area struk kecil */
    .receipt-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .receipt {
        width: 280px; /* lebar struk thermal kira-kira */
        font-family: monospace;
        font-size: 11px;
        border: 1px solid #ddd;
        padding: 10px;
        background: #fff;
    }

    .receipt h5,
    .receipt p {
        margin: 0;
        padding: 0;
    }

    .receipt .line {
        border-bottom: 1px dashed #000;
        margin: 4px 0;
    }

    .receipt table {
        width: 100%;
    }

    .receipt table td {
        padding: 0;
        vertical-align: top;
    }

    .receipt .text-right {
        text-align: right;
    }

    .receipt .text-center {
        text-align: center;
    }

    .receipt .mt-1 {
        margin-top: 4px;
    }

    .receipt .mb-1 {
        margin-bottom: 4px;
    }

    .receipt .mt-2 {
        margin-top: 8px;
    }

    .receipt .mb-2 {
        margin-bottom: 8px;
    }

    /* Tombol di bawah struk */
    .receipt-actions {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .receipt, .receipt * {
            visibility: visible;
        }

        .receipt {
            margin: 0;
            border: none;
            width: 58mm; /* ukuran thermal printer */
        }

        .receipt-actions {
            display: none;
        }

        .navbar,
        footer {
            display: none !important;
        }
    }
</style>

<div class="receipt-wrapper">
    <div class="receipt">
        <h5 class="text-center">Toko Serba-Serbi Banten</h5>
        <p class="text-center" style="font-size: 10px;">
            Oleh-oleh khas Banten<br>
            Jl.Komplek Ciceri Permai No. 29 Kota Serang
        </p>

        <div class="line"></div>

        <table style="font-size: 10px; margin-bottom: 4px;">
            <tr>
                <td>No</td>
                <td class="text-right">{{ $sale->invoice_number }}</td>
            </tr>
            <tr>
                <td>Tgl</td>
                <td class="text-right">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td class="text-right">{{ auth()->user()->name ?? '-' }}</td>
            </tr>
        </table>

        <div class="line"></div>

        {{-- Detail item --}}
        <table style="font-size: 10px;">
            @foreach($sale->items as $item)
                <tr>
                    <td colspan="3">
                        {{ $item->product->name ?? 'Produk' }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%;">
                        {{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}
                    </td>
                    <td></td>
                    <td class="text-right">
                        {{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="line"></div>

        {{-- Total --}}
        <table style="font-size: 10px;">
            <tr>
                <td>Total</td>
                <td class="text-right">{{ number_format($sale->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunai</td>
                <td class="text-right">{{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-right">{{ number_format($sale->change_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="line"></div>

        <p class="text-center" style="font-size: 10px; margin-top: 4px;">
            Terima kasih<br>
            Barang yang sudah dibeli<br>
            tidak dapat dikembalikan.
        </p>
    </div>
</div>

<div class="receipt-actions no-print">
    <button class="btn btn-sm btn-outline-secondary" onclick="window.print();">
        Cetak Struk
    </button>
    <a href="{{ route('kasir.index') }}" class="btn btn-sm btn-brand text-white">
        Transaksi Baru
    </a>
</div>
@endsection
