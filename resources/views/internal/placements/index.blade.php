@extends('layouts.admin')

@section('title', 'Penempatan Peserta')
@section('page_title', 'Penempatan Peserta')
@section('page_subtitle', 'Lihat posisi magang, bidang, dan periode peserta yang telah diterima.')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <p class="text-sm text-slate-500">Peserta Diterima</p>
        <h2 class="text-2xl font-bold text-blue-600 mt-2">{{ $participants->total() }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-5 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800">Daftar Penempatan</h2>
            <p class="text-sm text-slate-500 mt-1">Penempatan mengikuti posisi dan periode pada pendaftaran peserta.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Peserta</th>
                        <th class="px-5 py-4 font-semibold">Nomor Pendaftaran</th>
                        <th class="px-5 py-4 font-semibold">Posisi</th>
                        <th class="px-5 py-4 font-semibold">Bidang</th>
                        <th class="px-5 py-4 font-semibold">Periode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($participants as $participant)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <div class="font-medium text-slate-800">{{ $participant->nama }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $participant->email }}</div>
                            </td>
                            <td class="px-5 py-4 font-medium text-blue-600">{{ $participant->application_no }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ $participant->job->title ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $participant->job->field->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $participant->job->period->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-500">
                                Belum ada peserta diterima untuk ditampilkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($participants->hasPages())
            <div class="p-5 border-t border-slate-200">{{ $participants->links() }}</div>
        @endif
    </div>
</div>
@endsection
