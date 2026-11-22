@extends('layouts.storefront')
@section('title', 'Nota Pesanan #' . $order->order_number)
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="{{ route('order.show', $order->order_number) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Status Pesanan
        </a>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-dark btn-sm">
                <i class="bi bi-printer me-1"></i>Cetak / Simpan PDF
            </button>
        </div>
    </div>
    
    {{-- NOTA CONTAINER --}}
    <div class="nota-a4 p-4 p-md-5 bg-white border rounded-4 shadow-sm mx-auto" style="max-width:750px;">
        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-choco">🎂 Toko Kue Lala</h3>
                <p class="text-muted small mb-0">Jl. Matraman Dalam III No. 26B, Jakarta Timur</p>
                <p class="text-muted small mb-0">WhatsApp: {{ \App\Models\Setting::get('phone', '08123456789') }}</p>
            </div>
            <div class="text-end">
                <h5 class="fw-bold text-choco mb-1">NOTA PEMESANAN</h5>
                <div class="fs-6 fw-bold font-monospace text-primary">#{{ $order->order_number }}</div>
                <div class="small text-muted">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
            </div>
        </div>
        
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="small text-muted">Pemesan:</div>
                <div class="fw-bold text-choco">{{ $order->customer_name ?? $order->user?->name ?? 'Pelanggan' }}</div>
                <div class="small text-muted">{{ $order->customer_phone ?? $order->user?->phone ?? '-' }}</div>
                @if($order->customer_address)
                <div class="small text-muted">{{ $order->customer_address }}</div>
                @endif
            </div>
            <div class="col-6 text-end">
                <div class="small text-muted">Jadwal Pengambilan di Toko:</div>
                <div class="fw-bold text-choco fs-6">{{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('l, d F Y') }}</div>
                <div>
                    <span class="badge-status badge-{{ $order->status }}">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="table-responsive mb-4">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Item Kue & Kustomisasi</th>
                        <th class="text-center" style="width:70px;">Qty</th>
                        <th class="text-end" style="width:120px;">Harga Satuan</th>
                        <th class="text-end" style="width:130px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            <div class="fw-bold text-choco">{{ $item->product_name }}</div>
                            @if($item->size)
                                <div class="small text-muted">Ukuran: <strong>{{ $item->size }}</strong></div>
                            @endif
                            @if($item->cake_text)
                                <div class="small text-muted">Tulisan: <em>"{{ $item->cake_text }}"</em></div>
                            @endif
                            @if($item->custom_note)
                                <div class="small text-muted">Catatan: {{ $item->custom_note }}</div>
                            @endif
                        </td>
                        <td class="text-center fw-bold">{{ $item->qty }}</td>
                        <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold fs-6">TOTAL TAGIHAN:</td>
                        <td class="text-end fw-bold fs-5" style="color:var(--caramel);">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        @if($order->note)
        <div class="p-3 bg-light rounded-3 mb-4">
            <div class="small fw-bold text-muted mb-1">Catatan Tambahan Pesanan:</div>
            <div class="small">{{ $order->note }}</div>
        </div>
        @endif
        
        <div class="border-top pt-4 text-center text-muted small">
            <p class="mb-1">Terima kasih telah memesan kue di <strong>Toko Kue Lala</strong>!</p>
            <p class="mb-0">Tunjukkan nomor nota ini kepada kasir saat mengambil pesanan.</p>
        </div>
    </div>
</div>
@endsection
