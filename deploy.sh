#!/bin/bash
# =============================================================================
# Toko Kue Lala - Deployment Script (Untuk VPS / Cloud / Dedicated Server)
# Cara pakai: chmod +x deploy.sh && ./deploy.sh
# =============================================================================

set -e

echo "🚀 Memulai Deployment Toko Kue Lala..."

# 1. Pastikan dependencies production terinstall
echo "📦 Menginstall dependencies composer (production mode)..."
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 2. Hubungkan symbolic link storage
echo "🔗 Menghubungkan storage link..."
php artisan storage:link || true

# 3. Jalankan migrasi database
echo "🗄️ Menjalankan migrasi database..."
php artisan migrate --force

# 4. Optimalkan cache Laravel
echo "⚡ Mengoptimalkan cache..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Atur permission folder storage & bootstrap
echo "🔒 Menyesuaikan permission folder..."
chmod -R 775 storage bootstrap/cache

echo "✅ Deployment Toko Kue Lala selesai dan website siap menerima pesanan!"
