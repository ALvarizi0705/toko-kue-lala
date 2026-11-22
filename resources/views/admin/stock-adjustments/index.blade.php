@extends('layouts.admin')
@section('title', 'Koreksi Stok Bahan')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-pencil-square me-2"></i>Koreksi / Penyesuaian Stok (Stock Opname)</h5>
        <p class="text-muted small mb-0">Catat selisih fisik bahan baku dengan alasan yang jelas</p>
    </div>
    <a href="{{ route('admin.koreksi-stok.create') }}" class="btn btn-primary-lala">
        <i class="bi bi-plus-lg me-1"></i>Input Koreksi Stok
    </a>
</div>

<div class="table-lala bg-white rounded-4 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Bahan</th>
                    <th>Selisih Stok</th>
                    <th>Alasan Koreksi</th>
                    <th>Petugas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adjustments as $adj)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($adj->date)->translatedFormat('d F Y') }}</td>
                    <td class="fw-bold text-choco">{{ $adj->ingredient->name ?? '-' }}</td>
                    <td class="fw-bold {{ $adj->difference >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $adj->difference >= 0 ? '+' : '' }}{{ number_format($adj->difference, 2) }} {{ $adj->ingredient->unit ?? '' }}
                    </td>
                    <td>{{ $adj->reason }}</td>
                    <td class="small">{{ $adj->user->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat koreksi stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $adjustments->links() }}
</div>
@endsection
