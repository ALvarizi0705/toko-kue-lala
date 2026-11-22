@extends('layouts.storefront')
@section('title', 'Keranjang Belanja')
@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2" style="color:var(--caramel);"></i>Keranjang Belanja</h2>
    
    @if(empty($cart))
        <div class="empty-state bg-white p-5 rounded-4 shadow-sm text-center">
            <div class="empty-icon fs-1">🛒</div>
            <h4 class="fw-bold">Keranjangmu masih kosong</h4>
            <p class="text-muted mb-4">Kamu belum menambahkan kue lezat ke dalam keranjang.</p>
            <a href="{{ route('catalog') }}" class="btn btn-primary-lala px-4 py-2">
                <i class="bi bi-grid-3x3-gap me-2"></i>Lihat Katalog Sekarang
            </a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card p-3 shadow-sm border-0 rounded-4 bg-white mb-3">
                    @foreach($cart as $rowId => $item)
                    <div class="d-flex flex-wrap align-items-center justify-content-between py-3 {{ !$loop->last ? 'border-bottom' : '' }} gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $item['photo'] ?? asset('images/placeholder.jpg') }}" 
                                 alt="{{ $item['name'] }}"
                                 style="width:75px;height:75px;object-fit:cover;border-radius:12px;background:#f8f9fa;">
                            <div>
                                <h6 class="fw-bold mb-1 text-choco">{{ $item['name'] }}</h6>
                                <div class="text-muted small">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 ms-auto">
                            <form method="POST" action="{{ route('cart.update', $rowId) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <div class="input-group input-group-sm" style="width:110px;">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="const el=this.nextElementSibling;el.value=Math.max(1,+el.value-1);this.form.submit()">−</button>
                                    <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="100" 
                                           class="form-control text-center fw-bold" onchange="this.form.submit()">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="const el=this.previousElementSibling;el.value=Math.min(100,+el.value+1);this.form.submit()">+</button>
                                </div>
                            </form>
                            
                            <div class="fw-bold text-end" style="color:var(--caramel);min-width:110px;">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                            
                            <form method="POST" action="{{ route('cart.remove', $rowId) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                        onclick="return confirm('Hapus item ini dari keranjang?')" title="Hapus">
                                    <i class="bi bi-trash fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Kosongkan semua kue di keranjang?')">
                        <i class="bi bi-trash3 me-1"></i>Kosongkan Keranjang
                    </button>
                </form>
            </div>
            
            <div class="col-lg-4">
                <div class="card p-4 shadow-sm border-0 rounded-4 bg-white position-sticky" style="top:90px;">
                    <h5 class="fw-bold mb-3 text-choco">Ringkasan Pesanan</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Jumlah Kue</span>
                        <span class="fw-semibold">{{ collect($cart)->sum('qty') }} pcs</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fs-5 fw-bold text-choco">Total Biaya</span>
                        <span class="fs-4 fw-bold" style="color:var(--caramel);">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <a href="{{ route('checkout') }}" class="btn btn-primary-lala w-100 py-2 fw-semibold text-center">
                        <i class="bi bi-bag-check me-2"></i>Lanjut ke Checkout
                    </a>
                    <p class="text-center small text-muted mt-2 mb-0" style="font-size:0.75rem;">
                        <i class="bi bi-check2-circle text-success me-1"></i>Pesan praktis tanpa perlu login
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
