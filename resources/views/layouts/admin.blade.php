<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') - Toko Kue Lala</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    @stack('styles')
</head>
<body class="bg-cream">

<div class="admin-wrapper">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <span>🎂</span>
            <span>Toko Kue Lala</span>
        </div>
        
        <!-- User Info -->
        <div class="px-3 py-2 border-bottom" style="border-color:rgba(255,255,255,0.1)!important;">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:36px;height:36px;background:var(--caramel);flex-shrink:0;font-size:1rem;color:#fff;font-weight:700;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-600 small text-white lh-1">{{ auth()->user()->name }}</div>
                    <div class="text-white-50" style="font-size:0.7rem;">
                        @php
                            $roleLabels = [
                                'pemilik' => 'Pemilik',
                                'staf_kasir' => 'Staf Kasir',
                                'staf_produksi' => 'Staf Produksi',
                            ];
                        @endphp
                        {{ $roleLabels[auth()->user()->role] ?? auth()->user()->role }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="mt-2">
            {{-- Dashboard --}}
            <div class="sidebar-section-title">Utama</div>
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            
            {{-- Pesanan & Verifikasi --}}
            <div class="sidebar-section-title">Pesanan</div>
            <a href="{{ route('admin.pesanan.index') }}" 
               class="nav-link {{ request()->routeIs('admin.pesanan*') ? 'active' : '' }}">
                <i class="bi bi-bag-check"></i> Kelola Pesanan
            </a>
            
            @if(in_array(auth()->user()->role, ['pemilik', 'staf_kasir']))
            <a href="{{ route('admin.verifikasi.index') }}" 
               class="nav-link {{ request()->routeIs('admin.verifikasi*') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i> Verifikasi Bayar
                @php
                    $pendingVerif = \App\Models\Order::where('status', 'menunggu_verifikasi')->count();
                @endphp
                @if($pendingVerif > 0)
                    <span class="badge-sidebar">{{ $pendingVerif }}</span>
                @endif
            </a>
            @endif
            
            <a href="{{ route('admin.produksi.index') }}" 
               class="nav-link {{ request()->routeIs('admin.produksi*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Jadwal Produksi
            </a>
            
            {{-- Penjualan Langsung --}}
            @if(in_array(auth()->user()->role, ['pemilik', 'staf_kasir']))
            <div class="sidebar-section-title">Kasir</div>
            <a href="{{ route('admin.penjualan-langsung.index') }}" 
               class="nav-link {{ request()->routeIs('admin.penjualan-langsung*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin"></i> Penjualan Langsung
            </a>
            @endif
            
            {{-- Produk & Stok --}}
            <div class="sidebar-section-title">Produk</div>
            @if(in_array(auth()->user()->role, ['pemilik', 'staf_kasir']))
            <a href="{{ route('admin.produk.index') }}" 
               class="nav-link {{ request()->routeIs('admin.produk*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Kelola Produk
            </a>
            <a href="{{ route('admin.kategori.index') }}" 
               class="nav-link {{ request()->routeIs('admin.kategori*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Kategori
            </a>
            @endif
            
            {{-- Laporan (pemilik saja) --}}
            @if(auth()->user()->role === 'pemilik')
            <div class="sidebar-section-title">Laporan</div>
            <a href="{{ route('admin.laporan.penjualan') }}" 
               class="nav-link {{ request()->routeIs('admin.laporan.penjualan') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Laporan Penjualan
            </a>
            @endif
            
            {{-- Pengaturan (pemilik saja) --}}
            @if(auth()->user()->role === 'pemilik')
            <div class="sidebar-section-title">Pengaturan</div>
            <a href="{{ route('admin.akun.index') }}" 
               class="nav-link {{ request()->routeIs('admin.akun*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Kelola Akun
            </a>
            <a href="{{ route('admin.pengaturan.index') }}" 
               class="nav-link {{ request()->routeIs('admin.pengaturan*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Pengaturan Toko
            </a>
            @endif
        </nav>
        
        <!-- Logout -->
        <div class="mt-auto p-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link text-danger w-100 border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </aside>
    
    <!-- MAIN CONTENT -->
    <div class="admin-content">
        <!-- TOP BAR -->
        <div class="admin-topbar">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle" 
                    onclick="document.getElementById('adminSidebar').classList.toggle('open')">
                <i class="bi bi-list"></i>
            </button>
            
            <h6 class="mb-0 fw-600 text-choco">@yield('title', 'Dashboard')</h6>
            
            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-shop"></i> <span class="d-none d-md-inline">Lihat Toko</span>
                </a>
            </div>
        </div>
        
        <!-- FLASH MESSAGES -->
        <div class="admin-main pb-0">
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
            @if($errors->any())
                <div class="alert alert-danger flash-message">
                    <strong>Terdapat kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
        </div>
        
        <!-- PAGE CONTENT -->
        <div class="admin-main">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.flash-message').forEach(el => {
            try { bootstrap.Alert.getOrCreateInstance(el).close(); } catch(e){}
        });
    }, 5000);
    
    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('adminSidebar');
        const toggle = document.getElementById('sidebarToggle');
        if (sidebar && toggle && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('open');
        }
    });
</script>
@stack('scripts')
</body>
</html>
