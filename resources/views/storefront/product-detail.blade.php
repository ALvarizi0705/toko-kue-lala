@extends('layouts.storefront')
@section('title', $product->name)
@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color:var(--caramel);">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog') }}" class="text-decoration-none" style="color:var(--caramel);">Katalog</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>
    
    <div class="row g-5">
        <div class="col-md-6">
            <div class="rounded-4 overflow-hidden shadow-sm bg-white border">
                <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" 
                     class="w-100" style="aspect-ratio:4/3;object-fit:cover;">
            </div>
        </div>
        <div class="col-md-6">
            <span class="badge rounded-pill mb-2 px-3 py-2 fw-semibold" style="background:var(--butter);color:var(--choco);">
                {{ $product->category->name ?? 'Aneka Kue' }}
            </span>
            <h1 class="fw-bold mb-3">{{ $product->name }}</h1>
            <div class="fs-2 fw-bold mb-3" style="color:var(--caramel);">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </div>
            
            <p class="text-muted mb-4 lead fs-6">{{ $product->description ?? 'Kue segar buatan Toko Kue Lala dengan cita rasa rumahan premium.' }}</p>
            
            <div class="card p-4 rounded-4 shadow-sm border-0 bg-white mb-4">
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jumlah Pesanan</label>
                        <div class="input-group" style="max-width:160px;">
                            <button type="button" class="btn btn-outline-secondary" 
                                    onclick="const el=document.getElementById('qty');el.value=Math.max(1,+el.value-1)">−</button>
                            <input type="number" id="qty" name="qty" class="form-control text-center fw-bold" value="1" min="1" max="100">
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="const el=document.getElementById('qty');el.value=Math.min(100,+el.value+1)">+</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary-lala w-100 py-2 fw-semibold">
                        <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                    </button>
                </form>
            </div>
            
            <div class="p-3 rounded-4" style="background:var(--cream);border:1px dashed var(--caramel);">
                <div class="fw-bold mb-2 text-choco"><i class="bi bi-info-circle me-2"></i>Informasi Pemesanan</div>
                <ul class="small mb-0 text-muted ps-3">
                    <li>Kustomisasi tulisan di kue & lilin/kartu ucapan bisa diisi saat <strong>Checkout</strong>.</li>
                    <li>Waktu pemesanan minimal <strong>H+1</strong> sebelum tanggal ambil.</li>
                    <li>Pengambilan langsung di toko Jl. Matraman Dalam III No. 26B, Jakarta Timur.</li>
                </ul>
            </div>
        </div>
    </div>
    
    {{-- PRODUK SERUPA --}}
    @if($relatedProducts->isNotEmpty())
    <div class="mt-5 pt-4">
        <h3 class="fw-bold mb-4">Kue Serupa Lainnya</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $related)
            <div class="col-6 col-md-3">
                <div class="product-card shadow-sm h-100 d-flex flex-column">
                    <a href="{{ route('product.show', $related) }}" class="text-decoration-none">
                        <div class="card-img-wrap">
                            <img src="{{ $related->photo_url }}" alt="{{ $related->name }}" loading="lazy">
                        </div>
                    </a>
                    <div class="card-body p-3 d-flex flex-column">
                        <a href="{{ route('product.show', $related) }}" class="text-decoration-none">
                            <h6 class="product-name fw-bold">{{ $related->name }}</h6>
                        </a>
                        <div class="product-price mt-auto pt-2">
                            Rp {{ number_format($related->price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
