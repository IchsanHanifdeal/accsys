📊 AccSystem - Sistem Informasi Akuntansi & Keuangan

Aplikasi akuntansi berbasis web yang dirancang untuk menangani pencatatan keuangan Koperasi, UMKM, dan Perusahaan Jasa. Dibangun dengan fokus pada kecepatan input jurnal, validasi balance otomatis, dan pelaporan real-time.

🛠 Teknologi

Backend: Laravel 12 (PHP 8.2+)

Frontend: Blade Templates

Styling: Tailwind CSS + DaisyUI

Interactivity: Alpine.js (Lightweight JS) + SweetAlert2

Database: MySQL / MariaDB

Charts: ApexCharts

📋 Prasyarat Sistem

Sebelum melakukan instalasi, pastikan lingkungan server/lokal Anda memenuhi syarat berikut:

PHP >= 8.1 (Disarankan 8.2) dengan ekstensi: bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml.

Composer (Dependency Manager).

Node.js & NPM (Untuk compile aset frontend).

MySQL atau MariaDB.

🚀 Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan lokal (Localhost):

1. Clone Repositori

git clone [https://github.com/IchsanHanifdeal/accsys.git](https://github.com/IchsanHanifdeal/accsys.git)
cd accsys


2. Install Dependencies

Install paket PHP dan Node modules yang dibutuhkan.

composer install
npm install


3. Konfigurasi Environment

Salin file konfigurasi contoh dan buat file .env baru.

cp .env.example .env


Buka file .env dan sesuaikan pengaturan database Anda:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=


4. Generate Application Key

php artisan key:generate


5. Setup Database & Seeding

Jalankan migrasi untuk membuat tabel. Gunakan --seed untuk mengisi data awal (Tipe Akun, COA standar, dan Simulasi Jurnal).

# Opsi 1: Migrasi bersih + Data Dummy (Disarankan untuk dev)
php artisan migrate:fresh --seed

# Opsi 2: Jika ingin menjalankan seeder spesifik saja
php artisan db:seed --class=SimulationSeeder


Catatan: SimulationSeeder akan membuat akun standar (Kas, Piutang, Pendapatan) dan contoh transaksi pencairan/angsuran koperasi.

6. Compile Aset Frontend

Compile file CSS dan JS (Tailwind/DaisyUI).

npm run build


(Gunakan npm run dev jika sedang dalam tahap development)

7. Jalankan Server

php artisan serve


Akses aplikasi melalui: http://127.0.0.1:8000

🧪 Fitur Utama

1. Dashboard Keuangan

Ringkasan Aset, Laba Rugi, dan Grafik Arus Kas (ApexCharts) secara real-time.

Shortcut akses cepat ke fitur krusial.

2. Chart of Accounts (COA)

Manajemen akun induk dengan kodefikasi otomatis.

Pengelompokan berdasarkan Tipe Akun (Aset, Kewajiban, Ekuitas, Pendapatan, Beban).

3. Jurnal Umum Pintar

Input Multi-row: Tambah baris debit/kredit tanpa batas.

Kalkulator Simulasi: Generate jurnal otomatis untuk kasus Pencairan & Angsuran Koperasi (Pokok + Bunga).

Validasi Ketat: Sistem menolak penyimpanan jika Debit & Kredit tidak balance (Unbalanced Journal Protection).

4. Laporan & Riwayat

Riwayat jurnal lengkap dengan filter periode tanggal dan pencarian.

Modal detail transaksi untuk melihat rincian akun tanpa reload halaman.

🔧 Troubleshooting Umum

1. Permission Denied pada folder storage
Jika terjadi error permission saat upload atau log, jalankan:

chmod -R 775 storage bootstrap/cache


2. Tampilan CSS berantakan / tidak muncul
Pastikan Anda sudah menjalankan npm run build. Jika menggunakan Laragon/XAMPP virtual host, pastikan APP_URL di .env sudah sesuai dengan domain lokal (misal: http://accsys.test).

3. Error "Vite manifest not found"
Hapus folder public/build lalu jalankan ulang build asset.

rm -rf public/build
npm run build
