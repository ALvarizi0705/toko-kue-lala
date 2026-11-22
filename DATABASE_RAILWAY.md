# Panduan Migrasi Database Toko Kue Lala ke Railway MySQL

Dokumen ini menjelaskan secara lengkap dan aman langkah pemindahan struktur dan seluruh data aplikasi **Toko Kue Lala** dari **SQLite lokal (`database/database.sqlite`)** ke **MySQL Production di Railway**, tanpa kehilangan data riil (produk asli, pengguna, pesanan, dan pengaturan toko).

---

## 1. Ringkasan Hasil Audit Database

### 1.1. Audit Migration (17 Tabel)
Seluruh 17 file migration Laravel telah diaudit dan terbukti **100% kompatibel dengan MySQL InnoDB**:
- Menggunakan skema standar Laravel (`id()`, `foreignId()`, `decimal(12, 2)`, `enum()`, `boolean()`, `timestamps()`).
- Indeks foreign key tertata rapi dengan constraint cascade/restrict yang aman.

### 1.2. Data Riil yang Ada di SQLite Lokal (Total 123 Baris)
Berdasarkan hasil pemeriksaan langsung:
- **`settings` (9 baris):** Pengaturan toko, nama toko (*Toko Kue Lala*), nomor WhatsApp Pemilik (`085795580399`), rekening BCA, DANA, GoPay.
- **`categories` (3 baris):** Bolu & Kue Panggang, Kue Tradisional, Snack & Pastry.
- **`products` (8 baris):** 8 menu autentik dari catatan toko (Bolu Marmer, Apem, Bolu Tape, Bolu Caramel S & L, Soes, Lumpur, Pie Buah).
- **`users` (5 baris):** Akun pemilik toko, kasir, produksi, dan 2 pelanggan.
- **`orders` (14 baris):** Data pesanan masuk beserta tanggal pengambilan dan nomor WhatsApp.
- **`order_items` (18 baris):** Rincian item pesanan kue.
- **`order_status_logs` (34 baris):** Riwayat pelacakan status pesanan.
- **`direct_sales` & `direct_sale_items` (10 baris):** Transaksi penjualan langsung kasir di toko.
- **`sessions` (5 baris) & `migrations` (17 baris).**

> [!IMPORTANT]
> **JANGAN JALANKAN `php artisan migrate:fresh` atau `DatabaseSeeder` di production!**
> Menjalankan `migrate:fresh` akan menghapus data, dan `DatabaseSeeder` akan menambahkan data demo dummy yang merusak data asli toko.

---

## 2. Persiapan di Railway

1. Buka dashboard [Railway.app](https://railway.app).
2. Buat proyek baru atau buka proyek yang sudah ada.
3. Klik **+ New Service** -> Pilih **Database** -> Pilih **Add MySQL**.
4. Masuk ke tab **Variables** atau **Connect** pada service MySQL Railway Anda.
5. Catat kredensial koneksi:
   - **`MYSQLHOST`** (atau Public Domain, misal: `roundhouse.proxy.rlwy.net`)
   - **`MYSQLPORT`** (misal: `12345` atau `3306`)
   - **`MYSQLDATABASE`** (biasanya: `railway`)
   - **`MYSQLUSER`** (biasanya: `root`)
   - **`MYSQLPASSWORD`** (password unik dari Railway)
   - **`MYSQL_URL`** (koneksi URL lengkap: `mysql://root:pass@host:port/railway`)

---

## 3. Konfigurasi Environment Variables Laravel

Proyek Laravel Toko Kue Lala telah dioptimalkan di `config/database.php` sehingga **langsung mengenali variabel bawaan Railway**.

Di Railway (Service Web / App Laravel), Anda cukup memasukkan environment variable berikut di tab **Variables**:

```env
APP_NAME="Toko Kue Lala"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nama-proyek-anda.up.railway.app

# Konfigurasi Database Railway MySQL
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

# Driver Production
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

*(Jika Railway App dan MySQL berada dalam satu project, Railway menyediakan fitur referensi variabel otomatis `${{MySQL.MYSQLHOST}}`)*.

---

## 4. Dua Pilihan Metode Pemindahan Data ke Railway MySQL

Pilih salah satu metode berikut yang paling mudah dan nyaman bagi Anda:

---

### METODE A (Paling Mudah): Menggunakan File SQL Siap Pakai

Kami telah menyiapkan file SQL lengkap yang sudah otomatis diekspor dari database lokal:
- **Lokasi File:** `database/railway_toko_kue_lala.sql`
- File ini berisi pembuatan skema 17 tabel MySQL lengkap beserta seluruh 123 baris data riil, dibungkus dengan `SET FOREIGN_KEY_CHECKS = 0;` dan `SET FOREIGN_KEY_CHECKS = 1;` untuk mencegah error relasi.

#### Langkah Eksekusi:
1. Hubungkan database Railway Anda menggunakan aplikasi GUI database seperti **DBeaver**, **TablePlus**, **HeidiSQL**, atau **Navicat**:
   - Host: Kredensial public host dari Railway
   - Port: Kredensial public port dari Railway
   - User: `root`
   - Password: Password dari Railway
   - Database: `railway`
2. Buka file `database/railway_toko_kue_lala.sql` di aplikasi tersebut.
3. Klik tombol **Execute / Run SQL Script**.
4. Seluruh tabel dan data toko langsung aktif seketika!

*Atau menggunakan terminal command line:*
```bash
mysql -h HOST_RAILWAY -P PORT_RAILWAY -u root -p railway < database/railway_toko_kue_lala.sql
```

---

### METODE B: Transfer Otomatis Menggunakan Artisan CLI

Kami telah membuat perintah Artisan kustom `db:transfer-to-mysql` yang menghubungkan database lokal langsung ke database Railway via internet:

```bash
php artisan db:transfer-to-mysql --host=HOST_RAILWAY --port=PORT_RAILWAY --database=railway --username=root --password=PASSWORD_RAILWAY
```

Perintah ini akan secara otomatis:
1. Menguji koneksi ke database Railway MySQL.
2. Menjalankan migrasi skema tabel (`php artisan migrate --database=mysql --force`) **tanpa seeder**.
3. Menonaktifkan Foreign Key Checks sementara.
4. Mentransfer seluruh 123 data riil per tabel berurutan.
5. Mengaktifkan kembali Foreign Key Checks.

---

## 5. Verifikasi Integritas Data (SQLite vs MySQL)

Untuk memastikan tidak ada satupun baris data yang tertinggal atau hilang, kami telah menyediakan perintah verifikasi tabel demi tabel:

```bash
php artisan db:verify-transfer --host=HOST_RAILWAY --port=PORT_RAILWAY --database=railway --username=root --password=PASSWORD_RAILWAY
```

### Hasil yang Diharapkan:
```text
=== VERIFIKASI SINKRONISASI DATA: SQLITE VS MYSQL RAILWAY ===
+-----------------------+-----------------------+-----------------------+-------------------+
| Tabel                 | Baris SQLite (Lokal)  | Baris MySQL (Railway) | Status            |
+-----------------------+-----------------------+-----------------------+-------------------+
| settings              | 9                     | 9                     | ✓ Sesuai (Match)  |
| categories            | 3                     | 3                     | ✓ Sesuai (Match)  |
| users                 | 5                     | 5                     | ✓ Sesuai (Match)  |
| products              | 8                     | 8                     | ✓ Sesuai (Match)  |
| orders                | 14                    | 14                    | ✓ Sesuai (Match)  |
| order_items           | 18                    | 18                    | ✓ Sesuai (Match)  |
| order_status_logs     | 34                    | 34                    | ✓ Sesuai (Match)  |
| direct_sales          | 5                     | 5                     | ✓ Sesuai (Match)  |
| direct_sale_items     | 5                     | 5                     | ✓ Sesuai (Match)  |
| migrations            | 17                    | 17                    | ✓ Sesuai (Match)  |
| sessions              | 5                     | 5                     | ✓ Sesuai (Match)  |
| ingredients           | 0                     | 0                     | ✓ Sesuai (Match)  |
| stock_ins             | 0                     | 0                     | ✓ Sesuai (Match)  |
| stock_usages          | 0                     | 0                     | ✓ Sesuai (Match)  |
| stock_adjustments     | 0                     | 0                     | ✓ Sesuai (Match)  |
+-----------------------+-----------------------+-----------------------+-------------------+
Ringkasan Total Baris:
• Total Baris SQLite: 123
• Total Baris MySQL : 123

✓ VERIFIKASI SUKSES: Seluruh data tabel pada SQLite dan MySQL Railway 100% IDENTIK!
```

---

## 6. Backup & Keamanan Database Lokal

1. Database SQLite asli di `database/database.sqlite` **tetap utuh dan tidak tersentuh**.
2. Setiap kali perintah ekspor dijalankan, backup otomatis dengan timestamp dibuat di folder `database/backups/`. Contoh:
   - `database/backups/database_sqlite_backup_20261122_184144.sqlite`
3. Jika sewaktu-waktu Anda ingin mengekspor ulang data terbaru dari SQLite:
   ```bash
   php artisan db:export-mysql
   ```
