@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container py-4">

  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
    <h3 class="mb-0">Laporan Penjualan</h3>

    {{-- ✅ Tombol Export (tidak dobel) --}}
    <div class="d-flex gap-2">
      <a class="btn btn-outline-dark"
         href="{{ route('laporan.penjualan.csv', ['from' => $from, 'to' => $to]) }}">
        Export CSV
      </a>

      <a class="btn btn-dark"
         href="{{ route('laporan.penjualan.pdf', ['from' => $from, 'to' => $to]) }}">
        Download PDF
      </a>
    </div>
  </div>

  <form class="card card-body mb-3" method="GET" action="{{ route('laporan.penjualan') }}">
    <div class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label">Dari</label>
        <input type="date" class="form-control" name="from" value="{{ $from }}">
      </div>

      <div class="col-md-4">
        <label class="form-label">Sampai</label>
        <input type="date" class="form-control" name="to" value="{{ $to }}">
      </div>

      <div class="col-md-4 d-grid">
        <button class="btn btn-brand text-white">Terapkan Filter</button>
      </div>
    </div>
  </form>

  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Omzet</div>
          <div class="fs-4 fw-bold">Rp {{ number_format($summary->omzet,0,',','.') }}</div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Benefit (Profit)</div>
          <div class="fs-4 fw-bold">Rp {{ number_format($summary->profit,0,',','.') }}</div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Margin</div>
          <div class="fs-4 fw-bold">{{ $margin }}%</div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <div class="text-muted small">Jumlah Transaksi</div>
          <div class="fs-4 fw-bold">{{ $summary->trx_count }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-dark">
          <tr>
            <th style="min-width:160px;">Tanggal</th>
            <th style="min-width:160px;">Invoice</th>
            <th class="text-end" style="min-width:130px;">Omzet</th>
            <th class="text-end" style="min-width:130px;">Benefit</th>
            <th class="text-end" style="min-width:110px;">Margin</th>
            <th style="width:110px;"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($sales as $s)
            @php
              $profit = (int) ($s->profit ?? 0);
              $m = $s->total > 0 ? round(($profit / $s->total) * 100, 2) : 0;
            @endphp
            <tr>
              <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
              <td>{{ $s->invoice_number }}</td>
              <td class="text-end">Rp {{ number_format($s->total,0,',','.') }}</td>
              <td class="text-end">Rp {{ number_format($profit,0,',','.') }}</td>
              <td class="text-end">{{ $m }}%</td>
              <td class="text-end">
                <a href="{{ route('laporan.penjualan.show', $s->id) }}"
                   class="btn btn-sm btn-outline-primary">
                  Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-body">
      {{ $sales->links() }}
    </div>
  </div>

</div>
@endsection
