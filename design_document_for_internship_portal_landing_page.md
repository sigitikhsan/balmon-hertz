# Dokumen Desain: Landing Page Portal Magang/PKL

## 1. Gambaran Umum & Filosofi Desain

* **Tujuan:** Membuat *landing page* yang modern, minimalis, dan berfokus pada konversi untuk portal magang/PKL. Menampilkan 4 lowongan terbaru dalam bentuk kartu katalog, di mana akses penuh ke detail lowongan dibatasi (gated) di balik dinding autentikasi (Masuk/Daftar) yang akan mengarahkan pengguna ke *dashboard* setelah login.
* **Estetika Desain ("Anti-Slop"):** Menggunakan latar belakang bersih (`bg-slate-50`), kontras tipografi yang tinggi, sudut melengkung yang halus (`rounded-xl`), transisi lembut, serta ruang kosong (*whitespace*) yang proporsional. Menghindari gradasi warna berlebihan atau ilustrasi generik yang tidak perlu.
* **Teknologi:** Laravel Blade, Tailwind CSS.

---

## 2. Palet Warna & Tipografi

### Warna (Kelas Tailwind)
* **Latar Belakang:** `bg-slate-50` (Utama), `bg-white` (Kartu/Navigasi), `bg-slate-900` (Bagian gelap/Footer).
* **Teks:** `text-slate-900` (Judul), `text-slate-600` (Isi/Sub-teks), `text-blue-600` (Aksen Utama).
* **Garis Pembatas:** `border-slate-200`.

### Tipografi
* **Font:** Inter atau Plus Jakarta Sans (melalui Google Fonts).
* **Heading:** Tebal (`font-bold`), jarak huruf rapat (`tracking-tight`).

---

## 3. Struktur Komponen & Tata Letak

### A. Navigasi (`resources/views/components/navbar.blade.php`)
* **Kiri:** Logo/Nama brand (contoh: "MagangKu").
* **Tengah (Desktop):** Tautan navigasi (`Beranda`, `Tentang`, `Katalog`, `FAQ`).
* **Kanan:** Tombol aksi (`Masuk` sebagai tautan teks minimalis, `Daftar` sebagai tombol solid `bg-slate-900 text-white hover:bg-slate-800`).

### B. Bagian Hero (`resources/views/partials/hero.blade.php`)
* **Judul Utama:** *"Langkah Awal Menuju Karir Impianmu"*.
* **Sub-judul:** Penjelasan singkat mengenai kemudahan mencari magang dan PKL berkualitas.
* **Grup CTA:** Tombol primer menuju katalog dan tombol sekunder menuju halaman registrasi.

### C. Bagian Katalog: 4 Lowongan Terbaru (`resources/views/partials/latest-internships.blade.php`)
* **Header Bagian:** Judul "Lowongan Terbaru" dengan teks penjelasan di sebelah kanan.
* **Grid Layout:** Responsif 4 kolom (`grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6`).
* **Desain Kartu Lowongan:**
  * Thumbnail inisial perusahaan, nama perusahaan, dan lokasi.
  * Judul posisi magang (`text-lg font-semibold text-slate-900`).
  * Badge tipe (WFH/WFO) dan durasi (misal: 3 Bulan).
  * Tombol aksi "Lihat Selengkapnya" yang mengarah ke halaman login (dengan penanganan *intended URL* Laravel).

---

## 4. Alur Autentikasi & Logika Pengalihan (Laravel)

1. **Proteksi Route:** Halaman detail lowongan (`/internships/{id}`) dilindungi oleh middleware `auth`.
2. **Penanganan Intended URL:** Ketika pengguna tamu mengklik "Lihat Selengkapnya" pada kartu lowongan, Laravel menyimpan URL tujuan ke dalam session sebelum mengarahkan ke halaman login (`/login`).
3. **Pengalihan Dashboard:** Setelah proses login atau registrasi berhasil, pengguna langsung diarahkan ke *dashboard* utama user (`/dashboard`).

---

## 5. Implementasi Kode Referensi (OpenCode)

Berikut adalah contoh implementasi kode lengkap untuk halaman utama (`welcome.blade.php`) yang siap digunakan sebagai referensi acuan oleh OpenCode:

```html
{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="bg-slate-50 min-h-screen font-sans antialiased text-slate-800">
    
    {{-- Header / Navbar --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="font-bold text-xl text-slate-900 tracking-tight">Magang<span class="text-blue-600">Ku</span></span>
            </div>
            <nav class="hidden md:flex items-center space-x-8 text-sm font-medium text-slate-600">
                <a href="#beranda" class="hover:text-slate-900 transition">Beranda</a>
                <a href="#katalog" class="hover:text-slate-900 transition">Katalog Lowongan</a>
                <a href="#tentang" class="hover:text-slate-900 transition">Tentang</a>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900 transition">Masuk</a>
                <a href="{{ route('register') }}" class="text-sm font-medium bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition">Daftar</a>
            </div>
        </div>
    </header>

    {{-- Hero Section --}}
    <section id="beranda" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight max-w-3xl mx-auto">
            Langkah Awal Menuju <span class="text-blue-600">Karir Impianmu</span>
        </h1>
        <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">
            Temukan kesempatan magang dan PKL berkualitas dari berbagai perusahaan terkemuka dengan proses yang mudah dan terstruktur.
        </p>
        <div class="mt-8 flex justify-center gap-4">
            <a href="#katalog" class="px-6 py-3 bg-slate-900 text-white font-medium rounded-xl hover:bg-slate-800 transition shadow-sm">
                Jelajahi Lowongan
            </a>
            <a href="{{ route('register') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition">
                Daftar Sekarang
            </a>
        </div>
    </section>

    {{-- 4 Latest Internships Catalog Section --}}
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-2">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Lowongan Terbaru</h2>
                <p class="text-sm text-slate-500 mt-1">Peluang magang dan PKL segar minggu ini</p>
            </div>
            <span class="text-xs font-medium px-3 py-1 bg-blue-50 text-blue-600 rounded-full">Menampilkan 4 Lowongan Terbaru</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestInternships as $job)
                <div class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition duration-200">
                    <div>
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-slate-700 text-sm">
                                {{ strtoupper(substr($job->company_name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-medium text-slate-900 text-sm line-clamp-1">{{ $job->company_name }}</h3>
                                <p class="text-xs text-slate-500">{{ $job->location }}</p>
                            </div>
                        </div>
                        <h4 class="font-semibold text-slate-900 mb-2 line-clamp-2 text-base">{{ $job->title }}</h4>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-md">{{ $job->type }}</span>
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded-md">{{ $job->duration }}</span>
                        </div>
                    </div>
                    
                    {{-- Gated CTA Button: Redirects to login with intended URL handling --}}
                    <a href="{{ route('login') }}" class="w-full text-center py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition">
                        Lihat Selengkapnya
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm">
            <p>&copy; {{ date('Y') }} MagangKu. Hak cipta dilindungi.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-white transition">Bantuan</a>
            </div>
        </div>
    </footer>

</main>
@endsection
```