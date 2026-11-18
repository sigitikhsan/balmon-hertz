@extends('layouts.admin')

@section('title', 'Periode & Kuota')
@section('page_title', 'Periode & Kuota')
@section('page_subtitle', 'Kelola periode pelaksanaan magang dan kapasitas peserta.')

@section('content')
<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700" role="status">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Periode</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-2">
                        {{ $totalPeriods }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-days text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Periode Aktif</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-2">
                        {{ $activePeriods }}
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
                    <p class="text-sm text-slate-500">Total Kuota</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-2">
                        {{ number_format($totalQuota, 0, ',', '.') }}
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
                    <p class="text-sm text-slate-500">Peserta Diterima</p>
                    <h3 class="text-2xl font-bold text-purple-600 mt-2">
                        {{ number_format($totalAccepted, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-check text-lg"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- Tabel Periode --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">

        <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">
                    Daftar Periode Magang
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    Informasi periode dan kapasitas penerimaan peserta magang.
                </p>
            </div>
            <a href="{{ route('internal.periods.create') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium">
                <i class="fa-solid fa-plus mr-2"></i>Tambah Periode
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">No.</th>
                        <th class="px-5 py-4 font-semibold">Nama Periode</th>
                        <th class="px-5 py-4 font-semibold">Tanggal Mulai</th>
                        <th class="px-5 py-4 font-semibold">Tanggal Selesai</th>
                        <th class="px-5 py-4 font-semibold">Kuota</th>
                        <th class="px-5 py-4 font-semibold">Lowongan</th>
                        <th class="px-5 py-4 font-semibold">Peserta Diterima</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($periods as $index => $period)

                        @php
                            $acceptedCount = $period->jobs->sum(function ($job) {
                                return $job->applications->count();
                            });

                            $remainingQuota = max(0, $period->quota - $acceptedCount);
                        @endphp

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-5 py-4 text-slate-500">
                                {{ $periods->firstItem() + $index }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-800">
                                    {{ $period->name }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $period->start_date->format('d M Y') }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $period->end_date->format('d M Y') }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-semibold text-slate-800">
                                    {{ number_format($period->quota, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $period->jobs_count }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-800">
                                    {{ $acceptedCount }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    Sisa: {{ $remainingQuota }}
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                @if ($period->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-2"></span>
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex flex-col items-start gap-2">
                                    <a href="{{ route('internal.periods.edit', $period) }}"
                                       class="text-sm font-medium text-blue-700 hover:text-blue-900">
                                        Ubah
                                    </a>
                                    <form action="{{ route('internal.periods.status', $period) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_active" value="{{ $period->is_active ? 0 : 1 }}">
                                        <button type="submit" class="text-sm font-medium text-slate-600 hover:text-slate-900">
                                            {{ $period->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">

                                    <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-calendar-xmark text-xl"></i>
                                    </div>

                                    <h4 class="font-semibold text-slate-700">
                                        Belum Ada Periode
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Data periode magang belum tersedia.
                                    </p>

                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        @if ($periods->hasPages())
            <div class="px-5 py-4 border-t border-slate-200">
                {{ $periods->links() }}
            </div>
        @endif

    </div>

</div>
@endsection