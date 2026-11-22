@extends('layouts.storefront')
@section('title', 'Pesanan Saya')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-choco"><i class="bi bi-bag-check me-2" style="color:var(--caramel);"></i>Pesanan Saya</h2>
            <p class="text-muted small mb-0">Pantau status pembuatan dan riwayat pesanan kue Anda</p>
        </div>
        <a href="{{ route('catalog') }}" class="btn btn-outline-secondary">
            <i class="bi bi-plus-lg me-1"></i>Pesan Kue Lain
        </a>
    </div>
    
    @if($orders->isEmpty())
        <div class="empty-state bg-white p-5 rounded-4 shadow-sm text-center">
            <div class="empty-icon fs-1">📦</div>
            <h4 class="fw-bold">Belum ada riwayat pesanan</h4>
            <p class="text-muted mb-4">Yuk buat pesanan kue pertamamu hari ini!</p>
            <a href="{{ route('catalog') }}" class="btn btn-primary-lala">Lihat Katalog</a>
        </div>
    @else
        <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal Ambil</th>
                            <th>Total Belanja</th>
                            <th>Status Pesanan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('orders.my.detail', $order) }}" class="fw-bold text-decoration-none" style="color:var(--caramel);">
                                    #{{ $order->order_number }}
                                </a>
                                <div class="text-muted small">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('d F Y') }}</div>
                                @if(\Carbon\Carbon::parse($order->pickup_date)->isToday())
                                    <span class="badge bg-danger small">Hari ini!</span>
                                @endif
                            </td>
                            <td class="fw-bold" style="color:var(--caramel);">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge-status badge-{{ $order->status }}">
                                    {{ $order->status_label }}
                                </span>
                                @if($order->status === 'menunggu_pembayaran' && $order->payment_reject_reason)
                                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>Bukti ditolak: {{ Str::limit($order->payment_reject_reason, 40) }}</div>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('orders.my.detail', $order) }}" class="btn btn-sm btn-outline-secondary" title="Detail Pesanan">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(in_array($order->status, ['menunggu_pembayaran', 'menunggu_verifikasi']))
                                    <a href="{{ route('payment.show', $order) }}" class="btn btn-sm btn-primary-lala" title="Unggah Bukti Bayar">
                                        <i class="bi bi-upload"></i> Bayar
                                    </a>
                                    @endif
                                    <a href="{{ route('order.nota', $order) }}" class="btn btn-sm btn-outline-dark" title="Cetak Nota">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
