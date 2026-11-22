@extends('layouts.admin')
@section('title', isset($ingredient) ? 'Edit Bahan Baku' : 'Tambah Bahan Baku')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-4">{{ isset($ingredient) ? 'Edit Bahan Baku' : 'Tambah Bahan Baku Baru' }}</h5>
            
            <form method="POST" action="{{ isset($ingredient) ? route('admin.bahan.update', $ingredient) : route('admin.bahan.store') }}">
                @csrf
                @if(isset($ingredient)) @method('PUT') @endif
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Kode Bahan (Unik) <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $ingredient->code ?? '') }}" placeholder="cth: BHN-TRG" required>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Bahan Baku <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $ingredient->name ?? '') }}" placeholder="cth: Tepung Terigu Segitiga Biru" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Satuan Ukuran <span class="text-danger">*</span></label>
                    <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror"
                           value="{{ old('unit', $ingredient->unit ?? '') }}" placeholder="cth: kg, gram, butir, liter" required>
                    @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                @if(!isset($ingredient))
                <div class="mb-3">
                    <label class="form-label fw-bold">Stok Awal <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" name="stock" class="form-control @error('stock') is-invalid @enderror"
                           value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @endif
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Batas Minimum Peringatan Stok <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" name="min_stock" class="form-control @error('min_stock') is-invalid @enderror"
                           value="{{ old('min_stock', $ingredient->min_stock ?? 5) }}" min="0" required>
                    <div class="form-text small">Jika stok sama dengan atau di bawah angka ini, sistem akan memunculkan peringatan.</div>
                    @error('min_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lala px-4">Simpan</button>
                    <a href="{{ route('admin.bahan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
