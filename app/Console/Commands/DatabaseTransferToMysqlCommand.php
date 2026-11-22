<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class DatabaseTransferToMysqlCommand extends Command
{
    protected $signature = 'db:transfer-to-mysql 
                            {--host= : Host MySQL Railway (contoh: roundhouse.proxy.rlwy.net)}
                            {--port= : Port MySQL Railway (contoh: 3306)}
                            {--database= : Nama database MySQL Railway (contoh: railway)}
                            {--username= : Username MySQL (contoh: root)}
                            {--password= : Password MySQL}
                            {--skip-migration : Lewati proses pembuatan skema tabel (asumsi tabel sudah di-migrate)}';

    protected $description = 'Mentransfer seluruh struktur dan data dari SQLite lokal langsung ke MySQL Railway';

    protected array $tableOrder = [
        'settings',
        'categories',
        'users',
        'products',
        'orders',
        'order_items',
        'order_status_logs',
        'direct_sales',
        'direct_sale_items',
        'ingredients',
        'stock_ins',
        'stock_usages',
        'stock_adjustments',
        'migrations',
        'sessions',
        'password_reset_tokens',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
    ];

    public function handle(): int
    {
        $this->info("=== PEMINDAHAN DATA SQLITE LOKAL KE MYSQL RAILWAY ===\n");

        // 1. Konfigurasi kredensial koneksi MySQL
        if ($this->option('host')) {
            Config::set('database.connections.mysql.host', $this->option('host'));
        }
        if ($this->option('port')) {
            Config::set('database.connections.mysql.port', $this->option('port'));
        }
        if ($this->option('database')) {
            Config::set('database.connections.mysql.database', $this->option('database'));
        }
        if ($this->option('username')) {
            Config::set('database.connections.mysql.username', $this->option('username'));
        }
        if ($this->option('password') !== null) {
            Config::set('database.connections.mysql.password', $this->option('password'));
        }

        $mysqlConfig = Config::get('database.connections.mysql');
        $this->line("Target Database: MySQL");
        $this->line("Host: {$mysqlConfig['host']}:{$mysqlConfig['port']}");
        $this->line("Database: {$mysqlConfig['database']}");
        $this->line("User: {$mysqlConfig['username']}\n");

        // 2. Tes koneksi ke MySQL Railway
        $this->info("Menghubungkan ke MySQL Railway...");
        try {
            DB::connection('mysql')->getPdo();
            $this->info("✓ Koneksi ke MySQL Railway berhasil terhubung!\n");
        } catch (Throwable $e) {
            $this->error("✗ Gagal terhubung ke MySQL Railway: " . $e->getMessage());
            $this->line("\nTips:");
            $this->line("1. Pastikan Railway MySQL instance aktif.");
            $this->line("2. Jalankan command dengan argumen parameter lengkap, contoh:");
            $this->line("   php artisan db:transfer-to-mysql --host=YOUR_HOST --port=YOUR_PORT --database=railway --username=root --password=YOUR_PASS");
            $this->line("3. Atau gunakan metode import file SQL: database/railway_toko_kue_lala.sql\n");
            return 1;
        }

        // 3. Jalankan migration pada MySQL untuk membuat struktur tabel yang bersih
        if (!$this->option('skip-migration')) {
            $this->info("Menjalankan migrasi skema tabel ke MySQL Railway...");
            try {
                Artisan::call('migrate', [
                    '--database' => 'mysql',
                    '--force' => true,
                ], $this->getOutput());
                $this->info("✓ Skema seluruh tabel berhasil dibuat di MySQL Railway!\n");
            } catch (Throwable $e) {
                $this->error("✗ Gagal menjalankan migrasi ke MySQL: " . $e->getMessage());
                return 1;
            }
        }

        // 4. Nonaktifkan foreign key checks pada MySQL sebelum transfer
        $this->info("Menonaktifkan Foreign Key Checks sementara...");
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS = 0;');

        $transferStats = [];
        $totalTransferredRows = 0;

        $this->info("Memulai penyalinan data dari SQLite ke MySQL Railway...\n");

        try {
            foreach ($this->tableOrder as $table) {
                // Periksa apakah tabel ada di SQLite
                $sqliteHasTable = DB::connection('sqlite')
                    ->select("SELECT name FROM sqlite_master WHERE type='table' AND name = ?", [$table]);

                if (empty($sqliteHasTable)) {
                    continue;
                }

                $rows = DB::connection('sqlite')->table($table)->get();
                $rowCount = $rows->count();

                if ($rowCount === 0) {
                    $transferStats[] = [
                        'Tabel' => $table,
                        'Baris SQLite' => 0,
                        'Tersalin ke MySQL' => 0,
                        'Status' => 'Dilewati (Kosong)',
                    ];
                    continue;
                }

                // Bersihkan tabel target di MySQL jika ada sisa data
                DB::connection('mysql')->table($table)->truncate();

                // Salin data dalam batch
                $batch = [];
                foreach ($rows as $row) {
                    $batch[] = (array) $row;
                    if (count($batch) >= 100) {
                        DB::connection('mysql')->table($table)->insert($batch);
                        $batch = [];
                    }
                }
                if (!empty($batch)) {
                    DB::connection('mysql')->table($table)->insert($batch);
                }

                $totalTransferredRows += $rowCount;
                $transferStats[] = [
                    'Tabel' => $table,
                    'Baris SQLite' => $rowCount,
                    'Tersalin ke MySQL' => $rowCount,
                    'Status' => '✓ Berhasil',
                ];
            }
        } catch (Throwable $e) {
            $this->error("✗ Terjadi kesalahan saat mentransfer data: " . $e->getMessage());
            // Tetap pastikan FK checks diaktifkan kembali
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS = 1;');
            return 1;
        } finally {
            // 5. Aktifkan kembali foreign key checks
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS = 1;');
        }

        $this->table(['Tabel', 'Baris SQLite', 'Tersalin ke MySQL', 'Status'], $transferStats);
        $this->info("\n✓ Transfer selesai! Total {$totalTransferredRows} baris data berhasil disalin ke MySQL Railway.");
        $this->line("Data di database SQLite lokal tetap aman dan tidak berubah.");

        return 0;
    }
}
