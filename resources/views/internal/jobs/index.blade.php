@extends('layouts.admin')

@section('title', 'Lowongan')
@section('page_title', 'Lowongan')
@section('page_subtitle', 'Kelola lowongan dan alokasi kuota peserta per periode.')

@section('content')
<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700" role="status">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Lowongan</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ number_format($totalJobs, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Lowongan Terbuka</p>
            <p class="mt-2 text-2xl font-bold text-green-600">{{ number_format($openJobs, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Alokasi Kuota</p>
            <p class="mt-2 text-2xl font-bold text-blue-600">{{ number_format($totalQuota, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Daftar Lowongan</h2>
                <p class="mt-1 text-sm text-slate-500">Jumlah alokasi setiap periode tidak boleh melebihi kuota periode.</p>
            </div>
            <a href="{{ route('internal.jobs.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                <i class="fa-solid fa-plus"></i>
                Tambah Lowongan
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Lowongan</th>
                        <th class="px-5 py-4 font-semibold">Periode</th>
                        <th class="px-5 py-4 font-semibold">Bidang</th>
                        <th class="px-5 py-4 font-semibold">Peserta diterima / kuota</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($jobs as $job)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-800">{{ $job->title }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $job->type }} · {{ $job->duration }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $job->period->name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $job->field->name }}</td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ number_format($job->accepted_applications_count, 0, ',', '.') }}
                                /
                                {{ number_format($job->quota, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                @if ($job->status === 'open')
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">Terbuka</span>
                                @elseif ($job->status === 'closed')
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Ditutup</span>
                                @else
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-700">Draft</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('internal.jobs.edit', $job) }}"
                                   class="font-medium text-blue-600 hover:text-blue-800">Ubah</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-500">
                                Belum ada lowongan.
                                <a href="{{ route('internal.jobs.create') }}" class="font-medium text-blue-600 hover:underline">Buat lowongan pertama</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($jobs->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $jobs->links() }}</div>
        @endif
    </div>
</div>
@endsection
