@extends('layouts.admin')
@section('title', 'Verifikasi Pembayaran')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-shield-check me-2"></i>Verifikasi Bukti Pembayaran Masuk</h5>
        <p class="text-muted small mb-0">Periksa bukti transfer pelanggan sebelum pesanan diproses oleh tim produksi</p>
    </div>
</div>

@if($orders->isEmpty())
    <div class="form-card text-center py-5">
        <i class="bi bi-check2-circle fs-1 text-success d-block mb-2"></i>
        <h5 class="fw-bold">Semua Bukti Pembayaran Sudah Diverifikasi</h5>
        <p class="text-muted small mb-0">Tidak ada pesanan yang sedang menunggu verifikasi saat ini.</p>
    </div>
@else
    <div class="row g-4">
        @foreach($orders as $order)
        <div class="col-lg-6">
            <div class="form-card h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-choco">Pesanan #{{ $order->order_number }}</h6>
                        <span class="small text-muted">{{ $order->created_at->format('d/m/Y H:i') }} WIB</span>
                    </div>
                    <span class="fs-5 fw-bold" style="color:var(--caramel);">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-sm-6 small">
                        <div class="text-muted">Pelanggan:</div>
                        <div class="fw-bold">{{ $order->customer_name ?? $order->user?->name ?? 'Pelanggan' }}</div>
                        <div>{{ $order->customer_phone ?? $order->user?->phone ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6 small">
                        <div class="text-muted">Metode Bayar:</div>
                        <strong class="text-uppercase">{{ str_replace('_', ' ', $order->payment_method ?? '-') }}</strong>
                        <div class="text-muted mt-1">Tgl Ambil: {{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('d M Y') }}</div>
                    </div>
                </div>
                
                {{-- GAMBAR BUKTI TRANSFER --}}
                <div class="mb-3 text-center bg-light p-2 rounded-3 border">
                    @if($order->payment_proof)
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" title="Klik untuk memperbesar">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" 
                             class="rounded img-fluid" style="max-height:220px;object-fit:contain;">
                    </a>
                    <div class="small text-muted mt-1"><i class="bi bi-zoom-in me-1"></i>Klik gambar untuk melihat ukuran penuh</div>
                    @else
                    <div class="py-4 text-muted small">Belum ada file bukti pembayaran terunggah.</div>
                    @endif
                </div>
                
                {{-- TOMBOL TERIMA / TOLAK --}}
                <div class="mt-auto pt-3 border-top d-flex gap-2">
                    <form method="POST" action="{{ route('admin.verifikasi.terima', $order) }}" class="flex-grow-1">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100 fw-bold" onclick="return confirm('Verifikasi dan terima pembayaran ini?')">
                            <i class="bi bi-check-lg me-1"></i>Terima (Diproses)
                        </button>
                    </form>
                    
                    <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#rejectForm{{ $order->id }}">
                        <i class="bi bi-x-lg me-1"></i>Tolak
                    </button>
                </div>
                
                {{-- FORM TOLAK (Wajib isi alasan) --}}
                <div class="collapse mt-3" id="rejectForm{{ $order->id }}">
                    <form method="POST" action="{{ route('admin.verifikasi.tolak', $order) }}" class="p-3 bg-light rounded-3 border border-danger">
                        @csrf @method('PATCH')
                        <label class="form-label small fw-bold text-danger">Alasan Penolakan Bukti Pembayaran <span class="text-danger">*</span></label>
                        <textarea name="payment_reject_reason" class="form-control form-control-sm mb-2" rows="2" 
                                  placeholder="cth: Nominal transfer tidak sesuai tagihan / struk tidak terbaca" required></textarea>
                        <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Tolak bukti pembayaran ini?')">
                            Kirim Penolakan ke Pelanggan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endif

@endsection
