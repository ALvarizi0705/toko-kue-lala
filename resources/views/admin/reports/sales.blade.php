@extends('layouts.admin')
@section('title', 'Laporan Penjualan')
@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-graph-up-arrow me-2"></i>Laporan Penjualan</h5>
        <p class="text-muted small mb-0">Rekapitulasi gabungan pesanan online terverifikasi dan penjualan langsung toko</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.penjualan.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-sm btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i>Ekspor Excel (CSV)
        </a>
        <a href="{{ route('admin.laporan.penjualan.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-sm btn-danger">
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
            <a href="{{ route('admin.laporan.penjualan') }}" class="btn btn-sm btn-outline-secondary ms-1">Bulan Ini</a>
        </div>
    </form>
</div>

{{-- KARTU RINGKASAN TOTAL --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="summary-card">
            <div class="icon">🌐</div>
            <div>
                <div class="label">Pesanan Online (Terverifikasi)</div>
                <div class="value" style="font-size:1.25rem;">Rp {{ number_format($totalOnline, 0, ',', '.') }}</div>
                <div class="small text-muted">{{ $orders->count() }} transaksi</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card">
            <div class="icon">🏪</div>
            <div>
                <div class="label">Penjualan Langsung Toko</div>
                <div class="value" style="font-size:1.25rem;">Rp {{ number_format($totalDirect, 0, ',', '.') }}</div>
                <div class="small text-muted">{{ $directSales->count() }} transaksi</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary-card card-success">
            <div class="icon">💰</div>
            <div>
                <div class="label">Total Omset Penjualan</div>
                <div class="value" style="font-size:1.35rem;color:var(--caramel);">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                <div class="small text-muted">{{ $orders->count() + $directSales->count() }} total transaksi</div>
            </div>
        </div>
    </div>
</div>

{{-- GRAFIK TREN PENJUALAN CHART.JS --}}
<div class="form-card mb-4">
    <h6 class="fw-bold text-choco mb-3"><i class="bi bi-bar-chart me-2"></i>Grafik Tren Penjualan Harian</h6>
    <div style="height:280px;">
        <canvas id="salesChart"></canvas>
    </div>
</div>

{{-- TABEL RINCIAN TRANSAKSI --}}
<div class="form-card">
    <h6 class="fw-bold text-choco mb-3"><i class="bi bi-list-check me-2"></i>Rincian Transaksi Periode {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Tipe</th>
                    <th>No. Transaksi</th>
                    <th>Waktu</th>
                    <th>Pelanggan / Kasir</th>
                    <th>Metode Bayar</th>
                    <th>Status</th>
                    <th class="text-end">Total Biaya</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                <tr>
                    <td><span class="badge bg-primary">Online</span></td>
                    <td class="font-monospace fw-bold">#{{ $o->order_number }}</td>
                    <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $o->user->name ?? '-' }}</td>
                    <td class="text-uppercase">{{ str_replace('_', ' ', $o->payment_method ?? '-') }}</td>
                    <td><span class="badge-status badge-{{ $o->status }}">{{ $o->status_label }}</span></td>
                    <td class="text-end fw-bold">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                
                @foreach($directSales as $s)
                <tr>
                    <td><span class="badge bg-success">Toko (Langsung)</span></td>
                    <td class="font-monospace fw-bold">#{{ $s->sale_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($s->sold_at)->format('d/m/Y H:i') }}</td>
                    <td>Kasir: {{ $s->user->name ?? '-' }}</td>
                    <td class="text-uppercase">{{ $s->payment_method }}</td>
                    <td><span class="badge bg-success">Selesai</span></td>
                    <td class="text-end fw-bold">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                
                @if($orders->isEmpty() && $directSales->isEmpty())
                <tr><td colspan="7" class="text-center py-4 text-muted">Tidak ada transaksi pada periode ini.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [
            {
                label: 'Pesanan Online (Rp)',
                data: {!! json_encode($chartOnline) !!},
                backgroundColor: 'rgba(91, 58, 41, 0.7)',
                borderRadius: 4
            },
            {
                label: 'Penjualan Langsung Toko (Rp)',
                data: {!! json_encode($chartDirect) !!},
                backgroundColor: 'rgba(200, 135, 62, 0.85)',
                borderRadius: 4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + (value/1000).toLocaleString('id-ID') + 'k';
                    }
                }
            }
        }
    }
});
</script>
@endpush

@endsection
