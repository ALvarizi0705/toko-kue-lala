<?php
/**
 * Toko Kue Lala - Hosting Setup & Deployment Helper
 * File ini membantu inisialisasi aplikasi di hosting cPanel/Shared Hosting tanpa perlu akses SSH terminal.
 * Setelah setup selesai, HAPUS file ini demi keamanan.
 */

// Aktifkan reporting untuk proses setup
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Booting framework Laravel
$autoload = __DIR__ . '/../vendor/autoload.php';
$bootstrap = __DIR__ . '/../bootstrap/app.php';

if (!file_exists($autoload) || !file_exists($bootstrap)) {
    die("<h3>Error: File vendor atau bootstrap tidak ditemukan. Pastikan folder project di-upload lengkap.</h3>");
}

require $autoload;
$app = require_once $bootstrap;
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

$messages = [];
$status = 'idle';

// Aksi jika tombol ditekan
if ($request->isMethod('POST')) {
    $action = $request->input('action');

    try {
        if ($action === 'check_db') {
            DB::connection()->getPdo();
            $dbName = DB::connection()->getDatabaseName();
            $driver = DB::connection()->getDriverName();
            $messages[] = [
                'type' => 'success',
                'text' => "Koneksi database berhasil! Driver: <strong>{$driver}</strong>, Database: <strong>{$dbName}</strong>."
            ];
        } elseif ($action === 'migrate_seed') {
            Artisan::call('migrate', ['--force' => true, '--seed' => true]);
            $output = Artisan::output();
            $messages[] = [
                'type' => 'success',
                'text' => "Migrasi dan Seeder berhasil dijalankan!<pre class='mt-2 bg-dark text-white p-2 rounded small'>" . htmlspecialchars($output) . "</pre>"
            ];
        } elseif ($action === 'migrate_only') {
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();
            $messages[] = [
                'type' => 'success',
                'text' => "Migrasi tabel berhasil dijalankan!<pre class='mt-2 bg-dark text-white p-2 rounded small'>" . htmlspecialchars($output) . "</pre>"
            ];
        } elseif ($action === 'storage_link') {
            $publicStorage = public_path('storage');
            $targetStorage = storage_path('app/public');

            // Hapus link lama jika ada atau broken
            if (is_link($publicStorage) || file_exists($publicStorage)) {
                if (is_link($publicStorage)) {
                    @unlink($publicStorage);
                }
            }

            Artisan::call('storage:link');
            $output = Artisan::output();
            $messages[] = [
                'type' => 'success',
                'text' => "Storage Link berhasil dibuat!<pre class='mt-2 bg-dark text-white p-2 rounded small'>" . htmlspecialchars($output) . "</pre>"
            ];
        } elseif ($action === 'optimize') {
            Artisan::call('optimize:clear');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            $messages[] = [
                'type' => 'success',
                'text' => "Cache config, route, dan view berhasil dioptimalkan untuk production!"
            ];
        } elseif ($action === 'clear_cache') {
            Artisan::call('optimize:clear');
            $messages[] = [
                'type' => 'success',
                'text' => "Semua cache berhasil dibersihkan!"
            ];
        } elseif ($action === 'delete_self') {
            @unlink(__FILE__);
            echo "<script>alert('File setup-hosting.php berhasil dihapus demi keamanan!'); window.location.href='/';</script>";
            exit;
        }
    } catch (\Throwable $e) {
        $messages[] = [
            'type' => 'danger',
            'text' => "Terjadi kesalahan: " . htmlspecialchars($e->getMessage())
        ];
    }
}

// Cek status environment
$phpVersion = PHP_VERSION;
$phpOk = version_compare(PHP_VERSION, '8.2.0', '>=');
$writableStorage = is_writable(storage_path());
$writableBootstrap = is_writable(base_path('bootstrap/cache'));
$appKeySet = !empty(config('app.key'));
$appEnv = config('app.env');
$appDebug = config('app.debug') ? 'TRUE (Nonaktifkan untuk production!)' : 'FALSE (Aman)';

// Cek koneksi DB
$dbOk = false;
$dbError = '';
try {
    DB::connection()->getPdo();
    $dbOk = true;
} catch (\Throwable $e) {
    $dbError = $e->getMessage();
}

$storageLinkExists = file_exists(public_path('storage'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup & Deployment Helper - Toko Kue Lala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #FFF8F0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #3A2A22; }
        .card-custom { border: none; border-radius: 14px; box-shadow: 0 4px 16px rgba(91,58,41,0.08); background: #fff; }
        .btn-lala { background: #C8873E; color: #fff; font-weight: 600; border: none; }
        .btn-lala:hover { background: #5B3A29; color: #fff; }
        .header-title { color: #5B3A29; font-weight: 700; }
    </style>
</head>
<body class="py-5">
<div class="container" style="max-width: 800px;">
    <div class="text-center mb-4">
        <h2 class="header-title">🎂 Toko Kue Lala - Deployment Helper</h2>
        <p class="text-muted">Alat bantu persiapan deployment untuk hosting tanpa memerlukan terminal SSH.</p>
    </div>

    <!-- Alert Notifikasi -->
    <?php foreach ($messages as $msg): ?>
        <div class="alert alert-<?= $msg['type'] ?> alert-dismissible fade show" role="alert">
            <?= $msg['text'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>

    <!-- 1. Audit Kesiapan Sistem -->
    <div class="card card-custom p-4 mb-4">
        <h5 class="fw-bold mb-3 header-title">1. Status Kesiapan Server & Hosting</h5>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <tbody>
                    <tr>
                        <td>Versi PHP Server</td>
                        <td><strong><?= $phpVersion ?></strong></td>
                        <td class="text-end">
                            <span class="badge bg-<?= $phpOk ? 'success' : 'danger' ?>"><?= $phpOk ? 'OK (≥ 8.2)' : 'Update PHP di cPanel' ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Folder <code>storage/</code> Writable</td>
                        <td>Izin tulis direktori penyimpanan</td>
                        <td class="text-end">
                            <span class="badge bg-<?= $writableStorage ? 'success' : 'danger' ?>"><?= $writableStorage ? 'Writable' : 'Set CHMOD 775/777' ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Folder <code>bootstrap/cache/</code> Writable</td>
                        <td>Izin tulis cache bootstrap</td>
                        <td class="text-end">
                            <span class="badge bg-<?= $writableBootstrap ? 'success' : 'danger' ?>"><?= $writableBootstrap ? 'Writable' : 'Set CHMOD 775/777' ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Application Key (APP_KEY)</td>
                        <td>Kunci enkripsi aplikasi</td>
                        <td class="text-end">
                            <span class="badge bg-<?= $appKeySet ? 'success' : 'danger' ?>"><?= $appKeySet ? 'Terisi' : 'Belum diisi di .env' ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Koneksi Database</td>
                        <td>Koneksi ke MySQL / SQLite</td>
                        <td class="text-end">
                            <span class="badge bg-<?= $dbOk ? 'success' : 'danger' ?>"><?= $dbOk ? 'Terkoneksi' : 'Gagal' ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Storage Link (Foto Produk)</td>
                        <td>Symlink <code>public/storage</code></td>
                        <td class="text-end">
                            <span class="badge bg-<?= $storageLinkExists ? 'success' : 'warning text-dark' ?>"><?= $storageLinkExists ? 'Aktif' : 'Belum Dihubungkan' ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Environment (APP_ENV)</td>
                        <td>Mode aplikasi</td>
                        <td class="text-end">
                            <span class="badge bg-info text-dark"><?= strtoupper($appEnv) ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php if (!$dbOk): ?>
            <div class="alert alert-warning small mt-3 mb-0">
                <strong>Peringatan Database:</strong> <?= htmlspecialchars($dbError) ?><br>
                Pastikan nama database, user, dan password di file <code>.env</code> sudah sesuai dengan database di cPanel hosting Anda.
            </div>
        <?php endif; ?>
    </div>

    <!-- 2. Eksekusi Aksi Deployment -->
    <div class="card card-custom p-4 mb-4">
        <h5 class="fw-bold mb-3 header-title">2. Tindakan Inisialisasi Database & Storage</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <form method="POST">
                    <input type="hidden" name="action" value="migrate_seed">
                    <button type="submit" class="btn btn-lala w-100 py-2" onclick="return confirm('Jalankan migrasi tabel database dan masukkan data menu Toko Kue Lala?')">
                        📦 Migrasi & Isi Data Menu Awal
                    </button>
                    <div class="form-text small">Buat semua tabel database dan isi data produk, akun admin, serta pengaturan toko.</div>
                </form>
            </div>
            <div class="col-md-6">
                <form method="POST">
                    <input type="hidden" name="action" value="storage_link">
                    <button type="submit" class="btn btn-outline-dark w-100 py-2">
                        🔗 Buat Storage Link (Upload Foto)
                    </button>
                    <div class="form-text small">Menghubungkan folder upload gambar produk dan struk pembayaran agar tampil di website.</div>
                </form>
            </div>
            <div class="col-md-6">
                <form method="POST">
                    <input type="hidden" name="action" value="optimize">
                    <button type="submit" class="btn btn-outline-success w-100 py-2">
                        ⚡ Optimasi Cache Production
                    </button>
                    <div class="form-text small">Cache config, route, dan view agar website loading maksimal di hosting.</div>
                </form>
            </div>
            <div class="col-md-6">
                <form method="POST">
                    <input type="hidden" name="action" value="clear_cache">
                    <button type="submit" class="btn btn-outline-secondary w-100 py-2">
                        🧹 Bersihkan Cache
                    </button>
                    <div class="form-text small">Gunakan jika ada perubahan kode atau file Blade yang belum ter-update.</div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Selesai & Keamanan -->
    <div class="card card-custom p-4 border border-danger">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold text-danger mb-1">⚠️ Langkah Terakhir (Penting)</h6>
                <div class="small text-muted">Setelah website Anda berhasil tampil dan dapat diakses, segera hapus file ini agar tidak dapat diakses orang lain.</div>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="delete_self">
                <button type="submit" class="btn btn-danger btn-sm px-3" onclick="return confirm('Hapus file setup-hosting.php ini secara permanen?')">
                    🗑️ Hapus File Setup Ini
                </button>
            </form>
        </div>
        <div class="mt-3 text-center">
            <a href="/" target="_blank" class="btn btn-sm btn-primary">Buka Website Toko Kue Lala →</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
