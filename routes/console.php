<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:deploy {--seed : Jalankan database seeder jika database baru}', function () {
    $this->info('🚀 Memulai persiapan deployment Toko Kue Lala...');

    // 1. Buat symbolic link storage
    try {
        $this->call('storage:link');
    } catch (\Throwable $e) {
        $this->warn('Storage link: ' . $e->getMessage());
    }

    // 2. Jalankan migrasi database
    $this->info('📦 Menjalankan migrasi database...');
    if ($this->option('seed')) {
        $this->call('migrate', ['--force' => true, '--seed' => true]);
    } else {
        $this->call('migrate', ['--force' => true]);
    }

    // 3. Optimasi cache untuk production
    $this->info('⚡ Mengoptimalkan cache config, routes, dan views...');
    $this->call('optimize:clear');
    $this->call('config:cache');
    $this->call('route:cache');
    $this->call('view:cache');

    $this->info('✅ Aplikasi Toko Kue Lala berhasil disiapkan untuk production!');
})->purpose('Otomatisasi deployment: migrasi, storage link, dan optimasi cache');
