<p align="center">
  <img src="public/assets/logo.png" width="120" alt="HANDMAN Logo">
</p>

<h1 align="center">HANDMAN</h1>
<p align="center"><strong>Sistem Manajemen Tugas Kantor & Kolaborasi Real-Time</strong></p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Vite-8.x-blueviolet?style=for-the-badge&logo=vite" alt="Vite 8">
  <img src="https://img.shields.io/badge/Tailwind--v4.0.0--beta.3-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS v4">
  <img src="https://img.shields.io/badge/WebSocket-Laravel_Reverb-blue?style=for-the-badge&logo=websocket" alt="Laravel Reverb">
</p>

---

## 📌 Tentang HANDMAN

**HANDMAN** adalah platform manajemen tugas (task management) dan kolaborasi internal perusahaan yang dirancang untuk meningkatkan efisiensi dan transparansi alur kerja tim. Aplikasi ini membagi peran pengguna menjadi tiga tingkatan: **Admin**, **Manager**, dan **Staff**, dengan dukungan komunikasi real-time untuk pemantauan tugas secara instan.

---

## 🚀 Fitur Utama

### 1. Manajemen & Pemantauan Tugas (Real-Time)
* **Manager**: Mendelegasikan tugas ke staff tertentu atau grup kerja (departemen), mengatur prioritas (Tinggi, Sedang, Rendah), tenggat waktu (deadline), menulis deskripsi, meninjau hasil kerja staff, serta melakukan revisi.
* **Staff**: Menerima penugasan secara langsung, mengunggah hasil kerja (gambar, dokumen, tautan workspace), dan melihat catatan revisi dari manager.

### 2. Kalender Agenda Kerja & Catatan Harian
* Kalender interaktif untuk mencatat agenda, tugas, dan jadwal kerja harian staff serta manager.
* Menampilkan catatan personal atau pengingat kerja yang dapat ditambah, diedit, dan dibatalkan secara dinamis.

### 3. Laporan Masalah (Ticketing System)
* Staff dan Manager dapat mengirimkan laporan kendala operasional langsung ke Admin.
* Admin dapat merespons dan menjawab laporan tersebut secara real-time. Status laporan akan diperbarui otomatis (`Menunggu` -> `Selesai`).

### 4. Ekspor PDF Terfilter & Terurut
* Manajer dan Admin dapat mengekspor laporan monitoring tugas ke dalam format PDF yang secara dinamis mengikuti kriteria penyaringan (Status, Prioritas, Kategori, Departemen) dan pengurutan tanggal tugas.

---

## 🛠️ Teknologi & Arsitektur Utama

Aplikasi ini dibangun menggunakan arsitektur modern berkinerja tinggi:

* **Laravel 11 (PHP 8.2+)**: Framework utama untuk menangani logika bisnis, ORM (Eloquent), otentikasi, validasi, dan routing.
* **Laravel Reverb (WebSockets)**: Server WebSocket berkinerja tinggi bawaan Laravel untuk menangani broadcasting event real-time (notifikasi tugas baru, perubahan status tugas, respon laporan, dan alert global).
* **Vite & Tailwind CSS v4**: Bundler aset ultra-cepat dikombinasikan dengan sistem utilitas CSS Tailwind v4 untuk menghasilkan antarmuka pengguna (UI/UX) yang premium, responsif, dan dinamis.
* **AJAX (Axios & Vanilla Fetch)**: Digunakan untuk validasi formulir real-time (tanpa refresh halaman), interaksi kalender, pengiriman notifikasi, dan pengelolaan file unggahan.
* **Pest / PHPUnit**: Pengujian otomatis (automated testing) untuk memvalidasi fitur filter tugas, validasi profil, otentikasi OTP, dan pengelolaan grup kerja.

---

## ⚙️ Cara Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah di bawah ini untuk menjalankan HANDMAN secara lokal di komputer Anda:

### 1. Prasyarat (Prerequisites)
Pastikan Anda sudah menginstal:
* PHP versi 8.2 atau yang lebih baru
* Composer
* Node.js & NPM
* Database server (MySQL / MariaDB)

### 2. Kloning Repositori
```bash
git clone https://github.com/username/HANDMAN.git
cd HANDMAN
```

### 3. Instal Dependensi
**Dependensi PHP (Backend):**
```bash
composer install
```

**Dependensi JavaScript (Frontend):**
```bash
npm install
```

### 4. Konfigurasi Environment File
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi koneksi database Anda di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=handman
DB_USERNAME=root
DB_PASSWORD=
```

Konfigurasikan driver broadcast untuk menggunakan **Laravel Reverb**:
```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=xxxxxx
REVERB_APP_KEY=xxxxxx
REVERB_APP_SECRET=xxxxxx
REVERB_HOST="127.0.0.1"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${VITE_REVERB_PORT}"
VITE_REVERB_SCHEME="${VITE_REVERB_SCHEME}"
```

### 5. Generate Application Key & Create Symlink
```bash
php artisan key:generate
php artisan storage:link
```

### 6. Migrasi & Seed Database
Jalankan migrasi tabel beserta data seed awal (akun admin, manager, staff bawaan):
```bash
php artisan migrate:refresh --seed
```

### 7. Jalankan Server Aplikasi
Jalankan dev server PHP:
```bash
php artisan serve
```

Jalankan server **Laravel Reverb (WebSocket)** di terminal terpisah:
```bash
php artisan reverb:start
```

Jalankan dev build server **Vite** untuk kompilasi aset real-time:
```bash
npm run dev
```

Buka browser Anda di alamat `http://127.0.0.1:8000`.

---

## 🧪 Pengujian Otomatis (Testing)
Untuk menjalankan suite pengujian otomatis (automated testing) menggunakan Pest:
```bash
php artisan test
```

