# Alur Sistem Magang-Hertz Balmon

> Catatan: flow ini bisa berubah-ubah sesuai kesepakatan tim.

## 1. Peserta (tanpa akun/login)

1. Buka landing page, lihat 4 lowongan terbaru.
2. Buka `/jobs`, filter per tipe, klik Lihat Selengkapnya (modal detail).
3. Klik Daftar, isi form: nama, email, no HP, TTL, posisi (autofill), upload CV + surat lamaran (PDF/DOCX, maks 5MB).
4. Terima nomor lamaran (`MH-2026-XXXX`). Aturan saat ini: 1 lamaran per email (bisa berubah).
5. Cek status di `/status` pakai nomor lamaran atau email: menunggu / diterima / ditolak (+ alasan) / perlu perbaikan.
6. Info diterima/ditolak dikirim via email HRD (dev: `MAIL_MAILER=log`).

## 2. Admin

1. Login `/admin` (role `admin`, akun aktif).
2. Kelola periode + kuota, bidang magang (CRUD), lowongan (buka/tutup).
3. Lihat daftar lamaran (pagination), verifikasi CV/surat.
4. Ubah status: diterima / ditolak (wajib alasan) / perlu perbaikan.
5. Tombol Terima/Tolak langsung kirim email template ke peserta.
6. Kelola penempatan + status PKL (sedang PKL, selesai).
7. Lihat laporan/rekap + audit log.

## 3. Superadmin

1. Semua hak admin, plus:
2. Kelola akun admin (tambah, edit, nonaktifkan).
3. Kelola role dan hak akses.
4. Pengaturan sistem + template email.
5. Audit log seluruh aktivitas admin.

## Teknis (untuk dev)

- Repo: `github.com/sigitikhsan/balmon-hertz`, branch `main`.
- Setup baru: `composer install`, `copy .env.example .env`, `php artisan key:generate`, `php artisan storage:link`, `php artisan serve`.
- DB: 9 migration siap. Jangan `migrate` sebelum database dibuat.
- File lamaran: private storage (`storage/app/private`), bukan public URL.
- Commit wajib persetujuan pemilik sebelum push.
