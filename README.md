# 🎂 Toko Kue Lala - Sistem Informasi Pemesanan & Pengelolaan Penjualan

Sistem Informasi Pemesanan Kue Online dan Pengelolaan Penjualan Kasir (POS) berbasis web untuk **Toko Kue Lala**, UMKM kue basah dan kering di Matraman, Jakarta Timur.

Aplikasi ini mengadopsi model **Ambil Sendiri di Toko (*Self-Pickup*)**, di mana pelanggan memesan dan membayar secara online atau langsung di kasir toko, kemudian mengambil pesanan di lokasi toko sesuai jadwal pengambilan yang dipilih.

🌐 **Website Live (Production):** [https://toko-kue-lala-production.up.railway.app](https://toko-kue-lala-production.up.railway.app)

---

## 📌 Ringkasan Sistem & Alur Bisnis

1. **Model Pengambilan:** **Self-Pickup (Ambil di Toko)** di Jl. Matraman Dalam III No. 26B, Jakarta Timur. Tidak menggunakan kurir antar/ekspedisi.
2. **Pemesanan Online:** Pelanggan (tamu/terdaftar) memilih kue, mengisi tanggal ambil (minimal H+1), mengisi pesan ucapan kue/catatan, lalu melakukan checkout.
3. **Metode Pembayaran:** 
   - **Online:** Transfer Bank BCA, E-Wallet (DANA, GoPay), atau scan **QRIS Statis Toko**. Pelanggan mengunggah foto struk/screenshot transfer langsung di halaman detail pesanan.
   - **Toko (Kasir POS):** Pembayaran tunai, transfer bank, atau scan barcode QRIS yang tampil di layar kasir.
4. **Verifikasi Kasir:** Kasir memeriksa bukti transfer pelanggan di menu *Verifikasi Pembayaran* sebelum pesanan diteruskan ke tim dapur.
5. **Jadwal Dapur (Produksi):** Staf dapur memproses kue sesuai urutan tanggal ambil terdekat dan detail kustomisasi tulisan pada kue.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

- **Backend Framework:** Laravel 11 (PHP ^8.4)
- **Frontend / UI:** Blade Templates, Bootstrap 5.3, Bootstrap Icons, Custom Cake Theme CSS
- **Database:** MySQL 8 (Production di Railway) / SQLite (Pengembangan Lokal)
- **Deployment Platform:** GitHub + Railway (PaaS CI/CD otomatis)
- **Penyimpanan Berkas:** Laravel Storage (`public` disk) untuk foto kue & bukti bayar
- **Reporting & Cetak:** Cetak Struk Kasir Thermal 58mm, Nota Standar A5, dan Laporan Penjualan

---

## 👥 Akun Demo & Hak Akses (Password: `password`)

Database seeder telah menyediakan akun pengujian untuk setiap peran pengguna:

| Peran (Role) | Email Login | No. WhatsApp | Kata Sandi | Hak Akses Utama |
| :--- | :--- | :--- | :--- | :--- |
| **Pemilik** | `pemilik@tokokuelalala.id` | `081234567890` | `password` | Akses penuh, kelola akun staf, laporan penjualan & omset, pengaturan toko |
| **Staf Kasir** | `kasir@tokokuelalala.id` | `081234567891` | `password` | Kasir POS (penjualan langsung), cetak struk/nota, verifikasi bukti transfer |
| **Staf Produksi** | `produksi@tokokuelalala.id` | `081234567892` | `password` | Jadwal produksi dapur, monitoring pesanan diproses, kelola bahan baku |
| **Pelanggan 1** | `pelanggan1@email.com` | `081298765432` | `password` | Pesan kue, upload bukti bayar, lacak status pesanan, riwayat belanja |
| **Pelanggan 2** | `pelanggan2@email.com` | `081287654321` | `password` | Pesan kue, upload bukti bayar, lacak status pesanan, riwayat belanja |

> **Catatan Login:** Form masuk mendukung login menggunakan **Email** ATAU **Nomor WhatsApp** + Kata Sandi.

---

## 🚀 Panduan Menjalankan di Komputer Lokal (Localhost)

### 1. Prasyarat
- PHP >= 8.2 (disarankan PHP 8.4)
- Composer
- Git

### 2. Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/ALvarizi0705/toko-kue-lala.git
cd toko-kue-lala

# 2. Install dependensi
composer install

# 3. Salin environment & generate app key
cp .env.example .env
php artisan key:generate

# 4. Atur database di file .env (contoh SQLite)
# DB_CONNECTION=sqlite
# atau konfigurasi MySQL Anda

# 5. Jalankan migrasi dan isi data awal
php artisan migrate --seed

# 6. Buat tautan penyimpanan (storage link untuk foto)
php artisan storage:link

# 7. Jalankan server lokal
php artisan serve
```

Akses website melalui browser di: `http://localhost:8000`

---

## ☁️ Konfigurasi Deployment (Production di Railway)

Proyek ini telah dikonfigurasi penuh untuk berjalan di **Railway**:

1. **Koneksi GitHub:** Setiap `git push` ke branch `main` akan otomatis memicu redeploy di Railway.
2. **Environment Variables Wajib di Railway (`Variables` tab):**
   ```env
   APP_NAME="Toko Kue Lala"
   APP_ENV=production
   APP_KEY=base64:...
   APP_DEBUG=false
   APP_URL=https://toko-kue-lala-production.up.railway.app
   LOG_CHANNEL=stderr
   LOG_LEVEL=error

   DB_CONNECTION=mysql
   DB_HOST=mysql.railway.internal
   DB_PORT=3306
   DB_DATABASE=railway
   DB_USERNAME=root
   DB_PASSWORD=...

   SESSION_DRIVER=cookie
   CACHE_STORE=file
   FILESYSTEM_DISK=public
   ```
3. **Persistensi File Unggahan:** Tambahkan **Railway Volume** yang di-mount ke `/app/storage/app/public` agar foto produk dan bukti bayar tetap aman saat redeploy.

---

## 📂 Struktur Direktori Utama

```text
toko-kue-lala/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/            # Controller Kasir, Produk, Verifikasi, Laporan
│   │   ├── Auth/             # Login & Register multi-identitas
│   │   ├── CartController.php
│   │   ├── OrderController.php
│   │   └── StorefrontController.php
│   ├── Models/               # Model Eloquent (Product, Order, DirectSale, dll.)
│   └── Policies/             # Otorisasi hak akses pesanan
├── database/
│   ├── migrations/           # 17 migrasi tabel database
│   └── seeders/              # Data awal menu kue, kategori, pengguna
├── docs/                     # Dokumen Analisis & Implementasi Sprint
├── resources/
│   └── views/
│       ├── admin/            # Tampilan Dashboard, Kasir POS, Verifikasi
│       ├── layouts/          # Layout Admin, Storefront, Print Struk
│       └── storefront/       # Katalog, Keranjang, Checkout, Lacak Pesanan
└── routes/
    └── web.php               # Seluruh rute aplikasi (Storefront, Auth, Admin)
```

---

## 🔒 Keamanan & Perlindungan Data

- **Proteksi CSRF:** Dilengkapi token CSRF pada seluruh transaksi formulir.
- **Validasi Server-Side:** Total harga, subtotal item, dan ketersediaan dihitung ulang di sisi server (tidak mengandalkan input dari klien).
- **Sanitasi File:** Validasi ketat format file bukti transfer (hanya format gambar dengan ukuran maksimal 2 MB) dan penamaan file acak (`Str::random`) untuk mencegah penimpaan file.
- **Role-Based Access Control (RBAC):** Pemisahan hak akses ketat antara Pemilik, Staf Kasir, Staf Produksi, dan Pelanggan.

---

## 📄 Lisensi

Dikembangkan untuk kebutuhan operasional **Toko Kue Lala**. Hak cipta dilindungi.
