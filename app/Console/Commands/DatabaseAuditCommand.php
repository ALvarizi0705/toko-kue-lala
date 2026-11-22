<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseAuditCommand extends Command
{
    protected $signature = 'db:audit-sqlite';
    protected $description = 'Audit seluruh isi tabel dan jumlah data di database.sqlite';

    public function handle(): int
    {
        $this->info("=== AUDIT DATABASE SQLITE LOKAL ===");

        $dbPath = database_path('database.sqlite');
        if (!file_exists($dbPath)) {
            $this->error("File database.sqlite tidak ditemukan di {$dbPath}!");
            return 1;
        }

        $this->line("Lokasi Database: " . $dbPath);
        $this->line("Ukuran File: " . round(filesize($dbPath) / 1024, 2) . " KB\n");

        $tables = DB::connection('sqlite')->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");

        $summary = [];
        $totalRows = 0;

        foreach ($tables as $t) {
            $tableName = $t->name;
            $count = DB::connection('sqlite')->table($tableName)->count();
            $totalRows += $count;
            $summary[] = [
                'Tabel' => $tableName,
                'Jumlah Baris' => $count,
                'Status' => $count > 0 ? 'Berisi Data' : 'Kosong',
            ];
        }

        $this->table(['Nama Tabel', 'Jumlah Baris', 'Status'], $summary);
        $this->info("Total keseluruhan baris data: {$totalRows}\n");

        // Tampilkan sampel data penting
        $this->info("--- DATA PENGGUNA (users) ---");
        $users = DB::connection('sqlite')->table('users')->select(['id', 'name', 'phone', 'email', 'role'])->get();
        $this->table(['ID', 'Nama', 'No. WhatsApp', 'Email', 'Role'], $users->map(fn($u) => (array)$u)->toArray());

        $this->info("\n--- DATA PRODUK MENU KUE (products) ---");
        $products = DB::connection('sqlite')->table('products')->select(['id', 'name', 'price', 'is_active'])->get();
        $this->table(['ID', 'Nama Produk', 'Harga', 'Aktif'], $products->map(fn($p) => (array)$p)->toArray());

        $this->info("\n--- DATA PENGATURAN TOKO (settings) ---");
        $settings = DB::connection('sqlite')->table('settings')->select(['key', 'value'])->get();
        $this->table(['Key', 'Value'], $settings->map(fn($s) => (array)$s)->toArray());

        return 0;
    }
}
