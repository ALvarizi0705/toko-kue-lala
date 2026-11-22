@extends('layouts.storefront')
@section('title', 'Beranda')
@section('content')

{{-- HERO BANNER --}}
<section class="hero-banner">
    <div class="container">
        <h1 class="display-4 fw-bold">Kue Segar, Dipesan<br><span style="color:var(--caramel);">Kapan Saja</span></h1>
        <p class="tagline fs-5">Dari dapur kami ke momen spesialmu. Dibuat dari bahan pilihan premium tanpa pengawet.</p>
        <div class="mt-4">
            <a href="{{ route('catalog') }}" class="btn-hero shadow">
                <i class="bi bi-grid-3x3-gap me-2"></i>Lihat Katalog Lengkap
            </a>
        </div>
    </div>
</section>

{{-- KATEGORI --}}
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Pilihan Kategori</h2>
            <p class="text-muted">Temukan varian kue lezat untuk perayaan hari spesialmu</p>
        </div>
        <div class="row g-3 justify-content-center">
            @foreach($categories as $cat)
            <div class="col-6 col-md-3 col-lg-2">
                <a href="{{ route('catalog', ['kategori' => $cat->id]) }}" 
                   class="d-block text-center p-3 rounded-4 text-decoration-none shadow-sm h-100 bg-white border"
                   style="transition:transform 0.2s;"
                   onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
                    <div style="font-size:2.2rem;margin-bottom:0.5rem;">🎂</div>
                    <div class="fw-bold text-choco small">{{ $cat->name }}</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRODUK UNGGULAN --}}
<section class="py-5 bg-cream">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Menu Paling Favorit</h2>
                <p class="text-muted small mb-0">Kue segar paling banyak diminati pelanggan kami</p>
            </div>
            <a href="{{ route('catalog') }}" class="fw-bold text-decoration-none" style="color:var(--caramel);">
                Semua Menu <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        @if($featuredProducts->isEmpty())
            <div class="empty-state bg-white p-5 rounded-4 shadow-sm text-center">
                <div class="empty-icon fs-1">🍰</div>
                <h4>Produk belum tersedia</h4>
                <p class="text-muted">Nantikan produk baru kami dalam waktu dekat!</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($featuredProducts as $product)
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
                                <i class="bi bi-eye me-1"></i>Detail & Pesan
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- CARA MEMESAN --}}
<section class="py-5" style="background:var(--butter);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Cara Memesan di Toko Kue Lala</h2>
            <p class="text-choco opacity-75">Selesai dalam waktu kurang dari 5 menit tanpa antre panjang</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="step-card bg-white p-4 rounded-4 shadow-sm text-center h-100">
                    <div class="step-number">1</div>
                    <h5 class="fw-bold">Pilih Kue Favorit</h5>
                    <p class="text-muted small">Cari kue pilihanmu di katalog. Tentukan jumlah dan request tulisan ucapan jika diinginkan.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card bg-white p-4 rounded-4 shadow-sm text-center h-100">
                    <div class="step-number">2</div>
                    <h5 class="fw-bold">Isi Data & Pesan</h5>
                    <p class="text-muted small">Cukup isi nama, nomor WhatsApp, dan alamat. Pesan langsung tanpa perlu repot registrasi/login.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="step-card bg-white p-4 rounded-4 shadow-sm text-center h-100">
                    <div class="step-number">3</div>
                    <h5 class="fw-bold">Konfirmasi via WhatsApp</h5>
                    <p class="text-muted small">Penjual akan menghubungi via WhatsApp untuk konfirmasi dan pembayaran. Lacak status pesananmu kapan saja.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
