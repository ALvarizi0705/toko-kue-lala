@extends('layouts.storefront')
@section('title', 'Katalog Kue')
@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold mb-1">Katalog Kue Segar</h2>
            <p class="text-muted small mb-0">Temukan aneka pilihan kue ulang tahun, bolu, cookies & brownies</p>
        </div>
        <form method="GET" action="{{ route('catalog') }}" class="d-flex gap-2">
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Cari nama kue..." value="{{ request('q') }}">
                <button class="btn btn-primary-lala" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            @if(request('q') || request('kategori'))
                <a href="{{ route('catalog') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>
    
    {{-- CHIP FILTER KATEGORI --}}
    <div class="filter-chips mb-4">
        <a href="{{ route('catalog', ['q' => request('q')]) }}" class="chip {{ !request('kategori') ? 'active' : '' }}">
            Semua Kategori
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('catalog', ['kategori' => $cat->id, 'q' => request('q')]) }}" 
               class="chip {{ request('kategori') == $cat->id ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>
    
    {{-- GRID PRODUK --}}
    @if($products->isEmpty())
        <div class="empty-state bg-white p-5 rounded-4 shadow-sm text-center">
            <div class="empty-icon fs-1">🔍</div>
            <h4 class="fw-bold">Kue yang kamu cari tidak ditemukan</h4>
            <p class="text-muted mb-4">Coba cari dengan kata kunci lain atau pilih kategori lain.</p>
            <a href="{{ route('catalog') }}" class="btn btn-primary-lala">Lihat Semua Kue</a>
        </div>
    @else
        <div class="row g-4 mb-4">
            @foreach($products as $product)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card shadow-sm h-100 d-flex flex-column">
                    <a href="{{ route('product.show', $product) }}" class="text-decoration-none">
                        <div class="card-img-wrap">
                            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" loading="lazy">
                        </div>
                    </a>
                    <div class="card-body d-flex flex-column p-3">
                        <span class="product-category">{{ $product->category->name ?? 'Kue' }}</span>
                        <a href="{{ route('product.show', $product) }}" class="text-decoration-none">
                            <h6 class="product-name fw-bold">{{ $product->name }}</h6>
                        </a>
                        <div class="product-price mt-auto pt-2">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        <a href="{{ route('product.show', $product) }}" class="btn btn-add-cart mt-2">
                            <i class="bi bi-cart-plus me-1"></i>Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
