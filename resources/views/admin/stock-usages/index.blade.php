@extends('layouts.admin')
@section('title', 'Pemakaian Bahan Baku')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-box-arrow-up me-2"></i>Catatan Pemakaian Bahan</h5>
        <p class="text-muted small mb-0">Catatan pemakaian bahan dalam proses memanggang dan produksi kue</p>
    </div>
    <a href="{{ route('admin.pemakaian-bahan.create') }}" class="btn btn-primary-lala">
        <i class="bi bi-plus-lg me-1"></i>Catat Pemakaian
    </a>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Bahan</th>
                    <th>Jumlah Pemakaian</th>
                    <th>Keperluan / Catatan</th>
                    <th>Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockUsages as $usage)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($usage->date)->translatedFormat('d F Y') }}</td>
                    <td class="fw-bold text-choco">{{ $usage->ingredient->name ?? '-' }}</td>
                    <td class="fw-bold text-danger">- {{ number_format($usage->qty, 2) }} {{ $usage->ingredient->unit ?? '' }}</td>
                    <td>{{ $usage->note ?? '-' }}</td>
                    <td class="small">{{ $usage->user->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat pemakaian bahan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $stockUsages->links() }}
</div>
@endsection
