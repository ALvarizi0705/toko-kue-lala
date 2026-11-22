<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseExportMysqlCommand extends Command
{
    protected $signature = 'db:export-mysql {--output= : Lokasi file SQL output}';
    protected $description = 'Backup SQLite dan ekspor seluruh data ke file SQL yang siap di-import ke Railway MySQL';

    /**
     * Urutan tabel berdasarkan relasi foreign key agar pemindahan data runtut dan aman
     */
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
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
    ];

    public function handle(): int
    {
        $this->info("=== EKSPOR DATA SQLITE KE SQL RAILWAY MYSQL ===");

        // 1. Backup SQLite asli terlebih dahulu
        $sqlitePath = database_path('database.sqlite');
        if (!file_exists($sqlitePath)) {
            $this->error("File database.sqlite tidak ditemukan di {$sqlitePath}!");
            return 1;
        }

        $backupDir = database_path('backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $backupFile = $backupDir . '/database_sqlite_backup_' . date('Ymd_His') . '.sqlite';
        File::copy($sqlitePath, $backupFile);
        $this->info("✓ Backup lokal berhasil disimpan di: " . basename($backupFile));

        // 2. Siapkan file output SQL
        $outputPath = $this->option('output') ?: database_path('railway_toko_kue_lala.sql');
        $handle = fopen($outputPath, 'w');

        if (!$handle) {
            $this->error("Gagal membuka file output untuk penulisan di {$outputPath}!");
            return 1;
        }

        // Tulis header SQL yang ramah MySQL / MariaDB (Railway)
        fwrite($handle, "-- =====================================================================\n");
        fwrite($handle, "-- TOKO KUE LALA - RAILWAY MYSQL DATA EXPORT\n");
        fwrite($handle, "-- Tanggal Ekspor: " . date('Y-m-d H:i:s') . "\n");
        fwrite($handle, "-- Source: database.sqlite lokal\n");
        fwrite($handle, "-- =====================================================================\n\n");
        fwrite($handle, "SET NAMES utf8mb4;\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 0;\n");
        fwrite($handle, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n");
        fwrite($handle, "SET time_zone = '+07:00';\n\n");

        $totalExportedRows = 0;
        $exportedTableStats = [];

        foreach ($this->tableOrder as $table) {
            // Cek apakah tabel ada di SQLite
            $exists = DB::connection('sqlite')
                ->select("SELECT name FROM sqlite_master WHERE type='table' AND name = ?", [$table]);

            if (empty($exists)) {
                continue;
            }

            $rows = DB::connection('sqlite')->table($table)->get();
            $count = $rows->count();

            if ($count === 0) {
                $exportedTableStats[] = ['Tabel' => $table, 'Baris' => 0, 'Status' => 'Dilewati (Kosong)'];
                continue;
            }

            fwrite($handle, "-- ---------------------------------------------------------------------\n");
            fwrite($handle, "-- Data untuk tabel: {$table} ({$count} baris)\n");
            fwrite($handle, "-- ---------------------------------------------------------------------\n");

            // Chunk data per batch untuk performa
            foreach ($rows->chunk(50) as $chunk) {
                $firstRow = (array) $chunk->first();
                $columns = array_keys($firstRow);
                $escapedColumns = array_map(fn($col) => "`{$col}`", $columns);

                $valuesList = [];
                foreach ($chunk as $row) {
                    $rowArray = (array) $row;
                    $escapedValues = [];

                    foreach ($columns as $col) {
                        $val = $rowArray[$col] ?? null;

                        if (is_null($val)) {
                            $escapedValues[] = 'NULL';
                        } elseif (is_numeric($val) && !is_string($val)) {
                            $escapedValues[] = $val;
                        } elseif (is_bool($val)) {
                            $escapedValues[] = $val ? '1' : '0';
                        } else {
                            // Escape string khusus SQL
                            $escaped = str_replace(["\\", "'", "\r", "\n"], ["\\\\", "\\'", "\\r", "\\n"], (string) $val);
                            $escapedValues[] = "'{$escaped}'";
                        }
                    }

                    $valuesList[] = "(" . implode(', ', $escapedValues) . ")";
                }

                $sql = "INSERT INTO `{$table}` (" . implode(', ', $escapedColumns) . ") VALUES \n  " . implode(",\n  ", $valuesList) . ";\n";
                fwrite($handle, $sql);
            }

            fwrite($handle, "\n");

            $totalExportedRows += $count;
            $exportedTableStats[] = ['Tabel' => $table, 'Baris' => $count, 'Status' => '✓ Diekspor'];
        }

        // Tulis footer SQL
        fwrite($handle, "-- Aktifkan kembali Foreign Key Checks\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n");
        fwrite($handle, "-- Ekspor selesai dengan sukses.\n");

        fclose($handle);

        $this->table(['Tabel', 'Jumlah Baris', 'Status'], $exportedTableStats);
        $this->info("✓ Berhasil mengekspor total {$totalExportedRows} baris data!");
        $this->info("✓ File SQL tersimpan di: {$outputPath} (" . round(filesize($outputPath) / 1024, 2) . " KB)");
        $this->line("\nFile ini 100% siap di-import langsung ke Railway MySQL melalui DBeaver, TablePlus, phpMyAdmin, atau MySQL CLI.");

        return 0;
    }
}
