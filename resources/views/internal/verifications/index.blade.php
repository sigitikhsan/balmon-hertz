@extends('layouts.admin')

@section('title', 'Verifikasi Dokumen')
@section('page_title', 'Verifikasi Dokumen')
@section('page_subtitle', 'Pantau pendaftaran yang menunggu pemeriksaan atau perbaikan dokumen.')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Menunggu Verifikasi</p>
            <h2 class="text-2xl font-bold text-amber-600 mt-2">{{ $waitingCount }}</h2>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Perlu Perbaikan</p>
            <h2 class="text-2xl font-bold text-blue-600 mt-2">{{ $revisionCount }}</h2>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-5 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800">Antrean Pemeriksaan</h2>
            <p class="text-sm text-slate-500 mt-1">Halaman ini menampilkan antrean dokumen; aksi pemeriksaan belum tersedia.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Nomor Pendaftaran</th>
                        <th class="px-5 py-4 font-semibold">Peserta</th>
                        <th class="px-5 py-4 font-semibold">Posisi</th>
                        <th class="px-5 py-4 font-semibold">Dokumen</th>
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
                                <span class="mr-2">CV: {{ $application->cv_path ? 'Ada' : 'Belum ada' }}</span>
                                <span>Surat: {{ $application->surat_path ? 'Ada' : 'Belum ada' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $application->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $application->status === 'menunggu' ? 'Menunggu' : 'Perlu Perbaikan' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-500">
                                Belum ada dokumen dalam antrean pemeriksaan.
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
