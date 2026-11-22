@extends('layouts.admin')
@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-4">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' }}</h5>
            
            <form method="POST" action="{{ isset($product) ? route('admin.produk.update', $product) : route('admin.produk.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if(isset($product)) @method('PUT') @endif
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name ?? '') }}" placeholder="cth: Kue Ulang Tahun Black Forest" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori Kue <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Harga Produk (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                           value="{{ old('price', $product->price ?? '') }}" min="1" placeholder="cth: 150000" required>
                    <div class="form-text small">Harga tidak boleh 0 atau bernilai minus.</div>
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi Produk</label>
                    <textarea name="description" class="form-control" rows="3" 
                              placeholder="Deskripsi bahan, cita rasa, dan rekomendasi ukuran...">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Foto Produk</label>
                    @if(isset($product) && $product->photo)
                    <div class="mb-2">
                        <img src="{{ $product->photo_url }}" alt="Foto Produk" 
                             style="width:120px;height:90px;object-fit:cover;border-radius:8px;" class="border">
                        <div class="form-text small">Foto saat ini. Pilih berkas baru untuk mengubah foto.</div>
                    </div>
                    @endif
                    <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div class="form-text small">Format file: JPG, PNG, WEBP. Maksimal 2 MB.</div>
                    @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Aktif (Tampilkan di Katalog Publik)</label>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-lala px-4">
                        <i class="bi bi-check2 me-1"></i>Simpan
                    </button>
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
