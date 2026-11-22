@extends('layouts.admin')
@section('title', 'Peringatan Stok Menipis')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Daftar Peringatan Stok Menipis</h5>
        <p class="text-muted small mb-0">Bahan baku di bawah ini telah mencapai atau berada di bawah batas minimum stok.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.barang-masuk.create') }}" class="btn btn-primary-lala">
            <i class="bi bi-plus-lg me-1"></i>Input Barang Masuk
        </a>
        <a href="{{ route('admin.bahan.index') }}" class="btn btn-outline-secondary">Semua Bahan</a>
    </div>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Bahan Baku</th>
                    <th>Stok Saat Ini</th>
                    <th>Batas Minimum</th>
                    <th>Kebutuhan Min. Restock</th>
                    <th class="text-end">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ingredients as $ing)
                <tr class="table-danger">
                    <td class="font-monospace fw-bold">{{ $ing->code }}</td>
                    <td class="fw-bold text-choco">{{ $ing->name }}</td>
                    <td class="fw-bold text-danger">{{ number_format($ing->stock, 2) }} {{ $ing->unit }}</td>
                    <td>{{ number_format($ing->min_stock, 2) }} {{ $ing->unit }}</td>
                    <td class="fw-bold text-primary">
                        + {{ number_format(max(0, $ing->min_stock - $ing->stock), 2) }} {{ $ing->unit }}
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.barang-masuk.create', ['ingredient_id' => $ing->id]) }}" class="btn btn-sm btn-success">
                            <i class="bi bi-box-arrow-in-down me-1"></i>Restock
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-success">
                        <i class="bi bi-check-circle fs-1 d-block mb-2"></i>
                        <h6 class="fw-bold">Semua Stok Bahan Baku Aman!</h6>
                        <span class="text-muted small">Tidak ada bahan yang berada di bawah batas minimum.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $ingredients->links() }}
</div>
@endsection
