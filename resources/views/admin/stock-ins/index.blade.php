@extends('layouts.admin')
@section('title', 'Riwayat Barang Masuk')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-box-arrow-in-down me-2"></i>Riwayat Barang Masuk</h5>
        <p class="text-muted small mb-0">Pencatatan pembelian dan pasokan bahan baku dari supplier</p>
    </div>
    <a href="{{ route('admin.barang-masuk.create') }}" class="btn btn-primary-lala">
        <i class="bi bi-plus-lg me-1"></i>Catat Barang Masuk
    </a>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Bahan</th>
                    <th>Jumlah Masuk</th>
                    <th>Pemasok / Supplier</th>
                    <th>Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockIns as $in)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($in->date)->translatedFormat('d F Y') }}</td>
                    <td class="fw-bold text-choco">{{ $in->ingredient->name ?? '-' }}</td>
                    <td class="fw-bold text-success">+ {{ number_format($in->qty, 2) }} {{ $in->ingredient->unit ?? '' }}</td>
                    <td>{{ $in->supplier ?? '-' }}</td>
                    <td class="small">{{ $in->user->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat barang masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $stockIns->links() }}
</div>
@endsection
