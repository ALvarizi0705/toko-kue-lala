@extends('layouts.storefront')
@section('title', 'Profil Saya')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4 text-choco"><i class="bi bi-person-gear me-2" style="color:var(--caramel);"></i>Pengaturan Profil Akun</h2>
            
            {{-- DATA DIRI --}}
            <div class="card p-4 shadow-sm border-0 rounded-4 bg-white mb-4">
                <h5 class="fw-bold text-choco mb-3">Informasi Kontak & Akun</h5>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PATCH')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor WhatsApp</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary-lala">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan Profil
                    </button>
                </form>
            </div>
            
            {{-- GANTI KATA SANDI --}}
            <div class="card p-4 shadow-sm border-0 rounded-4 bg-white">
                <h5 class="fw-bold text-choco mb-3">Ganti Kata Sandi</h5>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PATCH')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kata Sandi Baru (Min. 8 Karakter) <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Ulangi Kata Sandi Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-outline-dark">
                        <i class="bi bi-key me-1"></i>Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
