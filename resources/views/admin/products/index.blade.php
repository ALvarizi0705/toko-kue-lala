@extends('layouts.admin')
@section('title', 'Kelola Produk')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-box-seam me-2"></i>Daftar Produk</h5>
    <a href="{{ route('admin.produk.create') }}" class="btn btn-primary-lala">
        <i class="bi bi-plus-lg me-1"></i>Tambah Produk Baru
    </a>
</div>

{{-- FILTER --}}
<div class="form-card mb-4 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-bold">Cari Produk</label>
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Nama kue..." value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold">Kategori</label>
            <select name="kategori" class="form-select form-select-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-primary-lala">Filter</button>
            <a href="{{ route('admin.produk.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
        </div>
    </form>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:60px;">Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga (Rp)</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <img src="{{ $product->photo_url }}" alt="{{ $product->name }}"
                             style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                    </td>
                    <td class="fw-bold text-choco">{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td class="fw-bold" style="color:var(--caramel);">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        @if($product->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.produk.edit', $product) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.produk.toggle', $product) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $product->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                        title="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="bi bi-{{ $product->is_active ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.produk.destroy', $product) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus produk ini secara permanen?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $products->links() }}
</div>
@endsection
