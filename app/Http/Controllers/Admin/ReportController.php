<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DirectSale;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\StockAdjustment;
use App\Models\StockIn;
use App\Models\StockUsage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Laporan Penjualan (Online terverifikasi + Penjualan Langsung).
     */
    public function penjualan(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Pesanan online terverifikasi (diproses, siap_diambil, selesai)
        $orders = Order::with('user')
            ->whereIn('status', ['diproses', 'siap_diambil', 'selesai'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        // Penjualan langsung kasir
        $directSales = DirectSale::with('user')
            ->whereDate('sold_at', '>=', $startDate)
            ->whereDate('sold_at', '<=', $endDate)
            ->get();

        $totalOnline = $orders->sum('total');
        $totalDirect = $directSales->sum('total');
        $grandTotal = $totalOnline + $totalDirect;

        // Data grafik 7 hari atau periode ini
        $chartLabels = [];
        $chartOnline = [];
        $chartDirect = [];

        $period = Carbon::parse($startDate)->daysUntil(Carbon::parse($endDate));
        foreach ($period as $date) {
            $d = $date->toDateString();
            $chartLabels[] = $date->format('d M');
            $chartOnline[] = $orders->filter(fn($o) => Carbon::parse($o->created_at)->toDateString() === $d)->sum('total');
            $chartDirect[] = $directSales->filter(fn($s) => Carbon::parse($s->sold_at)->toDateString() === $d)->sum('total');
        }

        return view('admin.reports.sales', compact(
            'startDate',
            'endDate',
            'orders',
            'directSales',
            'totalOnline',
            'totalDirect',
            'grandTotal',
            'chartLabels',
            'chartOnline',
            'chartDirect'
        ));
    }

    /**
     * Laporan Stok Bahan.
     */
    public function stok(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $ingredients = Ingredient::all();

        $reportData = $ingredients->map(function ($ing) use ($startDate, $endDate) {
            $totalIn = StockIn::where('ingredient_id', $ing->id)
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->sum('qty');

            $totalUsage = StockUsage::where('ingredient_id', $ing->id)
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->sum('qty');

            $totalAdjustment = StockAdjustment::where('ingredient_id', $ing->id)
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->sum('difference');

            return [
                'id' => $ing->id,
                'code' => $ing->code,
                'name' => $ing->name,
                'unit' => $ing->unit,
                'stock' => $ing->stock,
                'min_stock' => $ing->min_stock,
                'is_low' => $ing->stock <= $ing->min_stock,
                'total_in' => $totalIn,
                'total_usage' => $totalUsage,
                'total_adj' => $totalAdjustment,
            ];
        });

        return view('admin.reports.stock', compact('startDate', 'endDate', 'reportData'));
    }

    /**
     * Ekspor Laporan Penjualan ke Excel (Format CSV kompatibel Excel).
     */
    public function eksporExcelPenjualan(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $orders = Order::with('user')
            ->whereIn('status', ['diproses', 'siap_diambil', 'selesai'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        $directSales = DirectSale::with('user')
            ->whereDate('sold_at', '>=', $startDate)
            ->whereDate('sold_at', '<=', $endDate)
            ->get();

        $fileName = 'laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($orders, $directSales) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            fputcsv($handle, ['Tipe Transaksi', 'No. Transaksi', 'Tanggal', 'Pelanggan/Kasir', 'Metode Bayar', 'Status', 'Total (Rp)']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    'Pesanan Online',
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->user->name ?? '-',
                    strtoupper(str_replace('_', ' ', $order->payment_method ?? '-')),
                    $order->status_label,
                    $order->total,
                ]);
            }

            foreach ($directSales as $sale) {
                fputcsv($handle, [
                    'Penjualan Langsung',
                    $sale->sale_number,
                    Carbon::parse($sale->sold_at)->format('Y-m-d H:i'),
                    'Kasir: ' . ($sale->user->name ?? '-'),
                    strtoupper($sale->payment_method),
                    'Selesai',
                    $sale->total,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Ekspor Laporan Penjualan ke PDF.
     */
    public function eksporPdfPenjualan(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $orders = Order::with('user')
            ->whereIn('status', ['diproses', 'siap_diambil', 'selesai'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        $directSales = DirectSale::with('user')
            ->whereDate('sold_at', '>=', $startDate)
            ->whereDate('sold_at', '<=', $endDate)
            ->get();

        $totalOnline = $orders->sum('total');
        $totalDirect = $directSales->sum('total');
        $grandTotal = $totalOnline + $totalDirect;

        if (class_exists(Pdf::class)) {
            $pdf = Pdf::loadView('admin.reports.pdf-sales', compact('startDate', 'endDate', 'orders', 'directSales', 'totalOnline', 'totalDirect', 'grandTotal'));
            return $pdf->download('laporan-penjualan-' . $startDate . '-sd-' . $endDate . '.pdf');
        }

        return view('admin.reports.pdf-sales', compact('startDate', 'endDate', 'orders', 'directSales', 'totalOnline', 'totalDirect', 'grandTotal'));
    }

    /**
     * Ekspor Laporan Stok ke Excel (CSV kompatibel).
     */
    public function eksporExcelStok(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $ingredients = Ingredient::all();

        $fileName = 'laporan-stok-' . $startDate . '-sd-' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($ingredients, $startDate, $endDate) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Kode', 'Nama Bahan', 'Satuan', 'Stok Saat Ini', 'Batas Minimum', 'Total Masuk', 'Total Pakai', 'Koreksi', 'Status']);

            foreach ($ingredients as $ing) {
                $totalIn = StockIn::where('ingredient_id', $ing->id)
                    ->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate)->sum('qty');
                $totalUsage = StockUsage::where('ingredient_id', $ing->id)
                    ->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate)->sum('qty');
                $totalAdj = StockAdjustment::where('ingredient_id', $ing->id)
                    ->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate)->sum('difference');

                fputcsv($handle, [
                    $ing->code,
                    $ing->name,
                    $ing->unit,
                    $ing->stock,
                    $ing->min_stock,
                    $totalIn,
                    $totalUsage,
                    $totalAdj,
                    ($ing->stock <= $ing->min_stock) ? 'MENIPIS' : 'AMAN',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Ekspor Laporan Stok ke PDF.
     */
    public function eksporPdfStok(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $ingredients = Ingredient::all();

        $reportData = $ingredients->map(function ($ing) use ($startDate, $endDate) {
            return [
                'code' => $ing->code,
                'name' => $ing->name,
                'unit' => $ing->unit,
                'stock' => $ing->stock,
                'min_stock' => $ing->min_stock,
                'is_low' => $ing->stock <= $ing->min_stock,
                'total_in' => StockIn::where('ingredient_id', $ing->id)->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate)->sum('qty'),
                'total_usage' => StockUsage::where('ingredient_id', $ing->id)->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate)->sum('qty'),
                'total_adj' => StockAdjustment::where('ingredient_id', $ing->id)->whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate)->sum('difference'),
            ];
        });

        if (class_exists(Pdf::class)) {
            $pdf = Pdf::loadView('admin.reports.pdf-stock', compact('startDate', 'endDate', 'reportData'));
            return $pdf->download('laporan-stok-' . $startDate . '-sd-' . $endDate . '.pdf');
        }

        return view('admin.reports.pdf-stock', compact('startDate', 'endDate', 'reportData'));
    }
}
