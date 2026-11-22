@extends('layouts.admin')
@section('title', 'Laporan Stok Bahan Baku')
@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-clipboard-data me-2"></i>Laporan Mutasi & Stok Bahan Baku</h5>
        <p class="text-muted small mb-0">Rekap pergerakan stok awal, barang masuk, pemakaian, dan sisa fisik bahan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.stok.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-sm btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i>Ekspor Excel (CSV)
        </a>
        <a href="{{ route('admin.laporan.stok.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-sm btn-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i>Ekspor PDF
        </a>
    </div>
</div>

{{-- FILTER PERIODE --}}
<div class="form-card mb-4 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-bold">Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-bold">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-primary-lala">Tampilkan</button>
            <a href="{{ route('admin.laporan.stok') }}" class="btn btn-sm btn-outline-secondary ms-1">Bulan Ini</a>
        </div>
    </form>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Bahan Baku</th>
                    <th>Satuan</th>
                    <th class="text-end">Total Masuk (+)</th>
                    <th class="text-end">Total Pemakaian (-)</th>
                    <th class="text-end">Koreksi (±)</th>
                    <th class="text-end">Stok Akhir</th>
                    <th>Batas Min.</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $row)
                <tr class="{{ $row['is_low'] ? 'table-danger' : '' }}">
                    <td class="font-monospace fw-bold">{{ $row['code'] }}</td>
                    <td class="fw-bold text-choco">{{ $row['name'] }}</td>
                    <td>{{ $row['unit'] }}</td>
                    <td class="text-end text-success fw-bold">+ {{ number_format($row['total_in'], 2) }}</td>
                    <td class="text-end text-danger fw-bold">- {{ number_format($row['total_usage'], 2) }}</td>
                    <td class="text-end text-muted">{{ $row['total_adj'] >= 0 ? '+' : '' }}{{ number_format($row['total_adj'], 2) }}</td>
                    <td class="text-end fw-bold fs-6 {{ $row['is_low'] ? 'text-danger' : 'text-success' }}">
                        {{ number_format($row['stock'], 2) }}
                    </td>
                    <td>{{ number_format($row['min_stock'], 2) }}</td>
                    <td>
                        @if($row['is_low'])
                            <span class="badge bg-danger">MENIPIS</span>
                        @else
                            <span class="badge bg-success">AMAN</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
