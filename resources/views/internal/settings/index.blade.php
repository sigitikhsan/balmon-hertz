@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page_title', 'Pengaturan Sistem')
@section('page_subtitle', 'Kelola konfigurasi umum dan informasi sistem Magang-Hertz.')

@section('content')
<div class="space-y-6">

    {{-- Informasi Sistem --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-sliders text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Informasi Sistem</h3>
                <p class="text-sm text-slate-500">Informasi dasar aplikasi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Nama Aplikasi</label>
                <input type="text" value="Magang-Hertz" readonly
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-700">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Instansi</label>
                <input type="text" value="Balai Monitor Spektrum Frekuensi Radio"
                    readonly class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-700">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Framework</label>
                <input type="text" value="Laravel" readonly
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-700">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Status Sistem</label>
                <div class="flex items-center gap-2 px-4 py-2.5 bg-green-50 border border-green-100 rounded-lg">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span class="text-sm font-medium text-green-700">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengaturan Pendaftaran --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-user-plus text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Pengaturan Pendaftaran</h3>
                <p class="text-sm text-slate-500">Konfigurasi umum penerimaan peserta magang.</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between gap-4 p-4 bg-slate-50 rounded-lg">
                <div>
                    <p class="font-medium text-slate-700">Pendaftaran Online</p>
                    <p class="text-xs text-slate-500 mt-1">Mengizinkan calon peserta mengakses formulir pendaftaran.</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                    Mengikuti status lowongan
                </span>
            </div>

            <div class="flex items-center justify-between gap-4 p-4 bg-slate-50 rounded-lg">
                <div>
                    <p class="font-medium text-slate-700">Verifikasi Pendaftaran</p>
                    <p class="text-xs text-slate-500 mt-1">Pendaftaran diperiksa oleh Admin sebelum keputusan akhir.</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                    Aktif
                </span>
            </div>
        </div>
    </div>

    {{-- Pengaturan Notifikasi --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-bell text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Pengaturan Notifikasi</h3>
                <p class="text-sm text-slate-500">Informasi konfigurasi pemberitahuan sistem.</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center justify-between gap-4 p-4 bg-slate-50 rounded-lg">
                <div>
                    <p class="font-medium text-slate-700">Notifikasi Email</p>
                    <p class="text-xs text-slate-500 mt-1">Pengiriman email berdasarkan konfigurasi server.</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-200 text-slate-600">
                    Belum dikonfigurasi
                </span>
            </div>
        </div>
    </div>

    {{-- Informasi Keamanan --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Keamanan Sistem</h3>
                <p class="text-sm text-slate-500">Informasi perlindungan dan akses aplikasi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-slate-50 rounded-lg">
                <p class="text-sm text-slate-500">Hak Akses</p>
                <p class="font-semibold text-slate-800 mt-1">Role Based Access Control</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-lg">
                <p class="text-sm text-slate-500">Autentikasi</p>
                <p class="font-semibold text-slate-800 mt-1">Login Internal</p>
            </div>
        </div>
    </div>

    {{-- Keterangan --}}
    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-100 rounded-xl">
        <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
        <div>
            <p class="text-sm font-semibold text-blue-800">Informasi Pengaturan</p>
            <p class="text-sm text-blue-700 mt-1">
                Halaman ini merupakan tampilan awal pengaturan sistem. Nilai konfigurasi
                masih bersifat informatif dan belum dapat diubah atau disimpan ke database.
                Fitur penyimpanan akan diintegrasikan setelah backend tersedia.
            </p>
        </div>
    </div>

</div>
@endsection