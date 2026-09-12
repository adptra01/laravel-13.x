# KateringKu — Platform Marketplace Katering (Laravel)

**KateringKu** adalah aplikasi berbasis Laravel yang dirancang untuk menghubungkan penyedia jasa katering (**Merchant**) dengan pelanggan (**Customer**), serta menyediakan panel kontrol untuk pengelola sistem (**Admin**).

---

## 🚀 Fitur Utama

* **Multi-Role Access**
  Mendukung hak akses terpisah untuk **Admin**, **Merchant**, dan **Customer**.

* **Verifikasi Merchant**
  Menyediakan alur persetujuan akun Merchant oleh Admin sebelum merchant dan produknya dapat ditampilkan secara publik.

* **Katalog & Manajemen Menu**
  Merchant dapat mengelola menu makanan, stok, harga, dan kategori.

* **Pencarian & Pemesanan**
  Customer dapat mendaftar, menjelajahi merchant yang telah terverifikasi, dan melakukan pemesanan.

* **Storage & Automatic Seeding**
  Seeder dapat mengunduh gambar sampel secara otomatis untuk kebutuhan demo aplikasi.

---

## 📋 Akun Demo

Seluruh akun demo menggunakan password default:

```text
password
```

| Role           | Email                   | Status / Keterangan                       |
| -------------- | ----------------------- | ----------------------------------------- |
| **Admin**      | `admin@testing.com`     | Pengelola platform & verifikasi merchant  |
| **Merchant 1** | `merchant1@testing.com` | Dapur Nusantara Catering — **Verified**   |
| **Merchant 2** | `merchant2@testing.com` | Sehat Rasa Catering — **Verified**        |
| **Merchant 3** | `merchant3@testing.com` | Catering Prima — **Pending Verification** |
| **Customer**   | `customer@testing.com`  | Rina Kusuma (PT Maju Jaya)                |

---

## 🛠️ Panduan Instalasi

Tersedia dua metode instalasi. Pilih metode yang sesuai dengan lingkungan pengembangan yang digunakan.

### Prasyarat

Pastikan perangkat pengembangan telah memiliki:

* PHP
* Composer
* Node.js & NPM
* MySQL atau database yang kompatibel
* Git

Untuk metode **DDEV**, pastikan DDEV telah terpasang dan dapat digunakan dari terminal.

---

## Cara 1 — Menggunakan DDEV (Rekomendasi)

Jika menggunakan [DDEV](https://ddev.readthedocs.io/), ikuti langkah berikut.

### 1. Clone Repository

```bash
git clone https://github.com/adptra01/laravel-13.x.git
cd laravel-13.x
```

### 2. Start DDEV Environment

```bash
ddev start
```

### 3. Install Dependencies

```bash
ddev composer install
npm install
npm run build
```

### 4. Konfigurasi Environment

Pastikan file `.env` tersedia dan konfigurasi database sesuai dengan environment DDEV.

Kemudian generate application key:

```bash
ddev exec php artisan key:generate
```

### 5. Migrasi Database & Seeding

Untuk membuat database dari awal sekaligus memasukkan data demo:

```bash
ddev exec php artisan migrate:fresh --seed
```

### 6. Buat Storage Link

```bash
ddev exec php artisan storage:link
```

### 7. Akses Aplikasi

Buka browser dan kunjungi:

```text
https://laravel-test.ddev.site:8443
```

> **Catatan:** URL di atas mengikuti konfigurasi hostname/port DDEV pada project. Jika konfigurasi DDEV berbeda, gunakan URL yang ditampilkan oleh perintah `ddev describe`.

---

## Cara 2 — Setup Manual / Local Environment

Metode ini dapat digunakan jika menjalankan aplikasi secara langsung menggunakan PHP, Composer, MySQL, dan Node.js tanpa DDEV.

### 1. Clone Repository

```bash
git clone https://github.com/adptra01/laravel-13.x.git
cd laravel-13.x
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install & Build Frontend Assets

```bash
npm install
npm run build
```

### 4. Konfigurasi Environment

Salin `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Kemudian generate application key:

```bash
php artisan key:generate
```

Sesuaikan konfigurasi database pada file `.env`, misalnya:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi Database & Seeding

Untuk membuat database dari awal sekaligus memasukkan data demo:

```bash
php artisan migrate:fresh --seed
```

### 6. Buat Storage Link

```bash
php artisan storage:link
```

### 7. Jalankan Development Server

```bash
php artisan serve
```

Aplikasi dapat diakses melalui:

```text
http://127.0.0.1:8000
```

---

## 📝 Catatan Pengembang

### Sistem Seeding Gambar

Gambar produk dan banner yang digunakan oleh seeder diunduh secara otomatis melalui jaringan.

Apabila proses pengunduhan gagal karena masalah koneksi atau sumber gambar tidak dapat diakses, proses seeding akan tetap dilanjutkan tanpa menghentikan keseluruhan proses (**graceful fallback**).

> Pastikan koneksi internet tersedia ketika menjalankan seeding jika ingin mendapatkan gambar sampel secara lengkap.
