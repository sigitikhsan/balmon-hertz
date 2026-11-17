@extends('layouts.admin')

@section('title', 'Manajemen Akun Admin')
@section('page_title', 'Manajemen Akun Admin')
@section('page_subtitle', 'Kelola akun administrator sistem magang Balmon.')

@section('content')
<div class="space-y-6">

    {{-- HEADER ACTION --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                Daftar Administrator
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Kelola akun Admin yang memiliki akses ke sistem.
            </p>
        </div>

        <div>
            <button
                type="button"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                <i class="fa-solid fa-plus"></i>
                Tambah Admin
            </button>
        </div>

    </div>

    {{-- SUMMARY CARD --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Admin</p>

                    <h3 class="text-2xl font-bold text-slate-900 mt-2">
                        {{ $admins->total() }}
                    </h3>
                </div>

                <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fa-solid fa-users-gear text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Admin Aktif</p>

                    <h3 class="text-2xl font-bold text-green-600 mt-2">
                        {{ \App\Models\User::where('role', 'admin')->where('status', 'aktif')->count() }}
                    </h3>
                </div>

                <div class="w-11 h-11 rounded-lg bg-green-50 flex items-center justify-center">
                    <i class="fa-solid fa-user-check text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Admin Nonaktif</p>

                    <h3 class="text-2xl font-bold text-red-600 mt-2">
                        {{ \App\Models\User::where('role', 'admin')->where('status', '!=', 'aktif')->count() }}
                    </h3>
                </div>

                <div class="w-11 h-11 rounded-lg bg-red-50 flex items-center justify-center">
                    <i class="fa-solid fa-user-xmark text-red-600 text-lg"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- ADMIN TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="p-5 border-b border-slate-200">
            <h2 class="text-base font-semibold text-slate-800">
                Data Akun Admin
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Daftar akun administrator yang terdaftar di sistem.
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm text-left">

                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-6 py-4 font-semibold">No.</th>
                        <th class="px-6 py-4 font-semibold">Nama Admin</th>
                        <th class="px-6 py-4 font-semibold">Email</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Tanggal Dibuat</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($admins as $index => $admin)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-6 py-4 text-slate-500">
                                {{ $admins->firstItem() + $index }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="font-medium text-slate-800">
                                            {{ $admin->name }}
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            Administrator
                                        </p>
                                    </div>

                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $admin->email }}
                            </td>

                            <td class="px-6 py-4">

                                @if ($admin->status === 'aktif')

                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Aktif
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Nonaktif
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $admin->created_at ? $admin->created_at->format('d/m/Y') : '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">

                                <button
                                    type="button"
                                    class="text-slate-400 cursor-not-allowed"
                                    title="Fitur edit akan dibuat pada tahap berikutnya">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-users text-slate-400 text-xl"></i>
                                    </div>

                                    <h3 class="font-semibold text-slate-700">
                                        Belum Ada Akun Admin
                                    </h3>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Data administrator akan ditampilkan di sini.
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if ($admins->hasPages())

            <div class="px-6 py-4 border-t border-slate-200">
                {{ $admins->links() }}
            </div>

        @endif

    </div>

</div>
@endsection