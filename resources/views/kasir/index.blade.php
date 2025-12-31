@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<div class="container my-4">
    <h2 class="mb-3">Kasir Toko Serba-Serbi Banten</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kasir.store') }}" method="POST" id="kasir-form">
        @csrf

        <table class="table table-bordered align-middle" id="items-table">
            <thead class="table-dark">
                <tr>
                    <th style="width: 45%">Produk</th>
                    <th style="width: 15%">Harga</th>
                    <th style="width: 10%">Qty</th>
                    <th style="width: 20%">Subtotal</th>
                    <th style="width: 10%"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="item-row">
                    <td>
                        <select name="product_id[]" class="form-select product-select" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}"
                                        data-price="{{ $p->price }}">
                                    {{ $p->name }} - Rp {{ number_format($p->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="price-text">Rp 0</td>
                    <td>
                        <input type="number" name="qty[]" class="form-control qty-input"
                               min="1" value="1">
                    </td>
                    <td class="subtotal-text">Rp 0</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-row">
                            &times;
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-outline-primary mb-3" id="btn-add-row">
            + Tambah Item
        </button>

        <div class="row">
            <div class="col-md-4 ms-auto">
                <div class="card shadow-sm p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total:</span>
                        <strong id="total-text">Rp 0</strong>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Uang Bayar</label>
                        <input type="number" name="paid_amount" class="form-control"
                               id="paid-input" min="0" value="0">
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Kembalian:</span>
                        <strong id="change-text">Rp 0</strong>
                    </div>

                    <button class="btn btn-brand text-white w-100">
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemsTable = document.querySelector('#items-table tbody');
    const btnAddRow  = document.getElementById('btn-add-row');
    const totalText  = document.getElementById('total-text');
    const paidInput  = document.getElementById('paid-input');
    const changeText = document.getElementById('change-text');

    function formatRp(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function updateRow(row) {
        const select       = row.querySelector('.product-select');
        const priceCell    = row.querySelector('.price-text');
        const qtyInput     = row.querySelector('.qty-input');
        const subtotalCell = row.querySelector('.subtotal-text');

        const selectedOpt = select.selectedOptions[0];
        const price = selectedOpt ? parseInt(selectedOpt.dataset.price || '0', 10) : 0;
        const qty   = parseInt(qtyInput.value || '1', 10);

        const subtotal = price * qty;

        priceCell.textContent    = formatRp(price);
        subtotalCell.textContent = formatRp(subtotal);

        updateTotal();
    }

    function updateTotal() {
        let total = 0;

        itemsTable.querySelectorAll('.item-row').forEach(row => {
            const subtotalCell = row.querySelector('.subtotal-text');
            const raw = subtotalCell.textContent.replace(/[^\d]/g, '');
            total += parseInt(raw || '0', 10);
        });

        totalText.textContent = formatRp(total);

        const paid   = parseInt(paidInput.value || '0', 10);
        const change = Math.max(paid - total, 0);
        changeText.textContent = formatRp(change);
    }

    // Tambah baris baru
    btnAddRow.addEventListener('click', function () {
        const firstRow = itemsTable.querySelector('.item-row');
        const newRow   = firstRow.cloneNode(true);

        const select       = newRow.querySelector('.product-select');
        const qtyInput     = newRow.querySelector('.qty-input');
        const priceCell    = newRow.querySelector('.price-text');
        const subtotalCell = newRow.querySelector('.subtotal-text');

        select.selectedIndex   = 0;
        qtyInput.value         = 1;
        priceCell.textContent  = 'Rp 0';
        subtotalCell.textContent = 'Rp 0';

        itemsTable.appendChild(newRow);
    });

    // Reaksi kalau select produk / qty berubah
    itemsTable.addEventListener('change', function (e) {
        if (e.target.classList.contains('product-select') ||
            e.target.classList.contains('qty-input')) {
            updateRow(e.target.closest('.item-row'));
        }
    });

    // Hapus baris
    itemsTable.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-remove-row')) {
            const rows = itemsTable.querySelectorAll('.item-row');
            if (rows.length > 1) {
                e.target.closest('.item-row').remove();
                updateTotal();
            }
        }
    });

    // Hitung kembalian saat uang bayar diubah
    paidInput.addEventListener('input', updateTotal);

    // Inisialisasi (baris pertama)
    itemsTable.querySelectorAll('.item-row').forEach(updateRow);
});
</script>
@endsection
