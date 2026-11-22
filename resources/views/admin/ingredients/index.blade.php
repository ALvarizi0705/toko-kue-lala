@extends('layouts.admin')
@section('title', 'Master Bahan Baku')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-archive me-2"></i>Master Bahan Baku</h5>
        <p class="text-muted small mb-0">Kelola inventori bahan baku pembuatan kue</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.bahan.warnings') }}" class="btn btn-outline-danger">
            <i class="bi bi-exclamation-triangle me-1"></i>Peringatan Stok
        </a>
        <a href="{{ route('admin.bahan.create') }}" class="btn btn-primary-lala">
            <i class="bi bi-plus-lg me-1"></i>Tambah Bahan Baru
        </a>
    </div>
</div>

<div class="form-card mb-4 p-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari kode atau nama bahan baku..." value="{{ request('q') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-primary-lala">Cari</button>
            <a href="{{ route('admin.bahan.index') }}" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
        </div>
    </form>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Bahan</th>
                    <th>Satuan</th>
                    <th>Stok Saat Ini</th>
                    <th>Batas Minimum</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ingredients as $ing)
                <tr class="{{ $ing->isLowStock() ? 'table-danger' : '' }}">
                    <td class="font-monospace fw-bold">{{ $ing->code }}</td>
                    <td class="fw-bold text-choco">{{ $ing->name }}</td>
                    <td>{{ $ing->unit }}</td>
                    <td class="fw-bold {{ $ing->isLowStock() ? 'text-danger' : 'text-success' }}">
                        {{ number_format($ing->stock, 2) }}
                    </td>
                    <td>{{ number_format($ing->min_stock, 2) }}</td>
                    <td>
                        @if($ing->isLowStock())
                            <span class="badge bg-danger">Stok Menipis</span>
                        @else
                            <span class="badge bg-success">Aman</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.bahan.edit', $ing) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.bahan.destroy', $ing) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus master bahan baku ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data bahan baku.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $ingredients->links() }}
</div>
@endsection
