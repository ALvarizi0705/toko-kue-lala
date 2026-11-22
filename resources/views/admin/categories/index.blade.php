@extends('layouts.admin')
@section('title', 'Kategori Produk')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-tags me-2"></i>Kategori Produk</h5>
    <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary-lala">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
    </a>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Jumlah Produk Terkait</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td class="fw-bold text-choco">{{ $category->name }}</td>
                    <td>{{ $category->products_count }} produk</td>
                    <td>
                        @if($category->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.kategori.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.kategori.destroy', $category) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus kategori ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $categories->links() }}
</div>
@endsection
