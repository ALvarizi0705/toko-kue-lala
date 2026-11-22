@extends('layouts.admin')
@section('title', 'Jadwal Produksi Dapur')
@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-calendar-check me-2"></i>Jadwal Produksi Dapur</h5>
        <p class="text-muted small mb-0">Daftar pesanan kue yang telah diverifikasi dan siap dipanggang</p>
    </div>
    <form method="GET" class="d-flex gap-2">
        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
        <button type="submit" class="btn btn-sm btn-primary-lala">Filter Tanggal</button>
        @if(request('date'))
            <a href="{{ route('admin.produksi.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        @endif
    </form>
</div>

@if($orders->isEmpty())
    <div class="form-card text-center py-5">
        <i class="bi bi-calendar-x fs-1 text-muted d-block mb-2"></i>
        <h5 class="fw-bold">Tidak Ada Jadwal Produksi</h5>
        <p class="text-muted small mb-0">Semua pesanan yang diverifikasi telah selesai atau belum ada pesanan baru.</p>
    </div>
@else
    <div class="row g-4">
        @foreach($orders as $order)
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 bg-white d-flex flex-column" 
                 style="border-top: 5px solid {{ \Carbon\Carbon::parse($order->pickup_date)->isToday() ? 'var(--danger)' : 'var(--caramel)' }} !important;">
                <div class="card-body p-3 p-md-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-light text-dark border">#{{ $order->order_number }}</span>
                            <h6 class="fw-bold mb-0 mt-1 text-choco">{{ $order->customer_name ?? $order->user?->name ?? 'Pelanggan' }}</h6>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ \Carbon\Carbon::parse($order->pickup_date)->isToday() ? 'bg-danger' : 'bg-primary' }}">
                                {{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('d M Y') }}
                            </span>
                            @if(\Carbon\Carbon::parse($order->pickup_date)->isToday())
                                <div class="text-danger small fw-bold">HARI INI!</div>
                            @endif
                        </div>
                    </div>
                    
                    <hr class="my-2">
                    
                    {{-- DETAIL KUSTOMISASI KUE UNTUK DAPUR --}}
                    <div class="my-2 flex-grow-1">
                        @foreach($order->orderItems as $item)
                        <div class="p-2 mb-2 rounded-3 bg-light border">
                            <div class="d-flex justify-content-between fw-bold text-choco">
                                <span>{{ $item->product_name }}</span>
                                <span class="badge bg-dark">{{ $item->qty }} pcs</span>
                            </div>
                            
                            @if($item->size)
                                <div class="small text-muted mt-1">
                                    <i class="bi bi-rulers me-1"></i>Ukuran: <strong>{{ $item->size }}</strong>
                                </div>
                            @endif
                            
                            @if($item->cake_text)
                                <div class="small mt-1 p-1 bg-white rounded border text-choco">
                                    <i class="bi bi-type me-1"></i>Tulisan: <strong>"{{ $item->cake_text }}"</strong>
                                </div>
                            @endif
                            
                            @if($item->custom_note)
                                <div class="small text-danger mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>Catatan: {{ $item->custom_note }}
                                </div>
                            @endif
                        </div>
                        @endforeach
                        
                        @if($order->note)
                        <div class="small p-2 bg-warning-subtle text-warning-emphasis rounded border border-warning mt-2">
                            <strong>Catatan Tambahan:</strong> {{ $order->note }}
                        </div>
                        @endif
                    </div>
                    
                    {{-- TOMBOL SIAP DIAMBIL --}}
                    <div class="mt-3 pt-2 border-top">
                        <form method="POST" action="{{ route('admin.pesanan.status', $order) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="siap_diambil">
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm"
                                    onclick="return confirm('Kue sudah selesai dipanggang dan Siap Diambil?')">
                                <i class="bi bi-check-circle me-1"></i>Kue Selesai (Siap Diambil)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection
