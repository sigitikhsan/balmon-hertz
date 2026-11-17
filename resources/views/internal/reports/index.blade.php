@extends('layouts.admin')

@section('title', 'Laporan & Rekapitulasi')
@section('page_title', 'Laporan & Rekapitulasi')
@section('page_subtitle', 'Ringkasan pendaftaran dan peminat pada setiap posisi magang.')

@section('content')
<div class="space-y-6">
    @php
        $statusLabels = [
            'menunggu' => 'Menunggu',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            'perlu_perbaikan' => 'Perlu Perbaikan',
        ];
        $statusStyles = [
            'menunggu' => 'text-amber-600',
            'diterima' => 'text-green-600',
            'ditolak' => 'text-red-600',
            'perlu_perbaikan' => 'text-blue-600',
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        @foreach ($statusLabels as $status => $label)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <h2 class="text-2xl font-bold mt-2 {{ $statusStyles[$status] }}">
                    {{ $statusCounts[$status] ?? 0 }}
                </h2>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-5 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800">Rekap Peminat per Posisi</h2>
            <p class="text-sm text-slate-500 mt-1">Jumlah pendaftar dibandingkan kuota yang tersedia.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Posisi Magang</th>
                        <th class="px-5 py-4 font-semibold">Bidang</th>
                        <th class="px-5 py-4 font-semibold">Periode</th>
                        <th class="px-5 py-4 font-semibold">Pendaftar</th>
                        <th class="px-5 py-4 font-semibold">Kuota</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($jobStats as $job)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 font-medium text-slate-800">{{ $job->title }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $job->field->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $job->period->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ $job->applications_count }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ $job->quota }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-500">
                                Belum ada posisi magang untuk direkap.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
