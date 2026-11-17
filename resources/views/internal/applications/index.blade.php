@extends('layouts.admin')

@section('title', 'Pendaftaran Peserta')

@section('page_title', 'Pendaftaran Peserta')

@section('page_subtitle', 'Kelola dan pantau seluruh pendaftaran peserta magang/PKL.')

@section('content')
<div class="space-y-6">

    {{-- Ringkasan Pendaftaran --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Pendaftaran</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-2">
                        {{ $totalApplications }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Menunggu Verifikasi</p>
                    <h3 class="text-2xl font-bold text-amber-600 mt-2">
                        {{ $waitingApplications }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Diterima</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-2">
                        {{ $acceptedApplications }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Ditolak</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-2">
                        {{ $rejectedApplications }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark text-lg"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- Tabel Pendaftaran --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">

        <div class="p-5 border-b border-slate-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h3 class="text-lg font-semibold text-slate-800">
                        Data Pendaftaran
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Daftar peserta yang telah mengajukan pendaftaran magang/PKL.
                    </p>
                </div>

                <form action="{{ route('internal.applications.index') }}"
                      method="GET"
                      class="flex flex-col sm:flex-row gap-2">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama, email, nomor..."
                        class="w-full sm:w-64 px-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    <select
                        name="status"
                        class="px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500">

                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>
                            Menunggu
                        </option>
                        <option value="diterima" {{ $status === 'diterima' ? 'selected' : '' }}>
                            Diterima
                        </option>
                        <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>
                            Ditolak
                        </option>
                        <option value="perlu_perbaikan" {{ $status === 'perlu_perbaikan' ? 'selected' : '' }}>
                            Perlu Perbaikan
                        </option>

                    </select>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i>
                        Cari
                    </button>

                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">No.</th>
                        <th class="px-5 py-4 font-semibold">Nomor Pendaftaran</th>
                        <th class="px-5 py-4 font-semibold">Nama Peserta</th>
                        <th class="px-5 py-4 font-semibold">Posisi Magang</th>
                        <th class="px-5 py-4 font-semibold">Tanggal Daftar</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($applications as $index => $application)

                        @php
                            $statusStyles = [
                                'menunggu' => 'bg-amber-50 text-amber-700',
                                'diterima' => 'bg-green-50 text-green-700',
                                'ditolak' => 'bg-red-50 text-red-700',
                                'perlu_perbaikan' => 'bg-blue-50 text-blue-700',
                            ];

                            $statusLabels = [
                                'menunggu' => 'Menunggu',
                                'diterima' => 'Diterima',
                                'ditolak' => 'Ditolak',
                                'perlu_perbaikan' => 'Perlu Perbaikan',
                            ];
                        @endphp

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-5 py-4 text-slate-500">
                                {{ $applications->firstItem() + $index }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-medium text-blue-600">
                                    {{ $application->application_no }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $application->nama }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ $application->email }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $application->job->title ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $application->submitted_at
                                    ? $application->submitted_at->format('d M Y')
                                    : '-' }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $statusStyles[$application->status] ?? 'bg-slate-100 text-slate-600' }}">

                                    {{ $statusLabels[$application->status] ?? ucfirst($application->status) }}

                                </span>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <button
                                    type="button"
                                    disabled
                                    title="Fitur detail akan dibuat pada tahap berikutnya"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-400 bg-slate-50 cursor-not-allowed">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">

                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-folder-open text-xl"></i>
                                    </div>

                                    <h4 class="font-semibold text-slate-700">
                                        Belum Ada Pendaftaran
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Data pendaftaran peserta belum tersedia.
                                    </p>
                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        @if ($applications->hasPages())
            <div class="px-5 py-4 border-t border-slate-200">
                {{ $applications->links() }}
            </div>
        @endif

    </div>

</div>
@endsection