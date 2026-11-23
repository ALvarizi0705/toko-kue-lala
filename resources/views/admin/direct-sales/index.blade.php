@extends('layouts.admin')
@section('title', 'Penjualan Langsung Kasir')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-cash-coin me-2"></i>Penjualan Langsung di Toko (POS)</h5>
        <p class="text-muted small mb-0">Catat transaksi pembelian kue langsung oleh pelanggan yang datang ke toko</p>
    </div>
    <a href="{{ route('admin.penjualan-langsung.create') }}" class="btn btn-primary-lala">
        <i class="bi bi-plus-lg me-1"></i>Transaksi Baru
    </a>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Waktu Transaksi</th>
                    <th>Metode Pembayaran</th>
                    <th>Total Belanja</th>
                    <th>Kasir</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr>
                    <td>
                        <a href="{{ route('admin.penjualan-langsung.show', $sale) }}" class="fw-bold text-decoration-none" style="color:var(--caramel);">
                            #{{ $sale->sale_number }}
                        </a>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($sale->sold_at)->translatedFormat('d M Y, H:i') }} WIB</td>
                    <td>
                        <span class="badge bg-secondary text-uppercase">{{ $sale->payment_method }}</span>
                    </td>
                    <td class="fw-bold" style="color:var(--caramel);">
                        Rp {{ number_format($sale->total, 0, ',', '.') }}
                    </td>
                    <td>{{ $sale->user->name ?? '-' }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.penjualan-langsung.show', $sale) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.penjualan-langsung.cetak', ['penjualan_langsung' => $sale->id, 'format' => 'a5']) }}" class="btn btn-sm btn-outline-dark" title="Cetak Nota A5">
                                <i class="bi bi-printer"></i>
                            </a>
                            <a href="{{ route('admin.penjualan-langsung.cetak', ['penjualan_langsung' => $sale->id, 'format' => '58mm']) }}" class="btn btn-sm btn-outline-primary" title="Cetak Struk 58mm">
                                <i class="bi bi-receipt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi penjualan langsung.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $sales->links() }}
</div>
@endsection
