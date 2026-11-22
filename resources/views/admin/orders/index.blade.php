@extends('layouts.admin')
@section('title', 'Kelola Pesanan')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-bag-check me-2"></i>Daftar Pesanan Masuk</h5>
</div>

{{-- FILTER --}}
<div class="form-card mb-4 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-bold">Cari No. Pesanan / Nama</label>
            <input type="text" name="q" class="form-control form-control-sm" placeholder="No. pesanan / pemesan..." value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold">Status Pesanan</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="menunggu_pembayaran" {{ request('status') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="siap_diambil" {{ request('status') === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold">Tanggal Pengambilan</label>
            <input type="date" name="pickup_date" class="form-control form-control-sm" value="{{ request('pickup_date') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-primary-lala">Filter</button>
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
        </div>
    </form>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tgl Ambil</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('admin.pesanan.show', $order) }}" class="fw-bold text-decoration-none" style="color:var(--caramel);">
                            #{{ $order->order_number }}
                        </a>
                        <div class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $order->customer_name ?? $order->user?->name ?? '-' }}</div>
                        <div class="text-muted small"><i class="bi bi-whatsapp text-success me-1"></i>{{ $order->customer_phone ?? $order->user?->phone ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('d M Y') }}</div>
                        @if(\Carbon\Carbon::parse($order->pickup_date)->isToday())
                            <span class="badge bg-danger small">Hari ini</span>
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
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.pesanan.show', $order) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.pesanan.cetak', $order) }}" class="btn btn-sm btn-outline-dark" title="Cetak Nota">
                                <i class="bi bi-receipt"></i>
                            </a>
                            @if(in_array(auth()->user()->role, ['pemilik', 'staf_kasir']) && $order->status === 'siap_diambil')
                            <a href="{{ route('admin.pesanan.whatsapp', $order) }}" target="_blank" class="btn btn-sm btn-success" title="Kirim WA">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Belum ada pesanan ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $orders->links() }}
</div>
@endsection
