@extends('layouts.admin')
@section('title', 'Catat Pemakaian Bahan')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-4"><i class="bi bi-box-arrow-up me-2"></i>Catat Pemakaian Bahan Baku</h5>
            
            <form method="POST" action="{{ route('admin.pemakaian-bahan.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Bahan Baku <span class="text-danger">*</span></label>
                    <select name="ingredient_id" class="form-select @error('ingredient_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($ingredients as $ing)
                        <option value="{{ $ing->id }}" {{ old('ingredient_id') == $ing->id ? 'selected' : '' }}>
                            {{ $ing->code }} - {{ $ing->name }} (Sisa Stok: {{ $ing->stock }} {{ $ing->unit }})
                        </option>
                        @endforeach
                    </select>
                    @error('ingredient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Jumlah Pemakaian <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" name="qty" class="form-control @error('qty') is-invalid @enderror"
                           value="{{ old('qty') }}" min="0.001" placeholder="cth: 2.5" required>
                    <div class="form-text small">Stok bahan akan berkurang otomatis. Pemakaian melebihi sisa stok akan ditolak sistem.</div>
                    @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Pemakaian <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                           value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Keperluan Pemakaian / Catatan</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="cth: Pembuatan 5 loyang Black Forest pesanan #LALA-xxxx">{{ old('note') }}</textarea>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lala px-4">
                        <i class="bi bi-save me-1"></i>Simpan Pemakaian
                    </button>
                    <a href="{{ route('admin.pemakaian-bahan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
