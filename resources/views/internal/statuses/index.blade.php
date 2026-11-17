@extends('layouts.admin')

@section('title', 'Status PKL')
@section('page_title', 'Status PKL')
@section('page_subtitle', 'Pantau perkembangan seluruh pendaftaran program magang/PKL.')

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
            'menunggu' => 'bg-amber-50 text-amber-700',
            'diterima' => 'bg-green-50 text-green-700',
            'ditolak' => 'bg-red-50 text-red-700',
            'perlu_perbaikan' => 'bg-blue-50 text-blue-700',
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        @foreach ($statusLabels as $status => $label)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <h2 class="text-2xl font-bold text-slate-800 mt-2">{{ $statusCounts[$status] ?? 0 }}</h2>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-5 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800">Status Pendaftaran</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar pendaftaran dan status terakhirnya.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Nomor Pendaftaran</th>
                        <th class="px-5 py-4 font-semibold">Peserta</th>
                        <th class="px-5 py-4 font-semibold">Posisi</th>
                        <th class="px-5 py-4 font-semibold">Tanggal Daftar</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($applications as $application)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 font-medium text-blue-600">{{ $application->application_no }}</td>
                            <td class="px-5 py-4">
                                <div class="font-medium text-slate-800">{{ $application->nama }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $application->email }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $application->job->title ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $application->submitted_at ? $application->submitted_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $statusStyles[$application->status] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $statusLabels[$application->status] ?? ucfirst($application->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-500">
                                Belum ada pendaftaran untuk ditampilkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($applications->hasPages())
            <div class="p-5 border-t border-slate-200">{{ $applications->links() }}</div>
        @endif
    </div>
</div>
@endsection
