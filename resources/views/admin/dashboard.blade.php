@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

{{-- SUMMARY CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="summary-card card-warning">
            <div class="icon">📥</div>
            <div>
                <div class="label">Pesanan Baru</div>
                <div class="value">{{ $pendingOrders }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="summary-card">
            <div class="icon">👩‍🍳</div>
            <div>
                <div class="label">Sedang Diproses</div>
                <div class="value">{{ $processingToday }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="summary-card" style="border-left: 4px solid var(--caramel);">
            <div class="icon">🎂</div>
            <div>
                <div class="label">Siap Diambil</div>
                <div class="value">{{ $readyOrders }}</div>
            </div>
        </div>
    </div>
    @if(auth()->user()->role === 'pemilik' && $salesToday !== null)
    <div class="col-6 col-md-3">
        <div class="summary-card card-success">
            <div class="icon">💰</div>
            <div>
                <div class="label">Penjualan Hari Ini</div>
                <div class="value" style="font-size:1.25rem;">Rp {{ number_format($salesToday, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row g-4">
    {{-- PESANAN TERDEKAT JADWAL AMBIL --}}
    <div class="col-12">
        <div class="form-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-choco"><i class="bi bi-calendar-check me-2"></i>Pesanan Terdekat Tanggal Ambil</h6>
                <a href="{{ route('admin.pesanan.index') }}" class="small text-decoration-none" style="color:var(--caramel);">Lihat Semua Pesanan →</a>
            </div>
            
            @if($upcomingOrders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-check2-circle fs-1 d-block mb-2 text-success"></i>
                    Belum ada pesanan aktif saat ini.
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Alamat / Kontak</th>
                            <th>Tanggal Ambil</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.pesanan.show', $order) }}" class="fw-bold text-decoration-none" style="color:var(--caramel);">
                                    #{{ $order->order_number }}
                                </a>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold small">{{ $order->customer_name ?? $order->user?->name ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="small"><i class="bi bi-whatsapp text-success me-1"></i>{{ $order->customer_phone ?? $order->user?->phone ?? '-' }}</div>
                                @if($order->customer_address)
                                    <div class="text-muted text-truncate" style="max-width:200px;font-size:0.75rem;">{{ $order->customer_address }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="small fw-semibold">{{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('d M Y') }}</div>
                                @if(\Carbon\Carbon::parse($order->pickup_date)->isToday())
                                    <span class="badge bg-danger small">Hari ini!</span>
                                @elseif(\Carbon\Carbon::parse($order->pickup_date)->isTomorrow())
                                    <span class="badge bg-warning text-dark small">Besok</span>
                                @endif
                            </td>
                            <td class="fw-bold" style="color:var(--caramel);">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge-status badge-{{ $order->status }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pesanan.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
