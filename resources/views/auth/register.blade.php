@extends('layouts.storefront')
@section('title', 'Daftar Akun Baru')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <div style="font-size:3.5rem;">🎂</div>
                <h2 class="mt-2 text-choco fw-700">Daftar Akun Baru</h2>
                <p class="text-muted">Buat akun untuk memesan kue segar buatan rumahan</p>
            </div>
            <div class="card p-4 shadow-sm border-0 rounded-4 bg-white">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="cth: Siti Rahma" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Nomor WhatsApp Aktif</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}" placeholder="cth: 08123456789" required>
                        <div class="form-text small">Digunakan untuk konfirmasi pesanan dan pemberitahuan pengambilan kue.</div>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="cth: siti@email.com" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Kata Sandi</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Minimal 8 karakter" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-600">Ulangi Kata Sandi</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Ketik ulang kata sandi" required>
                    </div>
                    <button type="submit" class="btn btn-primary-lala w-100 py-2">
                        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                    </button>
                </form>
                <hr class="my-4">
                <p class="text-center small mb-0">Sudah punya akun? 
                    <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color:var(--caramel);">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
