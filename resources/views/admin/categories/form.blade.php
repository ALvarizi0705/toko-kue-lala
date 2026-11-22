@extends('layouts.admin')
@section('title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-4">{{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h5>
            
            <form method="POST" action="{{ isset($category) ? route('admin.kategori.update', $category) : route('admin.kategori.store') }}">
                @csrf
                @if(isset($category)) @method('PUT') @endif
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $category->name ?? '') }}" placeholder="cth: Kue Ulang Tahun" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Aktif</label>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lala px-4">Simpan</button>
                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
