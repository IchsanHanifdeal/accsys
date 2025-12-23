# AccSystem - Sistem Informasi Akuntansi & Keuangan

Aplikasi akuntansi berbasis web yang dirancang untuk menangani pencatatan keuangan Koperasi, UMKM, dan Perusahaan Jasa. Dibangun menggunakan Laravel dengan fokus pada kecepatan input jurnal, validasi _balance_ otomatis, dan pelaporan _real-time_.

## 🛠 Teknologi

-   **Backend:** Laravel 12 (PHP 8.2+)
-   **Frontend:** Blade Templates
-   **Styling:** Tailwind CSS + DaisyUI
-   **Interactivity:** Alpine.js (Lightweight JS) + SweetAlert2
-   **Database:** MySQL / MariaDB

## 📋 Prasyarat Sistem

Sebelum melakukan instalasi, pastikan lingkungan server/lokal Anda memenuhi syarat berikut:

1.  **PHP** >= 8.1 (Disarankan 8.2) dengan ekstensi: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`.
2.  **Composer** (Dependency Manager).
3.  **Node.js & NPM** (Untuk compile aset frontend).
4.  **MySQL** atau MariaDB.

---

## 🚀 Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan lokal (Localhost):

### 1. Clone Repositori

```bash
git clone [https://github.com/IchsanHanifdeal/accsys](https://github.com/IchsanHanifdeal/accsys)
cd accsystem
```
````

### 2. Install Dependencies

Install paket PHP dan Node modules yang dibutuhkan.

```bash
composer install
npm install

```

### 3. Konfigurasi Environment

Salin file konfigurasi contoh dan buat file `.env` baru.

```bash
cp .env.example .env

```

Buka file `.env` dan sesuaikan pengaturan database Anda:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=

```

### 4. Generate Application Key

```bash
php artisan key:generate

```

### 5. Setup Database & Seeding

Jalankan migrasi untuk membuat tabel. Gunakan `--seed` untuk mengisi data awal (Tipe Akun, COA standar, dan Simulasi Jurnal).

```bash
# Opsi 1: Migrasi bersih + Data Dummy (Disarankan untuk dev)
php artisan migrate:fresh --seed

# Opsi 2: Jika ingin menjalankan seeder spesifik saja
php artisan db:seed --class=SimulationSeeder

```

> **Catatan:** `SimulationSeeder` akan membuat akun standar (Kas, Piutang, Pendapatan) dan contoh transaksi pencairan/angsuran koperasi.

### 6. Compile Aset Frontend

Compile file CSS dan JS (Tailwind/DaisyUI).

```bash
npm run build

```

_(Gunakan `npm run dev` jika sedang dalam tahap development)_

### 7. Jalankan Server

```bash
php artisan serve

```

Akses aplikasi melalui: `http://127.0.0.1:8000`

---

## 🧪 Fitur Utama

-   **Dashboard Keuangan:** Ringkasan Aset, Laba Rugi, dan Grafik Arus Kas (ApexCharts).
-   **Chart of Accounts (COA):** Manajemen akun induk dengan kodefikasi otomatis.
-   **Jurnal Umum Pintar:**
-   Input _multi-row_ dinamis.
-   **Kalkulator Simulasi:** Generate jurnal otomatis untuk kasus Pencairan & Angsuran (Pokok + Bunga).
-   Validasi _Balance_ (Debit vs Kredit) secara _real-time_ (JS) dan _backend_ (DB Transaction).

-   **Laporan:** Riwayat jurnal dengan filter periode dan pencarian.

---

## 🔧 Troubleshooting Umum

**1. Permission Denied pada folder storage**
Jika terjadi error permission saat upload atau log, jalankan:

```bash
chmod -R 775 storage bootstrap/cache

```

**2. Tampilan CSS berantakan / tidak muncul**
Pastikan Anda sudah menjalankan `npm run build`. Jika menggunakan Laragon/XAMPP virtual host, pastikan `APP_URL` di `.env` sudah sesuai dengan domain lokal.

**3. Error "Vite manifest not found"**
Hapus folder `public/build` lalu jalankan ulang `npm run build`.

---

## 🛡️ Lisensi

Properti Intelektual Milik **[Nama Anda/Organisasi]**.
Tidak untuk didistribusikan ulang tanpa izin.

```

### Poin Plus dari Readme ini:
1.  **Struktur Jelas:** Menggunakan *headings* standar (Prasyarat, Install, Troubleshooting).
2.  **Perintah Spesifik:** Menyertakan command line yang tinggal di-*copy paste*.
3.  **Konteks Proyek:** Menyebutkan fitur spesifik yang baru saja Anda buat (`SimulationSeeder`, Kalkulator Simulasi) sehingga relevan dengan kode yang ada.
4.  **Tanpa Basa-basi:** Tidak ada kalimat pembuka seperti *"Halo pengguna yang budiman, ini adalah panduan..."*. Langsung ke teknis.

```
