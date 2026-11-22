<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Throwable;

class DatabaseVerifyTransferCommand extends Command
{
    protected $signature = 'db:verify-transfer
                            {--host= : Host MySQL Railway}
                            {--port= : Port MySQL Railway}
                            {--database= : Nama database MySQL Railway}
                            {--username= : Username MySQL}
                            {--password= : Password MySQL}';

    protected $description = 'Verifikasi dan bandingkan jumlah data setiap tabel antara SQLite lokal dan MySQL Railway';

    protected array $tablesToCheck = [
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
        $this->info("=== VERIFIKASI SINKRONISASI DATA: SQLITE VS MYSQL RAILWAY ===\n");

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

        try {
            DB::connection('mysql')->getPdo();
            $this->info("✓ Terhubung ke MySQL Railway.");
        } catch (Throwable $e) {
            $this->error("✗ Gagal terhubung ke MySQL Railway: " . $e->getMessage());
            $this->line("Pastikan Railway MySQL sedang aktif dan parameter koneksi sudah benar.");
            return 1;
        }

        $comparison = [];
        $allMatch = true;
        $totalSqlite = 0;
        $totalMysql = 0;

        foreach ($this->tablesToCheck as $table) {
            // Count SQLite
            $sqliteCount = 0;
            try {
                $sqliteCount = DB::connection('sqlite')->table($table)->count();
                $totalSqlite += $sqliteCount;
            } catch (Throwable $e) {
                $sqliteCount = '-';
            }

            // Count MySQL
            $mysqlCount = 0;
            $tableExistsInMysql = true;
            try {
                $mysqlCount = DB::connection('mysql')->table($table)->count();
                $totalMysql += $mysqlCount;
            } catch (Throwable $e) {
                $tableExistsInMysql = false;
            }

            if (!$tableExistsInMysql) {
                $status = '✗ Tabel belum ada di MySQL';
                $allMatch = false;
                $mysqlDisplay = 'Belum Ada';
            } elseif ($sqliteCount === $mysqlCount) {
                $status = '✓ Sesuai (Match)';
                $mysqlDisplay = (string) $mysqlCount;
            } else {
                $status = '✗ Tidak Sesuai';
                $allMatch = false;
                $mysqlDisplay = (string) $mysqlCount;
            }

            $comparison[] = [
                'Tabel' => $table,
                'Baris SQLite (Lokal)' => $sqliteCount,
                'Baris MySQL (Railway)' => $mysqlDisplay,
                'Status' => $status,
            ];
        }

        $this->table(['Tabel', 'Baris SQLite (Lokal)', 'Baris MySQL (Railway)', 'Status'], $comparison);

        $this->line("\nRingkasan Total Baris:");
        $this->line("• Total Baris SQLite: {$totalSqlite}");
        $this->line("• Total Baris MySQL : {$totalMysql}");

        if ($allMatch) {
            $this->info("\n✓ VERIFIKASI SUKSES: Seluruh data tabel pada SQLite dan MySQL Railway 100% IDENTIK!");
            return 0;
        } else {
            $this->warn("\n! PERINGATAN: Terdapat perbedaan jumlah data antara SQLite dan MySQL.");
            $this->line("Silakan jalankan `php artisan db:transfer-to-mysql` atau import ulang file SQL `railway_toko_kue_lala.sql`.");
            return 1;
        }
    }
}
