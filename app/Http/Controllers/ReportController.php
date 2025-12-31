<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date'],
        ]);

        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $salesQuery = Sale::query()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderByDesc('created_at');

        $sales = (clone $salesQuery)->paginate(10)->withQueryString();

        $summary = (clone $salesQuery)
            ->selectRaw('COALESCE(SUM(total),0) as omzet')
            ->selectRaw('COALESCE(SUM(profit),0) as profit')
            ->selectRaw('COUNT(*) as trx_count')
            ->first();

        $omzet  = (int) ($summary->omzet ?? 0);
        $profit = (int) ($summary->profit ?? 0);

        $margin = ($omzet > 0)
            ? round(($profit / $omzet) * 100, 2)
            : 0;

        return view('laporan.penjualan', [
            'sales'   => $sales,
            'from'    => $from,
            'to'      => $to,
            'summary' => $summary,
            'margin'  => $margin,
        ]);
    }

    public function showSale(Sale $sale)
    {
        // Pastikan relasi items() ada di model Sale
        $sale->load(['items.product']);

        return view('laporan.penjualan_show', compact('sale'));
    }

    // =======================
    // EXPORT PDF
    // =======================
    public function salesPdf(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date'],
        ]);

        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $salesQuery = Sale::query()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderByDesc('created_at');

        $sales = (clone $salesQuery)->get();

        $summary = (clone $salesQuery)
            ->selectRaw('COALESCE(SUM(total),0) as omzet')
            ->selectRaw('COALESCE(SUM(profit),0) as profit')
            ->selectRaw('COUNT(*) as trx_count')
            ->first();

        $omzet  = (int) ($summary->omzet ?? 0);
        $profit = (int) ($summary->profit ?? 0);

        $margin = ($omzet > 0)
            ? round(($profit / $omzet) * 100, 2)
            : 0;

        $pdf = Pdf::loadView('laporan.penjualan_pdf', [
            'sales'   => $sales,
            'from'    => $from,
            'to'      => $to,
            'summary' => $summary,
            'margin'  => $margin,
        ])->setPaper('a4', 'landscape');

        $filename = "laporan-penjualan-{$from}-sd-{$to}.pdf";
        return $pdf->download($filename);
    }

    // =======================
    // EXPORT CSV
    // =======================
    public function salesCsv(Request $request): StreamedResponse
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date'],
        ]);

        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $sales = Sale::query()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderByDesc('created_at')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laporan-penjualan-'.$from.'-sd-'.$to.'.csv"',
        ];

        $callback = function () use ($sales) {
            $out = fopen('php://output', 'w');

            // optional BOM biar Excel enak baca UTF-8
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($out, ['Tanggal', 'Invoice', 'Omzet', 'Benefit', 'Margin(%)']);

            foreach ($sales as $s) {
                $omzet  = (int) ($s->total ?? 0);
                $profit = (int) ($s->profit ?? 0);
                $m      = $omzet > 0 ? round(($profit / $omzet) * 100, 2) : 0;

                fputcsv($out, [
                    optional($s->created_at)->format('Y-m-d H:i:s'),
                    $s->invoice_number ?? '-',
                    $omzet,
                    $profit,
                    $m,
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    // =======================
    // HAPUS DATA LAMA
    // =======================
    public function purgeSales(Request $request)
    {
        $data = $request->validate([
            'before'  => ['required', 'date'],
            'confirm' => ['required', 'string'],
        ]);

        if (strtoupper(trim($data['confirm'])) !== 'HAPUS') {
            return back()->withErrors(['confirm' => 'Ketik HAPUS untuk konfirmasi.'])->withInput();
        }

        $before = $data['before'];

        DB::transaction(function () use ($before) {
            $saleIds = Sale::whereDate('created_at', '<', $before)->pluck('id');
            if ($saleIds->isEmpty()) {
                return;
            }

            // hapus item
            SaleItem::whereIn('sale_id', $saleIds)->delete();

            // opsional: hapus barang keluar hasil penjualan (kalau memang dibuat otomatis)
            BarangKeluar::where('keterangan', 'like', 'Penjualan %')
                ->whereDate('created_at', '<', $before)
                ->delete();

            // hapus sales
            Sale::whereIn('id', $saleIds)->delete();
        });

        return redirect()->route('laporan.penjualan')
            ->with('success', "Data transaksi sebelum {$before} berhasil dihapus.");
    }
}
