# Panduan Deployment & Hosting — Toko Kue Lala

Dokumen ini berisi panduan lengkap untuk mempublikasikan (hosting) aplikasi web **Toko Kue Lala** agar dapat diakses online oleh pelanggan dan staf toko.

---

## 1. Rangkuman Konfigurasi yang Sudah Disiapkan

Seluruh kode dan konfigurasi telah diaudit dan disiapkan untuk production:
- ✅ **Root `.htaccess` Dibuat:** Memastikan domain utama otomatis mengarah ke folder `/public` dan memblokir akses publik ke file sensitif (`.env`, `storage/logs/`, `composer.json`, dll).
- ✅ **Keamanan Production & HTTPS:** Konfigurasi `TrustProxies` diaktifkan di `bootstrap/app.php` agar deteksi SSL/HTTPS, Cloudflare, dan reverse proxy bekerja otomatis tanpa kendala mixed-content.
- ✅ **Storage & Image Upload:** Fallback route `/storage/{path}` disiapkan agar foto kue dan bukti transfer tetap bisa diakses meskipun hosting membatasi fungsi *symlink*.
- ✅ **Timezone & Locale:** Dikonfigurasi otomatis ke `Asia/Jakarta` (WIB) dan Bahasa Indonesia (`id`).
- ✅ **Otomatisasi Deployment:** Disediakan perintah `php artisan app:deploy` dan script `deploy.sh` untuk VPS, serta `setup-hosting.php` untuk shared hosting cPanel tanpa SSH.
- ✅ **Cache & Session:** Siap menggunakan driver `database` atau `file` yang kompatibel di semua jenis hosting.

---

## 2. Hal yang Perlu Dikonfigurasi Manual oleh Anda

Anda hanya perlu mengatur **1 file saja (`.env`)** di hosting:

1. **Domain:**
   ```env
   APP_URL=https://nama-domain-anda.com
   ```
2. **Koneksi Database:**
   Buat database MySQL baru di cPanel / panel hosting Anda, lalu masukkan datanya:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_di_cpanel
   DB_USERNAME=nama_user_database_di_cpanel
   DB_PASSWORD=password_user_database_di_cpanel
   ```
3. **Mode Production:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```
4. **App Key:**
   Salin nilai `APP_KEY` dari file `.env` lokal Anda ke `.env` di hosting, atau jalankan `php artisan key:generate`.

---

## 3. Panduan Langkah Demi Langkah Deployment

### METODE A: cPanel / Shared Hosting (Paling Direkomendasikan untuk Pemula)

1. **Kompresi File Project:**
   - Di komputer lokal, compress seluruh isi folder project `toko-kue-lala` menjadi file `.zip`.
   - *Catatan:* Folder `vendor` boleh disertakan langsung agar tidak perlu menjalankan `composer install` di hosting.

2. **Upload ke cPanel:**
   - Login ke cPanel hosting Anda.
   - Buka menu **File Manager**.
   - Masuk ke folder `public_html/`.
   - Klik **Upload**, lalu pilih file `.zip` tadi.
   - Setelah ter-upload, klik kanan pada file `.zip` dan pilih **Extract**.

3. **Buat Database MySQL di cPanel:**
   - Di cPanel, buka menu **MySQL Databases** atau **MySQL Database Wizard**.
   - Buat nama database baru (misal: `u1234_kuelala`).
   - Buat user database baru dan buat password yang kuat.
   - Hubungkan user ke database dengan memilih **All Privileges**.

4. **Konfigurasi File `.env`:**
   - Di File Manager cPanel, pastikan opsi *Show Hidden Files (dotfiles)* diaktifkan pada Settings.
   - Edit file `.env` (atau rename `.env.example` menjadi `.env`).
   - Masukkan `APP_URL`, data database MySQL yang baru dibuat, dan pastikan `APP_DEBUG=false`.

5. **Inisialisasi Otomatis via Browser (Tanpa Perlu Terminal SSH):**
   - Buka browser dan akses: `https://nama-domain-anda.com/setup-hosting.php`
   - Halaman diagnostic Toko Kue Lala akan terbuka:
     1. Klik tombol **"📦 Migrasi & Isi Data Menu Awal"** (database akan otomatis terisi 8 menu kue asli dan akun demo).
     2. Klik tombol **"🔗 Buat Storage Link"** (menghubungkan folder foto produk).
     3. Klik tombol **"⚡ Optimasi Cache Production"**.
     4. Terakhir, klik tombol merah **"🗑️ Hapus File Setup Ini"** demi keamanan.

6. **Aktifkan SSL (HTTPS):**
   - Di cPanel, buka menu **SSL/TLS Status** atau **Let's Encrypt**.
   - Centang domain Anda dan klik **Run AutoSSL** agar website memiliki ikon gembok aman (HTTPS).

---

### METODE B: VPS / Cloud Hosting (Ubuntu / Debian + Nginx / Apache)

Jika Anda menggunakan VPS (seperti DigitalOcean, Linode, AWS EC2, IDCloudHost VPS):

1. **Clone atau Upload Source Code ke VPS:**
   ```bash
   cd /var/www
   git clone <repo-url> toko-kue-lala
   cd toko-kue-lala
   ```

2. **Setup File `.env`:**
   ```bash
   cp .env.example .env
   nano .env
   # Masukkan APP_KEY, nama domain, dan konfigurasi database MySQL
   ```

3. **Jalankan Script Deployment Otomatis:**
   ```bash
   chmod +x deploy.sh
   ./deploy.sh
   ```
   *Jika database masih kosong dan ingin langsung memasukkan menu kue toko:*
   ```bash
   php artisan app:deploy --seed
   ```

4. **Konfigurasi Virtual Host Nginx:**
   Arahkan `root` Nginx ke direktori `/var/www/toko-kue-lala/public`:
   ```nginx
   server {
       listen 80;
       server_name tokokuelala.com www.tokokuelalala.com;
       root /var/www/toko-kue-lala/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";

       index index.php;
       charset utf-8;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```
5. **Pasang Sertifikat SSL Gratis (Certbot):**
   ```bash
   sudo certbot --nginx -d tokokuelala.com -d www.tokokuelalala.com
   ```

---

## 4. Akun Default Toko Kue Lala Setelah Migrasi

Setelah migrasi awal dijalankan, akun admin dan kasir langsung siap digunakan:
- **Akun Pemilik Toko:**
  - Email: `pemilik@tokokuelalala.id`
  - Password: `password`
- **Akun Kasir:**
  - Email: `kasir@tokokuelalala.id`
  - Password: `password`
- **Akun Staf Dapur:**
  - Email: `produksi@tokokuelalala.id`
  - Password: `password`

*(Disarankan langsung mengganti kata sandi pemilik toko melalui menu profil setelah login pertama kali).*

---

## 5. Rekomendasi Jenis Hosting

1. **Shared Hosting / Cloud Hosting cPanel (Sangat Direkomendasikan untuk Klien UMKM):**
   - **Penyedia Lokal Indonesia:** DomaiNesia, Niagahoster, Rumahweb, Dewaweb, IDCloudHost.
   - **Paket yang Dibutuhkan:** Paket Shared Hosting reguler atau Cloud Hosting (pilih paket yang mendukung PHP 8.2 atau 8.3).
   - **Keunggulan:** Biaya terjangkau (Rp 20.000 – Rp 50.000/bulan), sudah termasuk domain/SSL gratis, email toko, backup otomatis, dan mudah dikelola tanpa perlu pengetahuan sysadmin.

2. **VPS Cloud (Jika Trafik Pemesanan Sangat Tinggi):**
   - **Penyedia:** IDCloudHost VPS, DigitalOcean (Droplet \$6/bln), Linode, Vultr.
   - **Keunggulan:** Akses root penuh, performa maksimal, resource dedicated.
