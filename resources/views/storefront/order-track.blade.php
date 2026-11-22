@extends('layouts.storefront')
@section('title', 'Lacak Status Pesanan')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mb-4">
                <span class="fs-1">🔍</span>
                <h3 class="fw-bold text-choco mt-2">Lacak Pesanan Kue</h3>
                <p class="text-muted small">Cek progres pesananmu secara real-time. Cukup masukkan Nomor Pesanan atau Nomor WhatsApp yang kamu gunakan saat memesan.</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                <form method="POST" action="{{ route('order.track.lookup') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor Pesanan atau No. WhatsApp</label>
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" 
                                   placeholder="Contoh: LALA-20261106-0001 atau 081234567890" 
                                   value="{{ old('q', $query ?? '') }}" required>
                            <button class="btn btn-primary-lala" type="submit">
                                <i class="bi bi-search me-1"></i>Lacak
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if(isset($orders) && $orders->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                <h6 class="fw-bold text-choco mb-3">Hasil Pelacakan Pesanan:</h6>
                <div class="list-group list-group-flush">
                    @foreach($orders as $item)
                    <a href="{{ route('order.show', $item->order_number) }}" class="list-group-item list-group-item-action px-2 py-3 rounded-3 mb-2 border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-choco">#{{ $item->order_number }}</span>
                            <span class="badge-status badge-{{ $item->status }}">{{ $item->status_label }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small text-muted">
                            <span>Ambil: {{ \Carbon\Carbon::parse($item->pickup_date)->translatedFormat('d M Y') }}</span>
                            <span class="fw-bold" style="color:var(--caramel);">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @elseif(isset($query) && $query)
            <div class="alert alert-warning text-center rounded-4">
                <i class="bi bi-exclamation-circle me-1"></i>Tidak ditemukan pesanan dengan kata kunci "<strong>{{ $query }}</strong>". Pastikan nomor pesanan atau nomor WhatsApp sudah benar.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
