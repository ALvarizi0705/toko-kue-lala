@extends('layouts.storefront')
@section('title', 'Detail Pesanan #' . $order->order_number)
@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('orders.my') }}" class="text-decoration-none" style="color:var(--caramel);">Pesanan Saya</a></li>
                    <li class="breadcrumb-item active">#{{ $order->order_number }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0 text-choco">Pesanan #{{ $order->order_number }}</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('order.nota', $order) }}" class="btn btn-outline-dark">
                <i class="bi bi-printer me-1"></i>Cetak Nota
            </a>
            @if(in_array($order->status, ['menunggu_pembayaran', 'menunggu_verifikasi']))
            <a href="{{ route('order.show', $order->order_number) }}" class="btn btn-primary-lala">
                <i class="bi bi-wallet2 me-1"></i>Halaman Pembayaran & Bukti Transfer
            </a>
            @endif
        </div>
    </div>
    
    @if($order->payment_reject_reason)
    <div class="alert alert-danger shadow-sm rounded-4 mb-4">
        <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Bukti Pembayaran Ditolak</h6>
        <p class="mb-2">{{ $order->payment_reject_reason }}</p>
        <a href="{{ route('order.show', $order->order_number) }}" class="btn btn-sm btn-danger fw-bold">Unggah Ulang Bukti Transfer</a>
    </div>
    @endif
    
    <div class="row g-4">
        <div class="col-lg-8">
            {{-- ITEM PESANAN --}}
            <div class="card p-4 shadow-sm border-0 rounded-4 bg-white mb-4">
                <h5 class="fw-bold text-choco mb-3">Item Kue Dipesan</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kue</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-choco">{{ $item->product_name }}</div>
                                    @if($item->size)
                                        <div class="small text-muted">Ukuran: {{ $item->size }}</div>
                                    @endif
                                    @if($item->cake_text)
                                        <div class="small text-muted">Tulisan: "{{ $item->cake_text }}"</div>
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
                                <td colspan="3" class="text-end fw-bold">TOTAL TAGIHAN:</td>
                                <td class="text-end fw-bold fs-5" style="color:var(--caramel);">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            {{-- RIWAYAT STATUS --}}
            <div class="card p-4 shadow-sm border-0 rounded-4 bg-white">
                <h5 class="fw-bold text-choco mb-3"><i class="bi bi-clock-history me-2"></i>Riwayat Status Pesanan</h5>
                <div class="timeline">
                    @foreach($order->orderStatusLogs->sortByDesc('created_at') as $log)
                    <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                        <div class="badge-status badge-{{ $log->to_status }} mt-1">
                            {{ $order->getStatusLabelFor($log->to_status) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="small text-muted">{{ $log->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                            @if($log->note)
                                <div class="small text-dark">{{ $log->note }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card p-4 shadow-sm border-0 rounded-4 bg-white mb-4">
                <h5 class="fw-bold text-choco mb-3">Status Terkini</h5>
                <div class="mb-3 text-center py-2">
                    <span class="badge-status badge-{{ $order->status }} fs-6">
                        {{ $order->status_label }}
                    </span>
                </div>
                
                <hr>
                
                <div class="mb-2 small">
                    <span class="text-muted d-block">Tanggal Pengambilan di Toko:</span>
                    <strong class="fs-6 text-choco">{{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('l, d F Y') }}</strong>
                </div>
                <div class="mb-2 small">
                    <span class="text-muted d-block">Metode Pembayaran:</span>
                    <strong>{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? '-')) }}</strong>
                </div>
                <div class="mb-3 small">
                    <span class="text-muted d-block">Dibuat Pada:</span>
                    <span>{{ $order->created_at->translatedFormat('d F Y, H:i') }}</span>
                </div>
                
                @if($order->payment_proof)
                <div class="mt-3">
                    <span class="text-muted small d-block mb-1">Bukti Transfer Terunggah:</span>
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" 
                             class="w-100 rounded-3 border" style="max-height:180px;object-fit:cover;">
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
