@extends('layouts.admin')
@section('title', 'Detail Penjualan #' . $sale->sale_number)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.penjualan-langsung.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <h4 class="fw-bold mb-0 text-choco">Transaksi #{{ $sale->sale_number }}</h4>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.penjualan-langsung.cetak', $sale) }}?format=a5" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-printer me-1"></i>Cetak Nota A5
        </a>
        <a href="{{ route('admin.penjualan-langsung.cetak', $sale) }}?format=58mm" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-receipt me-1"></i>Cetak Struk 58mm
        </a>
        <a href="{{ route('admin.penjualan-langsung.create') }}" class="btn btn-primary-lala btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Transaksi Baru
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-3">Item Kue Terjual</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th class="text-center" style="width:70px;">Qty</th>
                            <th class="text-end" style="width:130px;">Harga Satuan</th>
                            <th class="text-end" style="width:140px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->directSaleItems as $item)
                        <tr>
                            <td class="fw-bold text-choco">{{ $item->product_name }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">TOTAL BELANJA:</th>
                            <th class="text-end fs-5" style="color:var(--caramel);">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-3">Informasi Transaksi</h5>
            <div class="small mb-2">
                <span class="text-muted d-block">Waktu Transaksi:</span>
                <strong>{{ \Carbon\Carbon::parse($sale->sold_at)->translatedFormat('l, d F Y, H:i') }} WIB</strong>
            </div>
            <div class="small mb-2">
                <span class="text-muted d-block">Metode Pembayaran:</span>
                <span class="badge bg-secondary text-uppercase">{{ $sale->payment_method }}</span>
            </div>
            <div class="small mb-3">
                <span class="text-muted d-block">Petugas Kasir:</span>
                <strong>{{ $sale->user->name ?? '-' }}</strong>
            </div>
            
            <hr>
            <div class="text-center py-2">
                <span class="badge bg-success fs-6"><i class="bi bi-check2-circle me-1"></i>Lunas / Selesai</span>
            </div>
        </div>
    </div>
</div>

@endsection
