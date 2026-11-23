<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<h1 align="center">Balmon Hertz - Sistem Magang</h1>

<p align="center">
<strong>Sistem pendaftaran dan pelaksanaan magang bagi institur Balmon Hertz</strong>
</p>

<p align="center">
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?logo=laravel&logoColor=white"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-yellow.svg"></a>
</p>

## Tentang Proyek

Aplikasi web berbasis Laravel untuk sistem pendaftaran magang (PKL) mahasiswa. Aplikasi ini memungkinkan peserta daftar lowongan magang, admin mengelola pendaftaran, serta superadmin mengelola seluruh sistem.

## Fitur Utama

### untuk Peserta (tanpa akun/login)
- Buka landing page, lihat 4 lowongan terbaru
- Filter lowongan per tipe, klik Lihat Selengkapnya (modal detail)
- Daftar dengan form: nama, email, no HP, TTL, posisi, upload CV + surat lamaran (PDF/DOCX, maks 2MB)
- Terima nomor lamaran (`MH-2026-XXXX`)
- Cek status di `/status` pakai nomor lamaran atau email: menunggu / diterima / ditolak / perlu perbaikan

### untuk Admin
- Login `/akses-internal` (role `admin`, akun aktif)
- Kelola periode + kuota, bidang magang (CRUD), lowongan (buka/tutup)
- Lihat daftar lamaran (pagination), verifikasi status
- Ubah status: diterima / ditolak (wajib alasan) / perlu perbaikan
- Keputusan status membuat email dari template aktif dan masuk antrean pengiriman
- Kelola penempilan + status PKL (sedang PKL, selesai)
- Lihat laporan/rekap + audit log

### untuk Superadmin
- Semua hak admin, plus:
- Kelola akun admin (tambah, edit, nonaktifkan)
- Kelola role dan hak akses
- Pengaturan sistem + template email
- Audit log seluruh aktivitas admin

## Instalasi

1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. `php artisan storage:link`
5. Buat database `magang_hertz` di MySQL
6. `php artisan migrate --seed`
7. `php artisan queue:work database --queue=default`
8. Akses `http://localhost` untuk landing page atau `http://localhost/akses-internal` untuk area admin

## Konfigurasi Penting

- **QUEUE_CONNECTION**: gunakan `database` di production, `sync` untuk development lokal
- **MAIL_CONFIG**: isi `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` untuk pengiriman email nyata
- **Superadmin default**: email `superadmin@balmon.test`, password `password123`
- **Admin default**: email `admin@balmon.test`, password `password123`

## Endpoint Penting

- `/` - Landing page lowongan terbaru
- `/akses-internal` - Login halaman admin
- `/akses-internal/dashboard` - Dashboard admin
- `/akses-internal/admins` - Manajemen akun admin (superadmin only)
- `/akses-internal/applications` - Daftar lamaran (admin)
- `/status` - Cek status lamaran (publik, pakai nomor/email)
- `/status/result` - Hasil cek status lamaran (publik)

## Kontributor

Dibangun oleh tim magang Balmon Hertz dengan framework Laravel.

## Lisensi

Proyek ini bersifat internal untuk Balmon Hertz.