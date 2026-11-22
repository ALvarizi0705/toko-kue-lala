# DOKUMEN IMPLEMENTASI DAN KODE MODUL

## SISTEM INFORMASI PEMESANAN DAN PENGELOLAAN TOKO KUE LALA BERBASIS WEB
### Versi 0.1 (Sprint 1)

---

| | |
|---|---|
| **Nama Klien** | Toko Kue Lala (Lala Komalasari) |
| **Kelompok / Kelas** | Kelompok 4 / 4KA22 |
| **Disusun oleh** | Luky Prastyo (10123616) · Mohammad Dzaki An Nadhif Islamy (10123690) · Muhammad Alfan Naufal (10123703) · Muhammad Alvarizi (10123747) |
| **Repository Git** | `<<URL repository GitHub>>` |
| **Server Uji** | `<<URL Railway / server uji>>` |
| **Dosen Pengampu** | Dr. Lulu Chaerani Munggaran, S.Kom., MMSI. |
| **Tanggal** | 22-11-2026 |

**Program Studi Sistem Informasi**
Fakultas Ilmu Komputer dan Teknologi Informasi
Universitas Gunadarma

---

## RIWAYAT REVISI

| Versi | Tanggal | Sprint | Perubahan |
|---|---|---|---|
| 0.1 | 22-11-2026 | Sprint 1 (Mg 9) | Dokumen awal: Bagian 1–8, modul M1 (Autentikasi), M2 (Katalog & Keranjang), M3 (Pemesanan & Pelacakan) |

---

## DAFTAR ISI

1. Pendahuluan
2. Lingkungan Implementasi
3. Rencana dan Realisasi Sprint
4. Struktur Kode dan Penerapan Arsitektur
5. Implementasi Basis Data
6. Dokumentasi Kode Modul
7. Hasil Implementasi Antarmuka
8. Rencana Pengujian
9. Matriks Keterlacakan Implementasi
10. Kendala dan Tindak Lanjut
11. Referensi dan Pernyataan Penggunaan AI

Lampiran A – Laporan Sprint 1

---

## 1. PENDAHULUAN

### 1.1 Tujuan Dokumen

Dokumen Implementasi dan Kode Modul ini mencatat bagaimana rancangan teknis dalam Software Design Document (SDD) v1.1 diimplementasikan menjadi kode sumber aplikasi web **Sistem Informasi Pemesanan dan Pengelolaan Toko Kue Lala**, mencakup bagaimana setiap modul dikodekan, bagaimana kode tersebut didokumentasikan, dan bagaimana pengujiannya direncanakan.

Dokumen ini ditujukan kepada:
1. **Programmer** – sebagai acuan implementasi dan standar penulisan kode yang berlaku di proyek ini.
2. **Tester** – sebagai dasar penyusunan skenario dan kasus uji teknis Sprint 1 s.d. Sprint 3.
3. **Dosen Pengampu** – sebagai instrumen evaluasi pencapaian Sub-CPMK 03.1.3.
4. **Klien (Lala Komalasari)** – sebagai laporan kemajuan pemrograman yang dapat dipantau setiap sprint.

### 1.2 Ruang Lingkup Implementasi

| Dari SRS / SDD | Jumlah | Sudah Diimplementasikan (Sprint 1) | Keterangan |
|---|---|---|---|
| Use Case Must | 13 | 8 / 13 | UC-01 s.d. UC-07, UC-10 selesai Sprint 1 |
| Use Case Should | 4 | 0 / 4 | Dijadwalkan Sprint 2 |
| Use Case Could | 1 | 0 / 1 | Dijadwalkan Sprint 3 |
| Layar (L-xx) | 15 | 9 / 15 | L-01, L-02, L-03, L-05, L-06, L-07, L-08, L-09, L-04 |
| Tabel basis data | 9 (inti) + 4 (stok) | 13 / 13 | Semua tabel berhasil di-migrate; tabel stok masih kosong, akan diisi Sprint 2 |

**Use case yang selesai Sprint 1:**

| UC | Nama | Prioritas | Status |
|---|---|---|---|
| UC-01 | Login Staf & Pemilik | Must | ✓ Selesai |
| UC-02 | Registrasi Pelanggan | Must | ✓ Selesai |
| UC-03 | Logout | Must | ✓ Selesai |
| UC-04 | Melihat Profil | Must | ✓ Selesai |
| UC-05 | Melihat Katalog & Detail Produk | Must | ✓ Selesai |
| UC-06 | Memesan Kue (Checkout tanpa login) | Must | ✓ Selesai |
| UC-07 | Upload Bukti Pembayaran | Must | ✓ Selesai |
| UC-10 | Melacak Pesanan (publik) | Must | ✓ Selesai |

### 1.3 Referensi

- SRS v1.2 – Sistem Informasi Pemesanan dan Pengelolaan Toko Kue Lala, Kelompok 4 / 4KA22, 24-10-2026.
- SDD v1.1 – Sistem Informasi Pemesanan dan Pengelolaan Toko Kue Lala, Kelompok 4 / 4KA22, 14-11-2026.
- Dokumentasi resmi Laravel Framework v10.x – https://laravel.com/docs/10.x
- Dokumentasi resmi Bootstrap 5.3 – https://getbootstrap.com/docs/5.3/
- Dokumentasi PHP 8.4 – https://www.php.net/manual/en/

---

## 2. LINGKUNGAN IMPLEMENTASI

### 2.1 Perangkat dan Teknologi

| Komponen | Nama dan Versi | Keterangan |
|---|---|---|
| Bahasa pemrograman | PHP 8.4.0 | Rilis stabil terbaru; backward compatible dengan Laravel 13 |
| Framework backend | Laravel Framework 13.39.0 | MVC; Eloquent ORM; Blade templating |
| CSS framework | Bootstrap 5.3 | Mobile-first; grid system responsif |
| DBMS (lokal) | SQLite 3 (via Laravel) | Database lokal pengembangan; file `database/database.sqlite` |
| DBMS (production) | MySQL 8.0 (Railway) | Target deployment production |
| Server uji | `<<URL Railway>>` | Hosting Railway.app; aktif sejak Sprint 1 |
| Repository & cabang | GitHub; cabang `main`, `dev`, `feature/<nama>` | Merge ke `main` hanya melalui Pull Request |
| Editor | Visual Studio Code 1.95+ | Extension: PHP Intelephense, Laravel Blade |
| Alat bantu | Postman, DBeaver / TablePlus | Uji API & inspeksi database |
| Dependensi utama | `barryvdh/laravel-dompdf` *(direncanakan Sprint 3)* | Ekspor PDF laporan; belum aktif Sprint 1 |

> **Catatan Sprint 1:** Dependensi `barryvdh/laravel-dompdf` terdaftar di `composer.json` tetapi ServiceProvider-nya belum diinisialisasi penuh. Perbaikan dijadwalkan Sprint 2 bersamaan dengan pengembangan modul Laporan.

### 2.2 Standar Penulisan Kode

| Aturan | Detail |
|---|---|
| **Penamaan** | `camelCase` untuk nama fungsi/method; `snake_case` untuk nama tabel, kolom, dan variabel; `PascalCase` untuk nama kelas dan model |
| **Prinsip satu fungsi** | Setiap method publik hanya melakukan satu tanggung jawab (Single Responsibility) |
| **DRY** | Fungsi bersama diletakkan di Model atau helper; tidak menyalin logika antar controller |
| **Komentar** | Setiap fungsi publik wajib memiliki docblock PHP (`/** ... */`) yang memuat tujuan, parameter, dan nilai kembali |
| **Format commit** | `feat(UC-XX): deskripsi singkat` / `fix(UC-XX): perbaikan bug` / `docs: perbarui dokumen` |
| **Validasi** | Semua input form divalidasi di sisi server menggunakan `$request->validate()` Laravel sebelum diproses |
| **Keamanan** | Kata sandi di-hash dengan `bcrypt`; kueri menggunakan Eloquent ORM (prepared statement); proteksi CSRF aktif pada semua form |

### 2.3 Panduan Instalasi dan Menjalankan Aplikasi

Langkah-langkah berikut cukup untuk menjalankan aplikasi dari repository tanpa informasi tambahan dari tim:

```
1. git clone <<URL repository>>
   cd toko-kue-lala

2. Salin file konfigurasi:
   cp .env.example .env

3. Isi konfigurasi database di .env:
   DB_CONNECTION=sqlite
   # (database/database.sqlite sudah tersedia)

4. Pasang dependensi PHP:
   composer install

5. Generate application key:
   php artisan key:generate

6. Buat tabel dan data awal:
   php artisan migrate
   php artisan db:seed

7. Buat symlink storage publik:
   php artisan storage:link

8. Jalankan server lokal:
   php artisan serve

9. Akses di browser: http://127.0.0.1:8000

Akun uji (tersedia setelah seeder):
  Pemilik : pemilik@tokokuelalala.id  / password123
  Kasir   : kasir@tokokuelalala.id    / password123
  Produksi: produksi@tokokuelalala.id / password123
```

---

## 3. RENCANA DAN REALISASI SPRINT

### 3.1 Ringkasan Sprint

| Sprint | Minggu | Target (UC / KF) | Realisasi | Status |
|---|---|---|---|---|
| Sprint 1 – Fitur dasar | 9 | UC-01, 02, 03, 05, 06, 07, 10 (Must); M1, M2, M3 | UC-01–07, UC-10 selesai; seluruh layar storefront (L-01–03, L-05–09) dan Dashboard Admin (L-04) aktif | ✓ Selesai |
| Sprint 2 – Fitur manajemen | 10 | UC-08, 09, 11, 12, 14, 17; M4, M5, M6, M8 | *(Akan diisi Sprint 2)* | Belum |
| Sprint 3 – Laporan & final | 12 | UC-18, 19, 20; M7, M9; Ekspor PDF/Excel | *(Akan diisi Sprint 3)* | Belum |

**Realisasi Gantt Chart Sprint 1:**
Seluruh modul Sprint 1 (M1 Autentikasi, M2 Katalog & Keranjang, M3 Pemesanan & Pelacakan) berhasil diselesaikan dalam Minggu 9. Bug `PaymentController` error 500 (Policy belum terdaftar) diperbaiki hari yang sama. Stok bahan baku (M7) dipindah ke Sprint 2 atas permintaan klien sejak awal sprint. Realisasi: **100% dari target Sprint 1**.

### 3.2 Perubahan terhadap SDD

| No | Rancangan di SDD | Implementasi | Alasan | Disetujui |
|---|---|---|---|---|
| P-01 | Pelanggan wajib login sebelum memesan | Pemesanan terbuka tanpa login; hanya butuh Nama, No. WA, dan Alamat | Permintaan klien saat review Sprint 1: pelanggan tidak perlu membuat akun | Klien, 22-11-2026 |
| P-02 | Pilihan ukuran/varian produk (Diameter 16, 20, 24 cm, dsb.) | Kolom `size` dihilangkan dari form checkout dan tabel `order_items` | Klien menyatakan produk tidak memiliki variasi ukuran standar | Klien, 22-11-2026 |
| P-03 | Halaman instruksi transfer → upload bukti → notifikasi sistem | Setelah checkout, pelanggan diarahkan ke halaman detail pesanan; konfirmasi pembayaran via WhatsApp langsung ke penjual | Alur lebih sederhana; konfirmasi WA langsung ke penjual sesuai proses bisnis nyata | Klien, 22-11-2026 |
| P-04 | Stok bahan baku (M7) Sprint 1 | Ditunda ke Sprint 2 | Klien memprioritaskan alur pesanan online terlebih dahulu | Klien, 22-11-2026 |

---

## 4. STRUKTUR KODE DAN PENERAPAN ARSITEKTUR

### 4.1 Struktur Folder

```
toko-kue-lala/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                      # Controller untuk panel back-office
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── OrderAdminController.php
│   │   │   │   ├── PaymentVerificationController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── DirectSaleController.php
│   │   │   │   ├── ProductionScheduleController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   ├── SettingController.php
│   │   │   │   ├── UserManagementController.php
│   │   │   │   ├── IngredientController.php
│   │   │   │   ├── StockInController.php
│   │   │   │   ├── StockUsageController.php
│   │   │   │   └── StockAdjustmentController.php
│   │   │   ├── Auth/                       # Controller autentikasi
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── StorefrontController.php    # Beranda, Katalog, Detail Produk
│   │   │   ├── CartController.php          # Keranjang belanja (session-based)
│   │   │   ├── OrderController.php         # Checkout, pelacakan pesanan
│   │   │   ├── PaymentController.php       # Upload bukti pembayaran
│   │   │   └── ProfileController.php       # Profil pelanggan
│   │   └── Middleware/
│   │       ├── RoleMiddleware.php          # Cek peran (role) pengguna
│   │       └── CheckActive.php             # Cek status akun aktif setiap request
│   └── Models/
│       ├── User.php           # Pengguna; role enum; login via email/WA
│       ├── Category.php       # Kategori produk kue
│       ├── Product.php        # Produk kue; relasi ke categories; accessor photo_url
│       ├── Order.php          # Pesanan online; state machine VALID_TRANSITIONS
│       ├── OrderItem.php      # Item dalam pesanan; harga dibekukan (D-03 SDD)
│       ├── OrderStatusLog.php # Riwayat perubahan status pesanan
│       ├── DirectSale.php     # Transaksi penjualan kasir langsung
│       ├── DirectSaleItem.php # Item penjualan langsung
│       ├── Setting.php        # Konfigurasi toko (key-value store)
│       ├── Ingredient.php     # Master bahan baku
│       ├── StockIn.php        # Barang masuk
│       ├── StockUsage.php     # Pemakaian bahan
│       └── StockAdjustment.php # Koreksi stok
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── storefront.blade.php   # Layout publik (navbar, footer)
│       │   ├── admin.blade.php        # Layout panel admin (sidebar)
│       │   └── print.blade.php        # Layout cetak nota/laporan
│       ├── storefront/                # Halaman publik pelanggan
│       │   ├── home.blade.php         # L-01 Beranda
│       │   ├── catalog.blade.php      # L-02 Katalog
│       │   ├── product-detail.blade.php  # L-05 Detail Produk
│       │   ├── cart.blade.php         # L-06 Keranjang
│       │   ├── checkout.blade.php     # L-03 Form Checkout
│       │   ├── payment.blade.php      # L-07 Pembayaran
│       │   ├── order-detail.blade.php # L-08 Detail & Status Pesanan
│       │   ├── order-track.blade.php  # Form lacak pesanan
│       │   ├── order-nota.blade.php   # Nota pesanan (cetak)
│       │   ├── my-orders.blade.php    # Riwayat pesanan (login)
│       │   └── profile.blade.php      # Profil pelanggan
│       ├── auth/                      # L-09 Login & Registrasi
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       └── admin/                     # L-04 Dashboard Admin, L-10 s.d. L-15
│           ├── dashboard.blade.php
│           ├── orders/...
│           ├── payment-verification/...
│           ├── production-schedule/...
│           ├── products/...
│           └── ...
├── database/
│   ├── migrations/            # 17 file migration; urutan 000001–000017
│   ├── seeders/               # DatabaseSeeder.php
│   └── database.sqlite        # Database lokal pengembangan
├── routes/
│   └── web.php                # Semua rute web (storefront + admin + auth)
└── tests/
    ├── Feature/ExampleTest.php
    └── Unit/ExampleTest.php
```

### 4.2 Pemetaan Komponen SDD ke Kode

| Komponen SDD | Folder / File | Keterangan |
|---|---|---|
| Modul Auth (M1) | `Controllers/Auth/LoginController`, `RegisterController` | Login via email ATAU nomor WA; hash bcrypt; redirect berdasarkan role |
| Modul Katalog (M2) | `Controllers/StorefrontController`, `views/storefront/catalog` | Filter kategori, pencarian nama, pagination 12 produk/hal |
| Modul Keranjang (M2) | `Controllers/CartController` | Keranjang berbasis session; tidak memerlukan database |
| Modul Pesanan (M3) | `Controllers/OrderController`, `views/storefront/checkout` | Checkout tanpa login; nomor pesanan LALA-YYYYMMDD-XXXX; `DB::transaction()` |
| Modul Pembayaran (M4) | `Controllers/PaymentController`, `views/storefront/payment` | Upload bukti (JPG/PNG/WEBP, maks 2 MB); simpan ke `storage/payments/` |
| Modul Admin Order (M5) | `Controllers/Admin/OrderAdminController`, `PaymentVerificationController` | Verifikasi, update status, state machine `VALID_TRANSITIONS` |
| Modul Produk (M6) | `Controllers/Admin/ProductController`, `CategoryController` | CRUD produk; toggle aktif/nonaktif; upload foto |
| Modul Dashboard | `Controllers/Admin/DashboardController` | Widget KPI: pesanan baru, menunggu verifikasi, produksi hari ini, penjualan hari ini |
| Middleware Keamanan | `Middleware/RoleMiddleware`, `CheckActive` | Proteksi halaman berdasarkan role; paksa logout akun tidak aktif |

### 4.3 Prinsip Pemrograman yang Diterapkan

| Prinsip | Penerapan di Proyek Ini |
|---|---|
| **Modularitas** | Setiap modul memiliki Controller, Model, dan View terpisah; namespace `Admin\` untuk seluruh controller back-office |
| **DRY** | `Setting::get($key, $default)` dipakai di seluruh controller untuk mengambil konfigurasi toko tanpa duplikasi logika |
| **Validasi input server** | Semua form divalidasi di controller menggunakan `$request->validate()` dengan pesan error Bahasa Indonesia sebelum data diproses |
| **Penanganan kesalahan** | Transaksi basis data di `OrderController::store()` menggunakan `DB::transaction()` untuk rollback otomatis jika salah satu langkah gagal |
| **Keamanan (KNF-04)** | Kata sandi di-hash dengan `bcrypt`; Eloquent ORM mencegah SQL Injection; CSRF token aktif; middleware `role` + `active` melindungi seluruh rute admin |
| **Pembekuan harga (D-03 SDD)** | `unit_price` dan `product_name` disalin ke `order_items` saat checkout, bukan diambil dinamis dari tabel `products`, sehingga perubahan harga di masa depan tidak memengaruhi catatan pesanan lama |
| **State machine pesanan** | Konstanta `Order::VALID_TRANSITIONS` mendefinisikan transisi status yang sah; controller admin memvalidasi transisi sebelum menyimpan perubahan |

---

## 5. IMPLEMENTASI BASIS DATA

Skrip pembuatan tabel tersimpan di folder `database/migrations/`. Seluruh 17 migrasi dijalankan secara berurutan menggunakan perintah `php artisan migrate`. Data awal (pengaturan toko, kategori, produk, dan akun staf) tersedia melalui `php artisan db:seed`.

| Tabel | File Migrasi | Sesuai SDD? | Catatan Perubahan |
|---|---|---|---|
| `users` | `000001_create_users_table.php` | Ya | — |
| `categories` | `000002_create_categories_table.php` | Ya | — |
| `products` | `000003_create_products_table.php` | Ya | — |
| `orders` | `000004_create_orders_table.php` | Sebagian | Ditambah kolom `payment_proof` (path file bukti) dan `payment_reject_reason` (alasan tolak); disetujui P-01 |
| `order_items` | `000005_create_order_items_table.php` | Sebagian | Kolom `size` dihapus (P-02); ditambah `cake_text` dan `custom_note` untuk permintaan kustomisasi kue |
| `order_status_logs` | `000006_create_order_status_logs_table.php` | Ya | — |
| `ingredients` | `000007_create_ingredients_table.php` | Ya | Dibuat Sprint 1; akan diisi datanya Sprint 2 |
| `stock_ins` | `000008_create_stock_ins_table.php` | Ya | Dibuat Sprint 1; akan diisi Sprint 2 |
| `stock_usages` | `000009_create_stock_usages_table.php` | Ya | Dibuat Sprint 1; akan diisi Sprint 2 |
| `stock_adjustments` | `000010_create_stock_adjustments_table.php` | Ya | Dibuat Sprint 1; akan diisi Sprint 2 |
| `direct_sales` | `000011_create_direct_sales_table.php` | Ya | — |
| `direct_sale_items` | `000012_create_direct_sale_items_table.php` | Ya | — |
| `settings` | `000013_create_settings_table.php` | Ya | — |

> **[Sisipkan tangkapan layar struktur tabel dari DBeaver / phpMyAdmin / Railway]**
> *Gambar 5.1 Struktur Basis Data Hasil Implementasi*

---

## 6. DOKUMENTASI KODE MODUL

### 6.1 Daftar Modul Sprint 1

| Modul | File Utama | Fungsi / Kelas Utama | UC / KF | Sprint | PIC |
|---|---|---|---|---|---|
| Autentikasi – Login (M1) | `Auth/LoginController.php` | `login()`, `logout()` | UC-01, UC-03 / KF-04 | 1 | Muhammad Alfan Naufal |
| Autentikasi – Registrasi (M1) | `Auth/RegisterController.php` | `register()` | UC-02 / KF-04 | 1 | Muhammad Alfan Naufal |
| Katalog (M2) | `StorefrontController.php` | `index()`, `catalog()`, `show()` | UC-05 / KF-01, KF-02, KF-03 | 1 | Mohammad Dzaki |
| Keranjang (M2) | `CartController.php` | `add()`, `update()`, `remove()`, `clear()` | UC-06 / KF-03 | 1 | Mohammad Dzaki |
| Pemesanan (M3) | `OrderController.php` | `checkout()`, `store()`, `generateOrderNumber()` | UC-06 / KF-04, KF-05 | 1 | Luky Prastyo |
| Pelacakan Pesanan (M3) | `OrderController.php` | `trackForm()`, `trackLookup()`, `trackDetail()` | UC-10 / KF-04 | 1 | Luky Prastyo |
| Pembayaran (M4) | `PaymentController.php` | `show()`, `upload()` | UC-07 / KF-05 | 1 | Muhammad Alvarizi |
| Dashboard Admin | `Admin/DashboardController.php` | `index()` | UC-02 / KF-06 | 1 | Muhammad Alvarizi |
| Middleware Role & Active | `Middleware/RoleMiddleware.php`, `CheckActive.php` | `handle()` | KNF-04, KNF-05 | 1 | Muhammad Alfan Naufal |

---

### 6.2 Spesifikasi Fungsi

---

**Fungsi 1: `LoginController::login()`**

| | |
|---|---|
| **Nama fungsi** | `login(Request $request)` |
| **Modul / file** | Autentikasi / `app/Http/Controllers/Auth/LoginController.php` |
| **Tujuan** | Memvalidasi kredensial pengguna (email ATAU nomor WhatsApp + kata sandi), membuat sesi login, dan mengarahkan ke halaman sesuai role (UC-01, KF-04) |
| **Parameter** | `$request (Request)` – berisi `login` (string: email atau nomor WA) dan `password` (string) |
| **Nilai kembali** | `RedirectResponse` – ke `admin.dashboard` jika staf/pemilik; ke `home` jika pelanggan |
| **Kesalahan ditangani** | Kredensial salah atau akun tidak aktif (`is_active = false`) → `ValidationException` dengan pesan *"Kredensial tidak cocok atau akun tidak aktif"* |
| **Dipanggil oleh** | Layar L-09 Form Login (POST `/login`) |
| **Kasus uji** | TC-01, TC-02, TC-03, TC-04 |

---

**Fungsi 2: `RegisterController::register()`**

| | |
|---|---|
| **Nama fungsi** | `register(Request $request)` |
| **Modul / file** | Autentikasi / `app/Http/Controllers/Auth/RegisterController.php` |
| **Tujuan** | Membuat akun pelanggan baru dengan role `pelanggan` dan status aktif; login otomatis setelah daftar (UC-02, KF-04) |
| **Parameter** | `$request (Request)` – berisi `name`, `phone`, `email`, `password`, `password_confirmation` |
| **Nilai kembali** | `RedirectResponse` ke `home` dengan pesan sambutan |
| **Kesalahan ditangani** | Email sudah terdaftar; nomor WA sudah terdaftar; format WA tidak valid (regex); kata sandi < 8 karakter; konfirmasi kata sandi tidak cocok |
| **Dipanggil oleh** | Layar L-09 Form Registrasi (POST `/register`) |
| **Kasus uji** | TC-05, TC-06 |

---

**Fungsi 3: `StorefrontController::catalog()`**

| | |
|---|---|
| **Nama fungsi** | `catalog(Request $request)` |
| **Modul / file** | Katalog / `app/Http/Controllers/StorefrontController.php` |
| **Tujuan** | Menampilkan daftar produk aktif dengan fitur pencarian nama (`q`) dan filter kategori (`kategori`), dipaginasi 12 produk per halaman (UC-05, KF-01, KF-02) |
| **Parameter** | `$request (Request)` – query string opsional: `q` (pencarian nama), `kategori` (ID kategori) |
| **Nilai kembali** | `View` `storefront.catalog` dengan data `$products` (paginated), `$categories`, `$selectedCategory` |
| **Kesalahan ditangani** | Tidak ada produk yang sesuai filter → tampil pesan *"Produk tidak ditemukan"* di view; kategori tidak valid diabaikan (tidak error) |
| **Dipanggil oleh** | Layar L-02 Katalog (GET `/katalog`) |
| **Kasus uji** | TC-07, TC-08, TC-09 |

---

**Fungsi 4: `CartController::add()`**

| | |
|---|---|
| **Nama fungsi** | `add(Request $request)` |
| **Modul / file** | Keranjang / `app/Http/Controllers/CartController.php` |
| **Tujuan** | Menambahkan produk ke keranjang session; jika produk sudah ada, jumlahnya ditambahkan (tidak membuat entri baru) (UC-06, KF-03) |
| **Parameter** | `$request (Request)` – berisi `product_id` (integer, wajib ada di tabel products) dan `qty` (integer, min 1, maks 100) |
| **Nilai kembali** | `RedirectResponse` back dengan pesan sukses |
| **Kesalahan ditangani** | `product_id` tidak ditemukan → validasi gagal; produk tidak aktif → redirect back dengan error; `qty` ≤ 0 atau > 100 → validasi gagal |
| **Dipanggil oleh** | Tombol "Tambah ke Keranjang" di Layar L-05 Detail Produk (POST `/keranjang/tambah`) |
| **Kasus uji** | TC-10, TC-11, TC-12 |

---

**Fungsi 5: `OrderController::store()`**

| | |
|---|---|
| **Nama fungsi** | `store(Request $request)` |
| **Modul / file** | Pemesanan / `app/Http/Controllers/OrderController.php` |
| **Tujuan** | Membuat pesanan baru dari isi keranjang session; menyimpan Order, OrderItems (dengan pembekuan harga), dan OrderStatusLog pertama dalam satu transaksi database (UC-06, KF-04, KF-05) |
| **Parameter** | `$request (Request)` – berisi `customer_name`, `customer_phone`, `customer_address` (opsional), `pickup_date`, `note` (opsional), `cake_text_<id>`, `custom_note_<id>` per produk |
| **Nilai kembali** | `RedirectResponse` ke halaman detail pesanan (`order.show`) dengan nomor pesanan LALA-YYYYMMDD-XXXX |
| **Kesalahan ditangani** | Keranjang kosong → redirect ke `cart.index`; `pickup_date` di masa lalu atau kurang dari H+minDays → validasi gagal; gagal simpan ke database → transaksi rollback otomatis |
| **Dipanggil oleh** | Tombol "Buat Pesanan" di Layar L-03 Form Checkout (POST `/checkout`) |
| **Kasus uji** | TC-13, TC-14, TC-15 |

---

**Fungsi 6: `PaymentController::upload()`**

| | |
|---|---|
| **Nama fungsi** | `upload(Request $request, Order $order)` |
| **Modul / file** | Pembayaran / `app/Http/Controllers/PaymentController.php` |
| **Tujuan** | Menerima unggahan bukti transfer dari pelanggan, menyimpan file ke `storage/payments/`, dan mengubah status pesanan menjadi `menunggu_verifikasi` (UC-07, KF-05) |
| **Parameter** | `$request (Request)` – berisi `payment_proof` (file gambar); `$order (Order)` – model pesanan target via route model binding |
| **Nilai kembali** | `RedirectResponse` ke `orders.my.detail` dengan pesan sukses |
| **Kesalahan ditangani** | File bukan gambar → ditolak; format selain JPG/JPEG/PNG/WEBP → ditolak; ukuran > 2 MB → ditolak; pesanan bukan milik user saat ini → `403 Forbidden` |
| **Dipanggil oleh** | Layar L-07 Upload Bukti Pembayaran (POST `/pesanan/{order}/bayar`) |
| **Kasus uji** | TC-16, TC-17, TC-18 |

---

**Fungsi 7: `RoleMiddleware::handle()`**

| | |
|---|---|
| **Nama fungsi** | `handle(Request $request, Closure $next, string ...$roles): Response` |
| **Modul / file** | Keamanan / `app/Http/Middleware/RoleMiddleware.php` |
| **Tujuan** | Melindungi rute dari akses tidak sah; memverifikasi bahwa pengguna yang login memiliki role yang diizinkan dan statusnya aktif (KNF-04, KNF-05) |
| **Parameter** | `$request` – HTTP request; `$next` – closure middleware; `$roles` – variadic string daftar role yang diizinkan (`'pemilik'`, `'staf_kasir'`, dst.) |
| **Nilai kembali** | `Response` lanjut ke controller jika lolos; `RedirectResponse` ke `login` jika belum login; HTTP `403` jika role tidak diizinkan |
| **Kesalahan ditangani** | Tidak login → redirect ke login; akun tidak aktif → logout + redirect dengan pesan error; role tidak sesuai → `abort(403)` |
| **Dipanggil oleh** | Semua rute `/admin/*` via alias middleware `role:pemilik,staf_kasir,...` di `routes/web.php` |
| **Kasus uji** | TC-19, TC-20 |

---

### 6.3 Contoh Komentar di Dalam Kode

Setiap fungsi publik memiliki docblock PHP. Contoh dari `OrderController::store()`:

```php
/**
 * Simpan pesanan pelanggan (Nama, No WA, Alamat, Tanggal Ambil).
 *
 * Proses: validasi input → DB::transaction → buat Order → buat OrderItem
 * per produk (harga DIBEKUKAN dari tabel products saat checkout) → hitung
 * total → buat OrderStatusLog pertama → commit → kosongkan cart session.
 *
 * @param  Request  $request  Data form checkout
 * @return RedirectResponse   Redirect ke halaman detail pesanan (nomor LALA-...)
 *
 * @throws ValidationException  Jika field wajib kosong atau pickup_date < H+minDays
 */
public function store(Request $request)
{
    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Keranjangmu masih kosong.');
    }

    // Hitung tanggal minimum pengambilan dari pengaturan toko (setting: min_order_days)
    $minDays = (int) Setting::get('min_order_days', 1);
    $minDate = now()->addDays($minDays)->toDateString();

    $request->validate([
        'customer_name'  => 'required|string|max:100',
        'customer_phone' => 'required|string|max:20',
        'pickup_date'    => 'required|date|after_or_equal:' . $minDate,
        // ...
    ]);

    DB::transaction(function () use ($request, $cart, &$order) {
        // Buat record Order terlebih dahulu (total = 0 sementara)
        $order = Order::create([ /* ... */ ]);

        // Iterasi keranjang: buat OrderItem dengan harga dibekukan
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            $unitPrice = (float) $product->price; // Pembekuan harga (D-03 SDD)
            $order->orderItems()->create([
                'product_name' => $product->name, // Nama disalin agar tetap terdokumentasi
                'unit_price'   => $unitPrice,
                // ...
            ]);
        }

        // Update total setelah semua item dihitung
        $order->update(['total' => $total]);

        // Catat log status pertama
        $order->orderStatusLogs()->create([ /* ... */ ]);
    });

    session()->forget('cart'); // Kosongkan keranjang setelah sukses
    return redirect()->route('order.show', $order->order_number)
        ->with('success', 'Pesanan #' . $order->order_number . ' berhasil dibuat!');
}
```

Contoh dari `LoginController::login()`:

```php
/**
 * Login bisa menggunakan email ATAU nomor WhatsApp + kata sandi.
 *
 * Deteksi otomatis: jika input mengandung '@', dianggap email (kolom email);
 * jika tidak, dianggap nomor telepon/WA (kolom phone).
 *
 * @param  Request  $request  Berisi 'login' (email atau WA) dan 'password'
 * @return RedirectResponse   Ke admin.dashboard (staf/pemilik) atau home (pelanggan)
 * @throws ValidationException  Jika kredensial salah atau akun tidak aktif
 */
public function login(Request $request)
{
    $login    = $request->input('login');
    $password = $request->input('password');

    // Tentukan kolom login: email atau nomor WA (phone)
    $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

    if (!Auth::attempt([$field => $login, 'password' => $password, 'is_active' => true], ...)) {
        throw ValidationException::withMessages([
            'login' => 'Kredensial tidak cocok atau akun tidak aktif.',
        ]);
    }

    $request->session()->regenerate();

    // Redirect berbeda berdasarkan role pengguna yang berhasil login
    return Auth::user()->role === 'pelanggan'
        ? redirect()->intended(route('home'))
        : redirect()->intended(route('admin.dashboard'));
}
```

---

## 7. HASIL IMPLEMENTASI ANTARMUKA

| Layar | Mockup SDD | Hasil Implementasi | Sesuai? |
|---|---|---|---|
| L-01 Beranda | Gambar 6.3 SDD | Gambar 7.1 | Ya |
| L-02 Katalog | Gambar 6.4 SDD | Gambar 7.2 | Ya; pagination duplikat (<> dan 1 2) telah diperbaiki |
| L-03 Form Checkout | Gambar 6.5 SDD | Gambar 7.3 | Sebagian; pilihan ukuran dihilangkan sesuai P-02; login tidak wajib sesuai P-01 |
| L-04 Dashboard Admin | Gambar 6.6 SDD | Gambar 7.4 | Ya |
| L-05 Detail Produk | Gambar 6.7 SDD | Gambar 7.5 | Ya |
| L-06 Keranjang | Gambar 6.8 SDD | Gambar 7.6 | Ya |
| L-07 Pembayaran | Gambar 6.9 SDD | Gambar 7.7 | Ya; ditambah info rekening dari tabel `settings` |
| L-08 Lacak Pesanan | Gambar 6.10 SDD | Gambar 7.8 | Ya; timeline status berjalan dengan `order_status_logs` |
| L-09 Login | Gambar 6.11 SDD | Gambar 7.9 | Ya; field tunggal untuk email/WA |

> **[Sisipkan tangkapan layar masing-masing layar dari server uji, termasuk tampilan pesan validasi error]**
> *Gambar 7.1 Layar L-01 Beranda*
> *Gambar 7.2 Layar L-02 Katalog*
> *Gambar 7.3 Layar L-03 Form Checkout (tanpa login, tanpa pilihan ukuran)*
> *Gambar 7.4 Layar L-04 Dashboard Admin*
> *Gambar 7.5 Layar L-05 Detail Produk*
> *Gambar 7.6 Layar L-06 Keranjang*
> *Gambar 7.7 Layar L-07 Pembayaran*
> *Gambar 7.8 Layar L-08 Lacak Pesanan (Timeline Status)*
> *Gambar 7.9 Layar L-09 Login*

---

## 8. RENCANA PENGUJIAN

Rencana ini menjadi dasar Skenario Pengujian dan UAT pada Minggu 13. Kasus uji diturunkan dari kriteria penerimaan use case di SRS v1.2.

### 8.1 Strategi Pengujian

| Jenis Pengujian | Yang Diuji | Pelaksana | Alat | Waktu |
|---|---|---|---|---|
| Unit test | Fungsi per modul (`generateOrderNumber()`, validasi model) | Programmer | PHPUnit (`php artisan test`) | Setiap sprint |
| Integrasi | Alur antar-modul: keranjang → checkout → status log | Tester | PHPUnit Feature Test | Sprint 2–3 |
| Sistem / fungsional | Setiap use case dari ujung ke ujung di server uji | Tester | Manual + checklist | Mg 12–13 |
| Batas dan kesalahan | Input kosong, format salah, tanggal masa lalu, file terlalu besar | Tester | Manual, Postman | Mg 12–13 |
| Non-fungsional | KNF-01 waktu respons < 3 detik; KNF-04 keamanan; KNF-09 responsivitas mobile | Tester | Chrome DevTools, Lighthouse | Mg 13 |
| Regresi | Uji ulang fitur lama setelah perbaikan bug | Tester | PHPUnit | Setiap perbaikan bug |
| UAT | Skenario nyata oleh klien (pesan kue, lacak pesanan, verifikasi kasir) | Klien (Lala Komalasari) | Formulir UAT | Mg 13 |

### 8.2 Daftar Kasus Uji Sprint 1

| ID | UC / KF | Skenario | Data Uji | Hasil yang Diharapkan | Jenis |
|---|---|---|---|---|---|
| TC-01 | UC-01 | Login berhasil dengan email | email: `pemilik@tokokuelalala.id`, password: `password123` | Redirect ke Dashboard Admin | Positif |
| TC-02 | UC-01 | Login berhasil dengan nomor WA | login: `081234567890`, password: `password123` | Redirect ke Dashboard Admin | Positif |
| TC-03 | UC-01 | Login gagal: password salah | email valid, password: `salahsekali` | Pesan "Kredensial tidak cocok..." ditampilkan | Negatif |
| TC-04 | UC-01 | Login gagal: akun tidak aktif | Akun `is_active = false` | Pesan "Kredensial tidak cocok atau akun tidak aktif" | Negatif |
| TC-05 | UC-02 | Registrasi berhasil | name, phone, email, password valid, unik | Akun dibuat; login otomatis; redirect ke Beranda | Positif |
| TC-06 | UC-02 | Registrasi gagal: email duplikat | Email yang sudah terdaftar | Pesan "Email sudah terdaftar" | Negatif |
| TC-07 | UC-05 | Katalog tampil semua produk aktif | Tidak ada filter | Halaman katalog menampilkan produk dengan pagination 12/hal | Positif |
| TC-08 | UC-05 | Filter kategori berhasil | `kategori=1` (Bolu & Kue Panggang) | Hanya produk kategori tersebut yang tampil | Positif |
| TC-09 | UC-05 | Pencarian tidak ditemukan | `q=xyz_produk_tidak_ada` | Halaman tampil kosong dengan pesan informatif | Negatif |
| TC-10 | UC-06 | Tambah ke keranjang berhasil | `product_id=1`, `qty=2` | Item masuk keranjang; pesan sukses muncul | Positif |
| TC-11 | UC-06 | Tambah ke keranjang: qty nol | `product_id=1`, `qty=0` | Validasi gagal; pesan error | Negatif |
| TC-12 | UC-06 | Tambah ke keranjang: produk tidak aktif | Produk `is_active=false` | Pesan "Produk sedang tidak tersedia" | Negatif |
| TC-13 | UC-06 | Checkout berhasil (tanpa login) | `customer_name` = "Budi", `customer_phone` = "08123…", `pickup_date` = besok | Pesanan tersimpan; nomor LALA-YYYYMMDD-XXXX ditampilkan | Positif |
| TC-14 | UC-06 | Checkout gagal: tanggal masa lalu | `pickup_date` = kemarin | Validasi gagal; pesan tanggal tidak valid | Negatif |
| TC-15 | UC-06 | Pesanan pertama hari itu | — | Nomor pesanan berakhiran `-0001` | Batas |
| TC-16 | UC-07 | Upload bukti pembayaran berhasil | File JPG ukuran 500 KB | Status pesanan berubah ke `menunggu_verifikasi` | Positif |
| TC-17 | UC-07 | Upload gagal: format tidak valid | File `.pdf` | Validasi gagal; pesan "Format file yang diterima: JPG, JPEG, PNG, WEBP" | Negatif |
| TC-18 | UC-07 | Upload gagal: ukuran melebihi batas | File JPG ukuran 3 MB | Validasi gagal; pesan "Ukuran maksimal 2 MB" | Batas |
| TC-19 | KNF-04/05 | Akses panel admin tanpa login | GET `/admin/dashboard` tanpa sesi aktif | Redirect ke halaman login | Negatif |
| TC-20 | KNF-05 | Akses panel admin dengan role pelanggan | Sesi login sebagai `pelanggan`, GET `/admin/dashboard` | Respons HTTP 403 Forbidden | Negatif |

### 8.3 Kriteria Lulus dan Penanganan Bug

Pengujian Sprint 1 dianggap **lulus** apabila:
- Seluruh 20 kasus uji di atas menunjukkan hasil yang diharapkan.
- Tidak ada bug dengan prioritas **Tinggi** yang masih terbuka.
- Aplikasi dapat dijalankan dari repository bersih mengikuti Bagian 2.3 tanpa error.

**Penanganan bug:** Bug dicatat sebagai *Issue* di GitHub dengan label `bug` dan prioritas (`tinggi`/`sedang`/`rendah`), ditugaskan ke programmer terkait. Setelah diperbaiki, kasus uji yang terdampak dijalankan ulang (regresi) dan issue ditutup dengan referensi commit perbaikan.

---

## 9. MATRIKS KETERLACAKAN IMPLEMENTASI

| KF | UC | File / Fungsi | Layar | Kasus Uji | Sprint | Status |
|---|---|---|---|---|---|---|
| KF-01 | UC-05 | `StorefrontController::index()`, `catalog()` | L-01, L-02 | TC-07, TC-08, TC-09 | 1 | ✓ Selesai |
| KF-02 | UC-05 | `StorefrontController::show()` | L-05 | TC-07 | 1 | ✓ Selesai |
| KF-03 | UC-06 | `CartController::add()`, `update()`, `remove()` | L-05, L-06 | TC-10, TC-11, TC-12 | 1 | ✓ Selesai |
| KF-04 | UC-01, UC-02 | `LoginController::login()`, `RegisterController::register()` | L-09 | TC-01–TC-06 | 1 | ✓ Selesai |
| KF-05 | UC-06, UC-07 | `OrderController::store()`, `PaymentController::upload()` | L-03, L-07 | TC-13–TC-18 | 1 | ✓ Selesai |
| KF-06 | UC-02, UC-03 | `Admin/DashboardController::index()` | L-04 | TC-19, TC-20 | 1 | ✓ Selesai |
| KF-07 | UC-08 | `Admin/PaymentVerificationController` | L-11 | — | 2 | Belum |
| KF-08 | UC-09 | `Admin/OrderAdminController` | L-10 | — | 2 | Belum |
| KF-09 | UC-12 | `Admin/ProductController` | L-13 | — | 2 | Belum |
| KF-10 | UC-14 | `Admin/CategoryController` | L-13 | — | 2 | Belum |
| KF-14 | UC-17 | `Admin/DirectSaleController` | L-14 | — | 2 | Belum |
| KF-16 | UC-18 | `Admin/ReportController` | L-15 | — | 3 | Belum |

---

## 10. KENDALA DAN TINDAK LANJUT

| Kendala Selama Pemrograman | Dampak | Penyelesaian / Tindak Lanjut |
|---|---|---|
| `PaymentController::authorize()` memanggil Policy yang belum terdaftar di Laravel → error 500 saat buka halaman pembayaran | Halaman L-07 tidak dapat diakses sama sekali | Method `authorize()` diganti sementara dengan pengecekan manual `$order->user_id == auth()->id()`; `OrderPolicy` akan didaftarkan dan diuji Sprint 2 |
| Pagination Katalog menampilkan dua navigator sekaligus (tombol < > dan angka halaman 1 2) | Tampilan halaman tidak sesuai mockup SDD | Ditemukan duplikasi komponen pagination di `catalog.blade.php`; salah satu dihapus |
| Dependensi `barryvdh/laravel-dompdf` terdaftar di `composer.json` tetapi ServiceProvider tidak dikonfigurasi → aplikasi tidak bisa dijalankan sebelum ada perbaikan | Server lokal `php artisan serve` gagal dimulai | Diatasi sementara; perbaikan permanen Sprint 2 saat modul Laporan mulai dikembangkan |
| Navigasi pagination Katalog berukuran terlalu besar karena styling Bootstrap bentrok dengan tema storefront | Tampilan halaman tidak rapi dan tidak sesuai mockup | CSS khusus pagination ditambahkan di `layouts/storefront.blade.php` |

---

## 11. REFERENSI DAN PERNYATAAN PENGGUNAAN AI

**Referensi:**
1. Software Requirements Specification (SRS) v1.2 – Kelompok 4 / 4KA22, 24-10-2026.
2. Software Design Document (SDD) v1.1 – Kelompok 4 / 4KA22, 14-11-2026.
3. Dokumentasi Laravel Framework v10.x – https://laravel.com/docs/10.x
4. Dokumentasi Bootstrap 5.3 – https://getbootstrap.com/docs/5.3/
5. Dokumentasi PHP 8.4 – https://www.php.net/manual/en/

**Pernyataan Penggunaan AI:**

Generative AI (Google Antigravity / Gemini) digunakan sebagai *thought partner* dalam:
- Pembuatan kerangka awal file controller (StorefrontController, OrderController, CartController, PaymentController, LoginController, RegisterController, RoleMiddleware).
- Penulisan docblock PHP dan komentar kode.
- Penyusunan draf dokumen implementasi ini.

Seluruh kode yang dibuat dengan bantuan AI telah ditinjau, diuji, dan disesuaikan oleh anggota tim. Setiap anggota yang mengunggah kode ke repository memahami dan dapat menjelaskan logika kode tersebut secara mandiri.

| Fungsi / File | Dibuat / Dibantu AI | Ditinjau oleh | Catatan |
|---|---|---|---|
| `OrderController::store()` – logika transaksi DB | Ya (draf awal) | Luky Prastyo | Logika pembekuan harga ditambahkan manual; sudah diuji TC-13–TC-15 |
| `LoginController::login()` – deteksi email/WA | Ya (draf awal) | Muhammad Alfan Naufal | Logika `filter_var` disesuaikan manual sesuai kebutuhan |
| Seluruh docblock PHP | Ya | Semua anggota | Disesuaikan dengan parameter dan return value aktual setiap fungsi |
| Dokumen ini | Ya | Muhammad Alfan Naufal | Konten teknis disesuaikan dengan kode sumber aktual proyek |

---

## LAMPIRAN A — LAPORAN SPRINT 1

| | |
|---|---|
| **Sprint / Minggu** | Sprint 1 / Minggu 9 |
| **Periode** | 17-11-2026 s.d. 22-11-2026 |
| **Tujuan Sprint** | Pemilik dan kasir dapat login; pelanggan dapat menelusuri katalog produk, menambah ke keranjang, membuat pesanan online (tanpa login, hanya butuh nama/WA/tanggal ambil), dan melacak status pesanannya secara publik |

### A. Sprint Backlog

| ID | Item (UC / KF / Bug) | PIC | Estimasi (jam) | Status Akhir |
|---|---|---|---|---|
| SB-01 | UC-01 Login (email & WA) + redirect berdasarkan role | Muhammad Alfan Naufal | 5 | ✓ Selesai |
| SB-02 | Middleware `RoleMiddleware` + `CheckActive` | Muhammad Alfan Naufal | 3 | ✓ Selesai |
| SB-03 | UC-02 Registrasi pelanggan | Muhammad Alfan Naufal | 3 | ✓ Selesai |
| SB-04 | UC-05 Katalog produk + filter kategori + pencarian | Mohammad Dzaki | 6 | ✓ Selesai |
| SB-05 | UC-05 Halaman detail produk + produk terkait | Mohammad Dzaki | 3 | ✓ Selesai |
| SB-06 | UC-06 Keranjang session (tambah, update, hapus, kosongkan) | Mohammad Dzaki | 4 | ✓ Selesai |
| SB-07 | UC-06 Form checkout + simpan pesanan (DB::transaction) | Luky Prastyo | 8 | ✓ Selesai |
| SB-08 | UC-10 Halaman lacak pesanan + timeline status | Luky Prastyo | 4 | ✓ Selesai |
| SB-09 | UC-07 Upload bukti pembayaran + ubah status | Muhammad Alvarizi | 5 | ✓ Selesai |
| SB-10 | L-04 Dashboard Admin (widget KPI: pesanan, verifikasi, penjualan hari ini) | Muhammad Alvarizi | 4 | ✓ Selesai |
| SB-11 | 17 migration + seeder data awal toko | Muhammad Alfan Naufal | 4 | ✓ Selesai |
| SB-12 | Bug: `PaymentController` error 500 (Policy belum terdaftar) | Muhammad Alvarizi | 2 | ✓ Selesai |
| SB-13 | Bug: Duplikat pagination di halaman Katalog | Mohammad Dzaki | 1 | ✓ Selesai |

### B. Hasil Demo

| | |
|---|---|
| **Yang didemokan** | Alur lengkap pelanggan dari server uji: Beranda → Katalog (dengan filter & pencarian) → Detail Produk → Tambah ke Keranjang → Checkout (tanpa login, isi nama/WA/tanggal ambil) → Halaman status pesanan dengan nomor LALA-… → Lacak pesanan via nomor tersebut; Login admin/pemilik → Dashboard dengan widget KPI realtime |
| **Masukan dosen** | *(Diisi setelah demo di kelas)* |
| **Masukan klien** | Klien menyetujui alur pesanan tanpa login; konfirmasi bahwa pembayaran via WA langsung ke penjual sudah sesuai ekspektasi; meminta tombol "Pesan Sekarang" di katalog tidak terlalu besar *(sudah diperbaiki sebelumnya)* |
| **Tautan** | Pull Request #`<<nomor PR>>` / Commit / Tag `sprint-1` di repository |

### C. Retrospektif

| Mulai Dilakukan (Start) | Dihentikan (Stop) | Dilanjutkan (Continue) |
|---|---|---|
| Code review singkat sebelum merge ke `main` | Langsung commit ke `main` tanpa Pull Request | Rapat pembagian tugas setiap awal sprint |
| Menulis docblock langsung saat membuat fungsi baru (bukan ditunda) | Menunda komentar kode hingga akhir sprint | Pengujian manual setiap fitur selesai dikoding |
| Mendaftarkan Laravel Policy untuk proteksi endpoint sensitif | — | Komunikasi rutin via grup WhatsApp tim |

### D. Pembaruan Jadwal

Realisasi Gantt Chart Sprint 1 sudah diperbarui: **seluruh 13 item backlog Sprint 1 berhasil diselesaikan tepat waktu (Minggu 9)**. Tidak ada item yang terpaksa dipindah ke Sprint 2 dari Sprint 1. Item stok bahan baku (M7) sudah direncanakan dari awal untuk dimulai Sprint 2 atas kesepakatan dengan klien.
