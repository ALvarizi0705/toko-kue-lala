@extends('layouts.storefront')
@section('title', 'Checkout Pemesanan')
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('cart.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Keranjang
        </a>
        <h3 class="fw-bold mb-0 text-choco">Checkout Pemesanan</h3>
    </div>
    <p class="text-muted small mb-4">
        Lengkapi data pemesan. <strong>Semua pesanan diambil langsung di toko</strong> (tidak melayani pengantaran). Pembayaran dikonfirmasi via WhatsApp.
    </p>
    
    <form method="POST" action="{{ route('order.store') }}">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7">
                {{-- DATA PEMESAN (TANPA PERLU LOGIN) --}}
                <div class="card p-3 p-md-4 shadow-sm border-0 rounded-4 bg-white mb-4">
                    <h5 class="fw-bold mb-3 text-choco">
                        <i class="bi bi-person-circle me-2" style="color:var(--caramel);"></i>1. Data Pemesan
                    </h5>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control form-control-sm @error('customer_name') is-invalid @enderror" 
                               value="{{ old('customer_name', auth()->user()?->name) }}" placeholder="Contoh: Budi Santoso" required>
                        @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-success"></i></span>
                            <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                   value="{{ old('customer_phone', auth()->user()?->phone) }}" placeholder="Contoh: 081234567890" required>
                        </div>
                        <div class="form-text small">Nomor WhatsApp ini digunakan toko untuk mengonfirmasi pesanan dan pembayaran.</div>
                        @error('customer_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    {{-- INFO AMBIL SENDIRI DI TOKO --}}
                    <div class="p-3 rounded-3 border" style="background:#fffaf3;border-color:var(--butter) !important;">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-shop text-choco fs-5 mt-1"></i>
                            <div>
                                <div class="fw-bold small text-choco">Pengambilan: Ambil Sendiri di Toko (Self-Pickup)</div>
                                <div class="text-muted small" style="font-size:0.8rem;">
                                    📍 <strong>Toko Kue Lala</strong>: Jl. Matraman Dalam III No. 26B, Jakarta Timur<br>
                                    ⏰ Jam Buka: Senin – Sabtu, 08.00 – 20.00 WIB
                                </div>
                                <div class="text-muted small mt-1" style="font-size:0.75rem;">
                                    *Toko tidak melayani sistem antar/kirim. Pesanan diambil langsung oleh pemesan.
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="customer_address" value="Ambil Sendiri di Toko (Jl. Matraman Dalam III No. 26B, Jakarta Timur)">
                    </div>
                </div>

                {{-- JADWAL PENGAMBILAN & CATATAN --}}
                <div class="card p-3 p-md-4 shadow-sm border-0 rounded-4 bg-white mb-4">
                    <h5 class="fw-bold mb-3 text-choco">
                        <i class="bi bi-calendar-event me-2" style="color:var(--caramel);"></i>2. Jadwal Pengambilan di Toko
                    </h5>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tanggal Pengambilan di Toko <span class="text-danger">*</span></label>
                        @php
                            $minPickupDate = now()->addDays($minDays)->format('Y-m-d');
                        @endphp
                        <input type="date" name="pickup_date" id="pickup_date" 
                               min="{{ $minPickupDate }}" 
                               value="{{ old('pickup_date', $minPickupDate) }}" 
                               class="form-control form-control-sm @error('pickup_date') is-invalid @enderror" required>
                        <div class="form-text small">
                            <i class="bi bi-info-circle me-1"></i>Pemesanan minimal H+{{ $minDays }} hari sebelumnya agar kue siap segar (paling cepat: <strong>{{ \Carbon\Carbon::parse($minPickupDate)->translatedFormat('l, d F Y') }}</strong>).
                        </div>
                        @error('pickup_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                        @error('pickup_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Catatan Umum Pesanan (Opsional)</label>
                        <textarea name="note" class="form-control form-control-sm" rows="2" 
                                  placeholder="Tambahan pesan untuk pesanan ini...">{{ old('note') }}</textarea>
                    </div>
                </div>

                {{-- KUSTOMISASI KUE (TULISAN & CATATAN KHUSUS, TANPA UKURAN) --}}
                <div class="card p-3 p-md-4 shadow-sm border-0 rounded-4 bg-white mb-4">
                    <h5 class="fw-bold mb-2 text-choco">
                        <i class="bi bi-pencil-square me-2" style="color:var(--caramel);"></i>3. Kustomisasi Kue (Opsional)
                    </h5>
                    <p class="text-muted small mb-3">Tuliskan pesan ucapan pada kue atau request khusus (jika diperlukan).</p>
                    
                    @foreach($cart as $rowId => $item)
                    <div class="p-3 mb-2 rounded-3 bg-light border">
                        <div class="fw-bold text-choco small mb-2 d-flex justify-content-between">
                            <span>🎂 {{ $item['name'] }}</span>
                            <span class="badge bg-secondary">{{ $item['qty'] }} pcs</span>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label text-muted" style="font-size:0.75rem;">Tulisan di Kue (Opsional)</label>
                                <input type="text" name="cake_text_{{ $item['product_id'] }}" class="form-control form-control-sm"
                                       placeholder="cth: Selamat Ulang Tahun Ayah">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted" style="font-size:0.75rem;">Catatan Khusus (Opsional)</label>
                                <input type="text" name="custom_note_{{ $item['product_id'] }}" class="form-control form-control-sm"
                                       placeholder="cth: Lilin angka 50, jangan terlalu manis">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            {{-- RINGKASAN & PEMBAYARAN --}}
            <div class="col-lg-5">
                <div class="card p-3 p-md-4 shadow-sm border-0 rounded-4 bg-white sticky-top" style="top:90px;">
                    <h5 class="fw-bold mb-3 text-choco">Ringkasan Pesanan</h5>
                    
                    <div class="list-group list-group-flush mb-3">
                        @foreach($cart as $item)
                        <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold small text-choco">{{ $item['name'] }}</div>
                                <div class="text-muted small">{{ $item['qty'] }} × Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                            </div>
                            <span class="fw-bold small">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold">Total Pembayaran:</span>
                        <span class="fs-5 fw-bold" style="color:var(--caramel);">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    {{-- INFO PEMBAYARAN VIA WA --}}
                    <div class="p-3 rounded-3 mb-3" style="background:#e8f5e9;border:1px solid #c8e6c9;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-whatsapp text-success fs-5"></i>
                            <strong class="text-success small">Pembayaran via WhatsApp</strong>
                        </div>
                        <p class="small text-muted mb-0" style="font-size:0.8rem;">
                            Setelah klik tombol di bawah, pesananmu akan langsung tercatat dan kamu bisa langsung konfirmasi ke WhatsApp Toko Kue Lala. Penjual juga akan menghubungimu untuk transfer / pembayaran.
                        </p>
                    </div>
                    
                    <button type="submit" class="btn btn-primary-lala w-100 py-2">
                        <i class="bi bi-check2-circle me-1"></i>Buat Pesanan Sekarang
                    </button>
                    
                    <div class="text-center mt-2">
                        <span class="text-muted" style="font-size:0.75rem;">
                            <i class="bi bi-shield-check me-1"></i>Data aman, 100% tercatat dan dapat dilacak kapan saja
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
