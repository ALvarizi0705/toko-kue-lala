@extends('layouts.admin')
@section('title', 'Pengaturan Toko')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-4"><i class="bi bi-gear me-2"></i>Pengaturan Informasi & Pembayaran Toko</h5>
            
            <form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data">
                @csrf
                
                <h6 class="fw-bold text-muted text-uppercase mb-3" style="letter-spacing:1px;font-size:0.8rem;">Profil Toko</h6>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Toko <span class="text-danger">*</span></label>
                    <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name', $settings['shop_name']) }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Toko <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="2" required>{{ old('address', $settings['address']) }}</textarea>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nomor WhatsApp Resmi Toko <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings['phone']) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jam Operasional / Buka <span class="text-danger">*</span></label>
                        <input type="text" name="pickup_hours" class="form-control" value="{{ old('pickup_hours', $settings['pickup_hours']) }}" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Minimal Hari Pemesanan Sebelum Pengambilan (H+n) <span class="text-danger">*</span></label>
                    <input type="number" name="min_order_days" class="form-control" value="{{ old('min_order_days', $settings['min_order_days']) }}" min="0" max="30" required>
                    <div class="form-text small">cth: Jika diset 1, pelanggan hanya bisa memilih tanggal ambil mulai besok (H+1).</div>
                </div>
                
                <hr class="my-4">
                <h6 class="fw-bold text-muted text-uppercase mb-3" style="letter-spacing:1px;font-size:0.8rem;">Rekening Pembayaran Pelanggan</h6>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nomor Rekening Bank BCA</label>
                    <input type="text" name="bank_bca" class="form-control" value="{{ old('bank_bca', $settings['bank_bca']) }}" placeholder="1234567890 (a/n Lala Komalasari)">
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Akun DANA</label>
                        <input type="text" name="dana" class="form-control" value="{{ old('dana', $settings['dana']) }}" placeholder="08123456789 (Toko Kue Lala)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Akun GoPay</label>
                        <input type="text" name="gopay" class="form-control" value="{{ old('gopay', $settings['gopay']) }}" placeholder="08123456789 (Toko Kue Lala)">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Gambar QRIS Statis Toko</label>
                    @if(!empty($settings['qris_image']))
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $settings['qris_image']) }}" alt="QRIS Toko" 
                             style="max-width:180px;border-radius:10px;" class="border p-1 bg-light">
                        <div class="form-text small">Gambar QRIS saat ini. Pilih berkas baru untuk mengganti gambar.</div>
                    </div>
                    @endif
                    <input type="file" name="qris_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div class="form-text small">Unggah barcode QRIS toko beresolusi jelas (JPG/PNG).</div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lala px-4">
                        <i class="bi bi-save me-1"></i>Simpan Pengaturan Toko
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
