@extends('layouts.storefront')
@section('title', 'Pembayaran Pesanan #' . $order->order_number)
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <span class="badge-status badge-{{ $order->status }} mb-2 fs-6">
                    {{ $order->status_label }}
                </span>
                <h2 class="fw-bold text-choco">Instruksi Pembayaran</h2>
                <p class="text-muted">Nomor Pesanan: <strong>#{{ $order->order_number }}</strong></p>
            </div>
            
            @if($order->payment_reject_reason)
            <div class="alert alert-danger shadow-sm rounded-4 mb-4">
                <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Bukti Pembayaran Ditolak</h6>
                <p class="mb-2">{{ $order->payment_reject_reason }}</p>
                <div class="small fw-semibold">Silakan periksa kembali transfer Anda dan unggah ulang bukti pembayaran yang benar di bawah ini.</div>
            </div>
            @endif
            
            <div class="card p-4 shadow-sm border-0 rounded-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Total Tagihan Yang Harus Dibayar:</span>
                    <span class="fs-3 fw-bold" style="color:var(--caramel);">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
                
                <hr>
                
                <h5 class="fw-bold text-choco mb-3">Pilihan Rekening Pembayaran</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="fw-bold text-primary mb-1"><i class="bi bi-bank me-2"></i>Transfer Bank BCA</div>
                            <div class="fs-5 fw-bold font-monospace mb-1">{{ $settings['bank_bca'] }}</div>
                            <small class="text-muted">Gunakan nomor pesanan sebagai berita transfer.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light">
                            <div class="fw-bold text-info mb-1"><i class="bi bi-wallet2 me-2"></i>DANA / GoPay</div>
                            <div class="small mb-1"><strong>DANA:</strong> {{ $settings['dana'] }}</div>
                            <div class="small"><strong>GoPay:</strong> {{ $settings['gopay'] }}</div>
                        </div>
                    </div>
                </div>
                
                @if(!empty($settings['qris_image']))
                <div class="mt-4 p-3 border rounded-3 text-center bg-light">
                    <div class="fw-bold mb-2"><i class="bi bi-qr-code-scan me-2"></i>Scan QRIS Statis Toko Kue Lala</div>
                    <img src="{{ asset('storage/' . $settings['qris_image']) }}" alt="QRIS Toko Kue Lala" 
                         style="max-width:220px;border-radius:12px;" class="shadow-sm border">
                    <p class="small text-muted mt-2 mb-0">Mendukung BCA Mobile, Mandiri, BRImo, DANA, GoPay, OVO, ShopeePay & LinkAja.</p>
                </div>
                @endif
            </div>
            
            {{-- FORM UPLOAD BUKTI TRANSFER --}}
            <div class="card p-4 shadow-sm border-0 rounded-4 bg-white">
                <h5 class="fw-bold text-choco mb-3"><i class="bi bi-cloud-arrow-up me-2"></i>Unggah Bukti Transfer</h5>
                <p class="text-muted small">Setelah mentransfer sejumlah <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>, foto/screenshot bukti transfer dan unggah di sini.</p>
                
                <form method="POST" action="{{ route('payment.upload', $order) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Berkas Foto Bukti Transfer <span class="text-danger">*</span></label>
                        <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg,image/webp" required>
                        <div class="form-text small">Format gambar: JPG, JPEG, PNG, atau WEBP. Maksimal ukuran: 2 MB.</div>
                        @error('payment_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-lala py-2 px-4 fw-bold">
                            <i class="bi bi-upload me-2"></i>Kirim Bukti Pembayaran
                        </button>
                        <a href="{{ route('order.nota', $order) }}" class="btn btn-outline-secondary py-2 px-3">
                            <i class="bi bi-receipt me-1"></i>Lihat Nota Pesanan
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
