@extends('layouts.admin')
@section('title', 'Input Koreksi Stok')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-4"><i class="bi bi-pencil-square me-2"></i>Koreksi / Penyesuaian Stok</h5>
            
            <form method="POST" action="{{ route('admin.koreksi-stok.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Bahan Baku <span class="text-danger">*</span></label>
                    <select name="ingredient_id" class="form-select @error('ingredient_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($ingredients as $ing)
                        <option value="{{ $ing->id }}" {{ old('ingredient_id') == $ing->id ? 'selected' : '' }}>
                            {{ $ing->code }} - {{ $ing->name }} (Stok saat ini: {{ $ing->stock }} {{ $ing->unit }})
                        </option>
                        @endforeach
                    </select>
                    @error('ingredient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Selisih Stok (+ / -) <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" name="difference" class="form-control @error('difference') is-invalid @enderror"
                           value="{{ old('difference') }}" placeholder="cth: -2.5 jika rusak/hilang, atau 1.5 jika lebih" required>
                    <div class="form-text small">Masukkan angka positif untuk menambah stok fisik, angka negatif (tanda minus) untuk mengurangi.</div>
                    @error('difference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Koreksi <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                           value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Alasan Koreksi / Hasil Stock Opname <span class="text-danger">*</span></label>
                    <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" 
                              placeholder="cth: Kemasan bocor saat penyimpanan / selisih perhitungan opname fisik bulanan" required>{{ old('reason') }}</textarea>
                    @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lala px-4">
                        <i class="bi bi-check2 me-1"></i>Simpan Koreksi
                    </button>
                    <a href="{{ route('admin.koreksi-stok.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
