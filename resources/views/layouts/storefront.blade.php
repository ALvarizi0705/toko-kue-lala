<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Toko Kue Lala') - Kue Segar, Dipesan Kapan Saja</title>
    <meta name="description" content="@yield('description', 'Toko Kue Lala - UMKM kue premium di Jakarta Timur. Pesan kue ulang tahun, brownies, dan kue spesial lainnya.')">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tema Toko Kue Lala -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-lala sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="icon-cake">🎂</span> Toko Kue Lala
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('catalog') ? 'active' : '' }}" href="{{ route('catalog') }}">
                            <i class="bi bi-grid-3x3-gap"></i> Katalog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('order.track*') ? 'active' : '' }}" href="{{ route('order.track') }}">
                            <i class="bi bi-search"></i> Lacak Pesanan
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->role === 'pelanggan')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('orders.my*') ? 'active' : '' }}" href="{{ route('orders.my') }}">
                                <i class="bi bi-bag-check"></i> Pesanan Saya
                            </a>
                        </li>
                        @endif
                        @if(in_array(auth()->user()->role, ['pemilik','staf_kasir','staf_produksi']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Panel Admin
                            </a>
                        </li>
                        @endif
                    @endauth
                </ul>
                
                <div class="d-flex align-items-center gap-2">
                    <!-- Keranjang -->
                    <a href="{{ route('cart.index') }}" class="cart-badge-btn">
                        <i class="bi bi-cart3"></i>
                        <span>Keranjang</span>
                        @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                        @if($cartCount > 0)
                            <span class="cart-badge-count">{{ $cartCount }}</span>
                        @endif
                    </a>
                    
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center gap-1" 
                                    type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person"></i> Profil
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="bi bi-box-arrow-right"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-sm" style="background:var(--choco);color:#fff;border-radius:var(--radius-sm);">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- FLASH MESSAGES -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible flash-message" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible flash-message" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible flash-message" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger flash-message">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <strong>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    
    <!-- MAIN CONTENT -->
    @yield('content')
    
    <!-- FOOTER -->
    <footer class="footer-lala mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5>🎂 Toko Kue Lala</h5>
                    <p class="small opacity-75">Kue segar berkualitas premium untuk setiap momen spesialmu. Dibuat dengan cinta dari dapur kami.</p>
                </div>
                <div class="col-md-4">
                    <h5>Kontak & Lokasi</h5>
                    <p class="small mb-1"><i class="bi bi-geo-alt me-2"></i>Jl. Matraman Dalam III No. 26B, Jakarta Timur</p>
                    <p class="small mb-1"><i class="bi bi-clock me-2"></i>Senin–Sabtu, 08.00–20.00 WIB</p>
                    <p class="small mb-3"><i class="bi bi-whatsapp me-2"></i>{{ \App\Models\Setting::get('phone', '08123456789') }}</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('phone', '08123456789')) }}?text=Halo+Toko+Kue+Lala,+saya+ingin+bertanya." 
                       target="_blank" class="btn-whatsapp">
                        <i class="bi bi-whatsapp"></i> Chat via WhatsApp
                    </a>
                </div>
                <div class="col-md-4">
                    <h5>Cara Memesan</h5>
                    <ol class="small opacity-75 ps-3">
                        <li class="mb-1">Pilih kue di katalog</li>
                        <li class="mb-1">Masukkan ke keranjang & checkout</li>
                        <li>Transfer & upload bukti bayar</li>
                    </ol>
                </div>
            </div>
            <hr style="border-color:rgba(255,255,255,0.15);" class="mt-4 mb-3">
            <p class="text-center small opacity-50 mb-0">© {{ date('Y') }} Toko Kue Lala. Jl. Matraman Dalam III No. 26B, Jakarta Timur.</p>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Auto-dismiss alerts -->
    <script>
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(el => {
                const alert = bootstrap.Alert.getOrCreateInstance(el);
                if (alert) alert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
