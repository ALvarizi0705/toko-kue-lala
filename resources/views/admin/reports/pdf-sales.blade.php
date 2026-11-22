<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Toko Kue Lala</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { background: #5B3A29; color: #fff; text-align: left; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .header { text-align: center; border-bottom: 2px solid #C8873E; padding-bottom: 10px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;color:#5B3A29;">TOKO KUE LALA</h2>
        <p style="margin:4px 0 0;font-size:10px;">Jl. Matraman Dalam III No. 26B, Jakarta Timur | Telp: 08123456789</p>
        <h3 style="margin:10px 0 0;color:#C8873E;">LAPORAN PENJUALAN</h3>
        <p style="margin:2px 0 0;font-size:10px;">Periode: {{ $startDate }} s/d {{ $endDate }}</p>
    </div>

    <table style="margin-bottom:15px;">
        <tr>
            <td><strong>Pesanan Online (Terverifikasi):</strong> Rp {{ number_format($totalOnline, 0, ',', '.') }} ({{ $orders->count() }} transaksi)</td>
            <td><strong>Penjualan Langsung Kasir:</strong> Rp {{ number_format($totalDirect, 0, ',', '.') }} ({{ $directSales->count() }} transaksi)</td>
            <td><strong>TOTAL OMSET:</strong> <span style="font-size:13px;color:#C8873E;font-weight:bold;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Tipe</th>
                <th>No. Transaksi</th>
                <th>Tanggal</th>
                <th>Pelanggan/Kasir</th>
                <th>Metode Bayar</th>
                <th>Status</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $o)
            <tr>
                <td>Online</td>
                <td>#{{ $o->order_number }}</td>
                <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $o->user->name ?? '-' }}</td>
                <td>{{ strtoupper(str_replace('_', ' ', $o->payment_method ?? '-')) }}</td>
                <td>{{ $o->status_label }}</td>
                <td class="text-end">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @foreach($directSales as $s)
            <tr>
                <td>Toko</td>
                <td>#{{ $s->sale_number }}</td>
                <td>{{ \Carbon\Carbon::parse($s->sold_at)->format('d/m/Y H:i') }}</td>
                <td>Kasir: {{ $s->user->name ?? '-' }}</td>
                <td>{{ strtoupper($s->payment_method) }}</td>
                <td>Selesai</td>
                <td class="text-end">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-end">GRAND TOTAL:</th>
                <th class="text-end">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
