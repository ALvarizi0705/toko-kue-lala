<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Bahan Baku - Toko Kue Lala</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { background: #5B3A29; color: #fff; text-align: left; }
        .text-end { text-align: right; }
        .text-danger { color: #D1495B; font-weight: bold; }
        .header { text-align: center; border-bottom: 2px solid #C8873E; padding-bottom: 10px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;color:#5B3A29;">TOKO KUE LALA</h2>
        <p style="margin:4px 0 0;font-size:10px;">Jl. Matraman Dalam III No. 26B, Jakarta Timur | Telp: 08123456789</p>
        <h3 style="margin:10px 0 0;color:#C8873E;">LAPORAN MUTASI & STOK BAHAN BAKU</h3>
        <p style="margin:2px 0 0;font-size:10px;">Periode: {{ $startDate }} s/d {{ $endDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Bahan</th>
                <th>Satuan</th>
                <th class="text-end">Masuk (+)</th>
                <th class="text-end">Pakai (-)</th>
                <th class="text-end">Koreksi (±)</th>
                <th class="text-end">Stok Akhir</th>
                <th class="text-end">Min.</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $row)
            <tr style="{{ $row['is_low'] ? 'background:#ffe6e6;' : '' }}">
                <td>{{ $row['code'] }}</td>
                <td><strong>{{ $row['name'] }}</strong></td>
                <td>{{ $row['unit'] }}</td>
                <td class="text-end">+ {{ number_format($row['total_in'], 2) }}</td>
                <td class="text-end">- {{ number_format($row['total_usage'], 2) }}</td>
                <td class="text-end">{{ $row['total_adj'] >= 0 ? '+' : '' }}{{ number_format($row['total_adj'], 2) }}</td>
                <td class="text-end" style="font-weight:bold;{{ $row['is_low'] ? 'color:#D1495B;' : '' }}">{{ number_format($row['stock'], 2) }}</td>
                <td class="text-end">{{ number_format($row['min_stock'], 2) }}</td>
                <td>{{ $row['is_low'] ? 'MENIPIS' : 'AMAN' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
