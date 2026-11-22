# 🎂 Toko Kue Lala - Sistem Informasi Pemesanan & Pengelolaan

Aplikasi web lengkap **Sistem Informasi Pemesanan dan Pengelolaan Toko Kue Lala** (UMKM kue di Jakarta Timur) yang siap digunakan. Dibangun menggunakan arsitektur MVC berbasis Laravel, Bootstrap 5 dengan palet warna hangat-premium, dan MySQL/SQLite.

---

## 🚀 Cara Menjalankan Aplikasi (Lokal)

Jika Anda sudah berada di dalam folder proyek, jalankan langkah berikut di Terminal / PowerShell:

```bash
# 1. Masuk ke direktori aplikasi
cd c:\Users\Pongo\Downloads\video\toko-kue-lala

# 2. Jalankan server lokal Laravel
php artisan serve
```

Setelah server aktif, buka browser Anda (Google Chrome / Microsoft Edge) dan akses URL:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)** atau **[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Akun Demo (Default Password: `password`)

Database telah diisi data contoh lengkap (*seeder*) mencakup produk, bahan baku, pesanan, dan transaksi kasir. Anda dapat masuk menggunakan akun-akun berikut:

| Peran (Role) | Email Login | Nomor WhatsApp Login | Kata Sandi | Hak Akses Utama |
| :--- | :--- | :--- | :--- | :--- |
| **Pemilik** | `pemilik@tokokuelalala.id` | `081234567890` | `password` | Akses penuh, kelola akun staf, laporan keuangan & omset, pengaturan toko |
| **Staf Kasir** | `kasir@tokokuelalala.id` | `081234567891` | `password` | Verifikasi bukti transfer, kelola pesanan & pembatalan, penjualan langsung (POS), cetak nota |
| **Staf Produksi** | `produksi@tokokuelalala.id` | `081234567892` | `password` | Jadwal produksi dapur, ubah status *Diproses* → *Siap Diambil*, mutasi stok bahan baku |
| **Pelanggan 1** | `pelanggan1@email.com` | `081298765432` | `password` | Belanja kue, checkout, upload bukti transfer, lacak pesanan & riwayat |
| **Pelanggan 2** | `pelanggan2@email.com` | `081287654321` | `password` | Belanja kue, checkout, upload bukti transfer, lacak pesanan & riwayat |

> **Catatan Login:** Form login mendukung masuk menggunakan **Email** ATAU **Nomor WhatsApp** + Kata Sandi.

---

## 🛠️ Panduan Instalasi dari Awal (Fresh Install)

Jika ingin memasang aplikasi dari awal di komputer baru atau server:

1. **Clone / Ekstrak Proyek**
   ```bash
   git clone <repo-url> toko-kue-lala
   cd toko-kue-lala
   ```

2. **Install Dependensi Composer & Node**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Environment (`.env`)**
   Salin `.env.example` menjadi `.env`:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
   Atur koneksi database di `.env` (misalnya MySQL atau SQLite):
   ```env
   DB_CONNECTION=sqlite
   # Untuk MySQL:
   # DB_CONNECTION=mysql
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=toko_kue_lala
   # DB_USERNAME=root
   # DB_PASSWORD=
   ```

4. **Migrasi Database & Seeder Data Awal**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Hubungkan Storage untuk Foto Produk & Bukti Transfer**
   ```bash
   php artisan storage:link
   ```

6. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

---

## 🎨 Tema dan Desain Visual
- **Palet Warna:** 
  - Background Utama: Cream (`#FFF8F0`)
  - Teks Judul & Navbar Admin: Choco (`#5B3A29`)
  - Aksen CTA & Harga: Caramel (`#C8873E`)
  - Banner & Highlight: Butter (`#FBE7B5`)
  - Tombol Sekunder & Soft Badge: Pink (`#F4B6C2`) & Rose (`#E58CA0`)
- **Tipografi:** Google Fonts *Playfair Display* (Heading) dan *Poppins* (Body Text).
- **Responsif:** Mobile-first, optimal di layar smartphone (≥ 360px), tablet dapur, dan desktop.

---

## 📋 Fitur Utama Sistem

### A. Toko Publik & Pelanggan
1. **Beranda & Etalase:** Hero banner, kategori kue, produk favorit, 3 langkah mudah memesan, kontak WhatsApp & alamat toko.
2. **Katalog Produk:** Grid produk responsif, pencarian nama cepat (< 3 detik), filter kategori, pagination.
3. **Detail Produk & Keranjang Belanja:** Pilihan ukuran, kalkulasi harga otomatis, session-based cart.
4. **Checkout Pemesanan:** Kustomisasi per item (ukuran, tulisan di kue, catatan khusus), validasi tanggal ambil minimal H+1 (tidak bisa tanggal lampau), pembuatan kode pesanan otomatis format `LALA-YYYYMMDD-XXXX`.
5. **Pembayaran Manual:** Instruksi rekening BCA, DANA, GoPay, dan QRIS statis. Formulir upload bukti transfer (JPG/PNG/WEBP, maks 2MB) → status otomatis berubah menjadi *Menunggu Verifikasi*.
6. **Pesanan Saya & Nota:** Riwayat lengkap, status pelacakan, alasan penolakan jika bukti transfer ditolak kasir, serta tombol cetak nota bersih.

### B. Panel Administrasi (Sesuai Role)
1. **Dashboard Khusus:** Kartu ringkasan pesanan masuk, diproses hari ini, peringatan stok bahan menipis, dan ringkasan omset penjualan (khusus role pemilik).
2. **Verifikasi Pembayaran (Kasir & Pemilik):** Pratinjau bukti transfer, verifikasi *Terima* (status → *Diproses*), atau *Tolak* (wajib mengisi alasan penolakan yang langsung tampil ke pelanggan).
3. **Alur Status Berurutan:** `Menunggu Pembayaran` → `Menunggu Verifikasi` → `Diproses` → `Siap Diambil` → `Selesai`. Lompat status ditolak sistem. Pembatalan hanya dapat dilakukan oleh Pemilik dan Staf Kasir.
4. **Notifikasi WhatsApp:** Tombol kirim pesan otomatis ke nomor WA pelanggan via tautan resmi `wa.me` saat kue siap diambil.
5. **Jadwal Produksi (Dapur):** Tampilan khusus staf produksi berisi kue yang wajib dipanggang berdasarkan urutan tanggal ambil terdekat beserta instruksi kustomisasi tulisan & catatan.
6. **Master & Mutasi Stok Bahan Baku:** Master bahan (kode unik, satuan, stok awal, batas minimum), pencatatan barang masuk supplier, pemakaian bahan produksi, koreksi selisih stok (stock opname), dan daftar peringatan stok menipis otomatis.
7. **Kasir Toko (Penjualan Langsung / POS):** Input transaksi langsung saat pembeli datang ke toko, pemilihan kue dan hitung total di server, metode bayar Tunai/Transfer/QRIS, serta cetak struk format kasir 58mm atau nota A5.
8. **Laporan & Ekspor:** Laporan penjualan harian/bulanan (gabungan pesanan online terverifikasi + penjualan langsung toko), laporan mutasi stok bahan, ekspor format Excel/CSV & PDF, dan visualisasi grafik tren penjualan dengan Chart.js.
9. **Manajemen Akun & Toko:** Tambah/edit staf, aktivasi/nonaktifkan akun, reset kata sandi, pengaturan jam buka toko, nomor kontak, dan minimal hari pemesanan.

---

## 🔒 Keamanan & Non-Fungsional
- **CSRF & XSS:** Proteksi token CSRF pada seluruh form POST/PATCH/DELETE, sanitasi Blade otomatis.
- **Mass-Assignment:** Model dilindungi `$fillable` ketat.
- **Validasi Server-Side:** Total harga dihitung ulang di server (tidak percaya input klien), tanggal ambil divalidasi `after_or_equal`.
- **Upload File:** Validasi mime types (`jpg, jpeg, png, webp`), ukuran maks 2 MB, dan pengacakan nama file (Str::random) ke penyimpanan `storage/app/public`.
- **Role Middleware & Policy:** Hak akses terisolasi per peran; pelanggan hanya dapat melihat data pesanannya sendiri (`OrderPolicy`).

---

## ☁️ Panduan Deploy ke Hosting cPanel / VPS

### 1. Di Shared Hosting (cPanel):
1. Zip seluruh folder proyek (kecuali folder `vendor` dan `node_modules`).
2. Upload dan ekstrak di luar folder `public_html`, misalnya di direktori `/home/username/toko-kue-lala`.
3. Pindahkan seluruh isi folder `toko-kue-lala/public` ke dalam folder `public_html`.
4. Edit file `public_html/index.php`:
   ```php
   require __DIR__.'/../toko-kue-lala/vendor/autoload.php';
   $app = require_once __DIR__.'/../toko-kue-lala/bootstrap/app.php';
   ```
5. Buat database MySQL di cPanel (MySQL Databases) dan masukkan nama DB, user, dan password ke file `.env`.
6. Jalankan migrasi dan seeder via Terminal cPanel atau impor file `.sql`:
   ```bash
   php artisan migrate --force --seed
   php artisan storage:link
   ```

### 2. Backup Database Otomatis (Cron Job):
Tambahkan perintah backup harian melalui Cron Job cPanel:
```bash
# Backup MySQL setiap pukul 02:00 dini hari
0 2 * * * mysqldump -u username_db -ppassword_db nama_database > /home/username/backups/backup_$(date +\%Y\%m\%d).sql
```

---

## ✅ Checklist Uji Fitur (Testing Checklist)

- [x] **Pemesanan Selesai ≤ 5 Menit:** Alur pilih katalog → tambah keranjang → checkout kustomisasi → pesan selesai dalam 2-3 menit.
- [x] **Hak Akses Role:** 
  - Pelanggan tidak bisa mengakses URL `/admin`.
  - Staf produksi tidak bisa melihat menu laporan keuangan dan tidak bisa membatalkan pesanan.
  - Akun nonaktif otomatis tertolak saat login dengan pesan informatif.
- [x] **Validasi Input:**
  - Tanggal ambil lampau atau hari ini ditolak jika konfigurasi H+1.
  - Pemakaian bahan yang melebihi sisa stok riil ditolak sistem.
  - Upload bukti transfer selain gambar ditolak.
- [x] **Peringatan Stok Menipis:** Muncul badge dan daftar peringatan di dashboard dan sidebar saat stok ≤ batas minimum.
- [x] **Laporan Penjualan Akurat:** Menggabungkan pesanan online yang sudah terverifikasi dengan penjualan langsung di toko.
