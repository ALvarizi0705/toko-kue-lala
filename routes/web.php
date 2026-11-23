<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\ProductionScheduleController;
use App\Http\Controllers\Admin\DirectSaleController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/daftar', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| PUBLIC / STOREFRONT ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [StorefrontController::class, 'index'])->name('home');
Route::get('/katalog', [StorefrontController::class, 'catalog'])->name('catalog');
Route::get('/produk/{product}', [StorefrontController::class, 'show'])->name('product.show');

/*
|--------------------------------------------------------------------------
| CART ROUTES (bebas tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{rowId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{rowId}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/keranjang', [CartController::class, 'clear'])->name('cart.clear');

/*
|--------------------------------------------------------------------------
| CHECKOUT & PELACAKAN PESANAN (PUBLIC - TANPA LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
Route::get('/pesanan/{order:order_number}', [OrderController::class, 'trackDetail'])->name('order.show');
Route::post('/pesanan/{order:order_number}/bukti-bayar', [OrderController::class, 'uploadPayment'])->name('order.upload-payment');
Route::get('/pesanan/{order:order_number}/nota', [OrderController::class, 'nota'])->name('order.nota');
Route::get('/lacak-pesanan', [OrderController::class, 'trackForm'])->name('order.track');
Route::post('/lacak-pesanan', [OrderController::class, 'trackLookup'])->name('order.track.lookup');

/*
|--------------------------------------------------------------------------
| PELANGGAN ROUTES (opsional jika login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active'])->group(function () {
    // Pesanan Saya (untuk user terdaftar)
    Route::get('/pesanan-saya', [OrderController::class, 'myOrders'])->name('orders.my');
    Route::get('/pesanan-saya/{order}', [OrderController::class, 'myOrderDetail'])->name('orders.my.detail');

    // Pembayaran Online (upload bukti transfer)
    Route::get('/pembayaran/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/pembayaran/{order}/upload', [PaymentController::class, 'upload'])->name('payment.upload');

    // Profil
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profil/kata-sandi', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'active'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard')
        ->middleware('role:pemilik,staf_kasir,staf_produksi');

    // Pesanan
    Route::resource('pesanan', OrderAdminController::class)->only(['index', 'show'])
        ->middleware('role:pemilik,staf_kasir,staf_produksi');
    Route::patch('pesanan/{order}/status', [OrderAdminController::class, 'updateStatus'])->name('pesanan.status')
        ->middleware('role:pemilik,staf_kasir,staf_produksi');
    Route::patch('pesanan/{order}/batalkan', [OrderAdminController::class, 'cancel'])->name('pesanan.batalkan')
        ->middleware('role:pemilik,staf_kasir');
    Route::get('pesanan/{order}/cetak', [OrderAdminController::class, 'cetak'])->name('pesanan.cetak')
        ->middleware('role:pemilik,staf_kasir,staf_produksi');
    Route::get('pesanan/{order}/whatsapp', [OrderAdminController::class, 'whatsapp'])->name('pesanan.whatsapp')
        ->middleware('role:pemilik,staf_kasir');

    // Verifikasi Pembayaran (hanya kasir dan pemilik)
    Route::get('verifikasi', [PaymentVerificationController::class, 'index'])->name('verifikasi.index')
        ->middleware('role:pemilik,staf_kasir');
    Route::patch('verifikasi/{order}/terima', [PaymentVerificationController::class, 'approve'])->name('verifikasi.terima')
        ->middleware('role:pemilik,staf_kasir');
    Route::patch('verifikasi/{order}/tolak', [PaymentVerificationController::class, 'reject'])->name('verifikasi.tolak')
        ->middleware('role:pemilik,staf_kasir');

    // Jadwal Produksi (semua staf)
    Route::get('jadwal-produksi', [ProductionScheduleController::class, 'index'])->name('produksi.index')
        ->middleware('role:pemilik,staf_kasir,staf_produksi');

    // Produk & Kategori (pemilik dan kasir)
    Route::resource('produk', ProductController::class)
        ->middleware('role:pemilik,staf_kasir');
    Route::patch('produk/{product}/toggle', [ProductController::class, 'toggle'])->name('produk.toggle')
        ->middleware('role:pemilik,staf_kasir');
    Route::resource('kategori', CategoryController::class)
        ->middleware('role:pemilik,staf_kasir');

    // Penjualan Langsung (kasir dan pemilik)
    Route::resource('penjualan-langsung', DirectSaleController::class)->only(['index', 'create', 'store', 'show'])
        ->middleware('role:pemilik,staf_kasir');
    Route::get('penjualan-langsung/{penjualan_langsung}/cetak', [DirectSaleController::class, 'cetak'])->name('penjualan-langsung.cetak')
        ->middleware('role:pemilik,staf_kasir');

    // Laporan Penjualan (hanya pemilik)
    Route::prefix('laporan')->name('laporan.')->middleware('role:pemilik')->group(function () {
        Route::get('penjualan', [ReportController::class, 'penjualan'])->name('penjualan');
        Route::get('penjualan/ekspor-excel', [ReportController::class, 'eksporExcelPenjualan'])->name('penjualan.excel');
        Route::get('penjualan/ekspor-pdf', [ReportController::class, 'eksporPdfPenjualan'])->name('penjualan.pdf');
    });

    // Kelola Akun (hanya pemilik)
    Route::resource('akun', UserManagementController::class)
        ->middleware('role:pemilik');
    Route::patch('akun/{user}/toggle', [UserManagementController::class, 'toggle'])->name('akun.toggle')
        ->middleware('role:pemilik');
    Route::patch('akun/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('akun.reset-password')
        ->middleware('role:pemilik');

    // Pengaturan Toko (hanya pemilik)
    Route::get('pengaturan', [SettingController::class, 'index'])->name('pengaturan.index')
        ->middleware('role:pemilik');
    Route::post('pengaturan', [SettingController::class, 'update'])->name('pengaturan.update')
        ->middleware('role:pemilik');
});

/*
|--------------------------------------------------------------------------
| STORAGE FALLBACK ROUTE (Solusi Aman untuk Hosting Tanpa Izin Symlink)
|--------------------------------------------------------------------------
| Jika hosting tidak mengizinkan symlink public/storage, route ini memastikan
| semua foto kue dan bukti bayar tetap bisa diakses tanpa error 404.
*/
Route::get('/storage/{path}', function (string $path) {
    if (str_contains($path, '..')) {
        abort(403);
    }
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*')->name('storage.fallback');
