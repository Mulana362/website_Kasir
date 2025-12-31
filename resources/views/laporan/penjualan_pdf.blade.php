<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 6px; }
        .muted { color: #555; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #222; padding: 6px; }
        th { background: #eee; }
        .right { text-align: right; }
        .center { text-align: center; }
        .summary { margin: 10px 0; }
        .summary div { margin: 2px 0; }
    </style>
</head>
<body>
    <div class="title">Laporan Penjualan</div>
    <div class="muted">Periode: {{ $from }} s/d {{ $to }}</div>

    <div class="summary">
        <div><b>Omzet:</b> Rp {{ number_format($summary->omzet ?? 0, 0, ',', '.') }}</div>
        <div><b>Benefit (Profit):</b> Rp {{ number_format($summary->profit ?? 0, 0, ',', '.') }}</div>
        <div><b>Margin:</b> {{ $margin }}%</div>
        <div><b>Jumlah Transaksi:</b> {{ $summary->trx_count ?? 0 }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40px" class="center">No</th>
                <th style="width:150px">Tanggal</th>
                <th>Invoice</th>
                <th class="right">Omzet</th>
                <th class="right">Benefit</th>
                <th class="right">Margin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $i => $s)
                @php
                    $omzet = (int) $s->total;
                    $profit = (int) ($s->profit ?? 0);
                    $m = $omzet > 0 ? round(($profit / $omzet) * 100, 2) : 0;
                @endphp
                <tr>
                    <td class="center">{{ $i+1 }}</td>
                    <td>{{ optional($s->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $s->invoice_number }}</td>
                    <td class="right">Rp {{ number_format($omzet, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($profit, 0, ',', '.') }}</td>
                    <td class="right">{{ $m }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
