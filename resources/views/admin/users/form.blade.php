@extends('layouts.admin')
@section('title', isset($user) ? 'Edit Akun Pengguna' : 'Tambah Akun Pengguna')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-4">{{ isset($user) ? 'Edit Akun Pengguna' : 'Buat Akun Baru' }}</h5>
            
            <form method="POST" action="{{ isset($user) ? route('admin.akun.update', $user) : route('admin.akun.store') }}">
                @csrf
                @if(isset($user)) @method('PUT') @endif
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name ?? '') }}" placeholder="cth: Lala Komalasari" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nomor WhatsApp <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $user->phone ?? '') }}" placeholder="cth: 08123456789" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email ?? '') }}" placeholder="cth: user@tokokuelalala.id" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Role Hak Akses <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="pelanggan" {{ old('role', $user->role ?? '') === 'pelanggan' ? 'selected' : '' }}>Pelanggan (Storefront & Pesanan Sendiri)</option>
                        <option value="staf_kasir" {{ old('role', $user->role ?? '') === 'staf_kasir' ? 'selected' : '' }}>Staf Kasir (Verifikasi Bayar, POS, Pesanan)</option>
                        <option value="staf_produksi" {{ old('role', $user->role ?? '') === 'staf_produksi' ? 'selected' : '' }}>Staf Produksi (Jadwal Dapur, Stok Bahan)</option>
                        <option value="pemilik" {{ old('role', $user->role ?? '') === 'pemilik' ? 'selected' : '' }}>Pemilik (Akses Penuh, Laporan, Pengaturan)</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ isset($user) ? 'Ganti Kata Sandi (Kosongkan jika tidak ingin diubah)' : 'Kata Sandi Awal' }} {{ isset($user) ? '' : '*' }}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimal 8 karakter" {{ isset($user) ? '' : 'required' }}>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Akun Aktif (Dapat Login ke Sistem)</label>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lala px-4">Simpan</button>
                    <a href="{{ route('admin.akun.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
