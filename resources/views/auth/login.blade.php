@extends('layouts.storefront')
@section('title', 'Masuk')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <div style="font-size:3.5rem;">🎂</div>
                <h2 class="mt-2 text-choco fw-700">Masuk ke Toko Kue Lala</h2>
                <p class="text-muted">Gunakan alamat email atau nomor WhatsApp Anda</p>
            </div>
            <div class="card p-4 shadow-sm border-0 rounded-4 bg-white">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600">Email atau Nomor WhatsApp</label>
                        <input type="text" name="login" class="form-control @error('login') is-invalid @enderror"
                               value="{{ old('login') }}" placeholder="contoh@email.com atau 08123456789" required autofocus>
                        @error('login')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Kata Sandi</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Masukkan kata sandi" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Ingat saya</label>
                        </div>
                        <a href="javascript:void(0)" onclick="alert('Lupa kata sandi? Silakan hubungi admin toko via WhatsApp di 08123456789 untuk reset kata sandi.')" class="small text-decoration-none" style="color:var(--caramel);">
                            Lupa kata sandi?
                        </a>
                    </div>
                    <button type="submit" class="btn btn-primary-lala w-100 py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>
                <hr class="my-4">
                <p class="text-center small mb-0">Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color:var(--caramel);">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
