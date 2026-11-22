@extends('layouts.admin')
@section('title', 'Catat Barang Masuk')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-4"><i class="bi bi-box-arrow-in-down me-2"></i>Catat Barang Masuk</h5>
            
            <form method="POST" action="{{ route('admin.barang-masuk.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Bahan Baku <span class="text-danger">*</span></label>
                    <select name="ingredient_id" class="form-select @error('ingredient_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($ingredients as $ing)
                        <option value="{{ $ing->id }}" {{ old('ingredient_id', request('ingredient_id')) == $ing->id ? 'selected' : '' }}>
                            {{ $ing->code }} - {{ $ing->name }} (Stok saat ini: {{ $ing->stock }} {{ $ing->unit }})
                        </option>
                        @endforeach
                    </select>
                    @error('ingredient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Jumlah Masuk <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" name="qty" class="form-control @error('qty') is-invalid @enderror"
                           value="{{ old('qty') }}" min="0.001" placeholder="cth: 10" required>
                    <div class="form-text small">Stok bahan akan bertambah secara otomatis.</div>
                    @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                           value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Nama Pemasok / Toko Pembelian (Opsional)</label>
                    <input type="text" name="supplier" class="form-control" value="{{ old('supplier') }}"
                           placeholder="cth: Toko Bahan Kue Sejahtera / Tokopedia">
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lala px-4">
                        <i class="bi bi-save me-1"></i>Simpan Barang Masuk
                    </button>
                    <a href="{{ route('admin.barang-masuk.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
