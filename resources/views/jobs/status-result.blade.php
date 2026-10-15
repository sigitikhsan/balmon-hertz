{{-- resources/views/jobs/status-result.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="bg-slate-50 min-h-screen font-sans antialiased text-slate-800">

    @include('components.navbar')

    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Hasil Pengecekan</h1>

        @if(session('success'))
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6" role="status">
                <p class="text-sm font-normal text-blue-700 leading-relaxed">{{ session('success') }}</p>
            </div>
        @endif

        @if(empty($application))
            <div class="mt-6 bg-white rounded-xl border border-slate-200 p-10 text-center">
                <p class="font-semibold text-slate-900">Data tidak ditemukan</p>
                <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Periksa kembali nomor lamaran atau email yang dimasukkan.</p>
                <a href="{{ route('status.form') }}" class="mt-4 inline-flex items-center justify-center px-5 py-2.5 min-h-[44px] bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">Coba lagi</a>
            </div>
        @else
            @php
                $words = explode(' ', trim($application->nama));
                $maskedName = implode(' ', array_map(fn ($w) => mb_substr($w, 0, 1) . '***', $words));
                $maskedEmail = preg_replace('/^(.).*(@.*)$/', '$1***$2', $application->email);
                $badges = [
                    'menunggu' => 'bg-slate-100 text-slate-700',
                    'diterima' => 'bg-green-50 text-green-700',
                    'ditolak' => 'bg-red-50 text-red-700',
                    'perlu_perbaikan' => 'bg-amber-50 text-amber-700',
                ];
                $labels = [
                    'menunggu' => 'Menunggu seleksi',
                    'diterima' => 'Diterima',
                    'ditolak' => 'Ditolak',
                    'perlu_perbaikan' => 'Perlu perbaikan',
                ];
            @endphp
            <div class="mt-6 bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div>
                        <p class="text-xs font-normal text-slate-600 leading-relaxed">{{ $application->application_no }}</p>
                        <p class="font-bold text-slate-900">{{ $maskedName }}</p>
                        <p class="text-xs font-normal text-slate-600 leading-relaxed">{{ $maskedEmail }}</p>
                    </div>
                    <span class="text-xs font-medium px-3 py-1 rounded-full {{ $badges[$application->status] ?? $badges['menunggu'] }}">{{ $labels[$application->status] ?? $application->status }}</span>
                </div>
                <div class="mt-4 border-t border-slate-200 pt-4">
                    <p class="font-bold text-slate-900 text-sm">{{ $application->job->title }}</p>
                    <p class="text-xs font-normal text-slate-600 leading-relaxed">{{ $application->job->field->name ?? 'Balmon' }}, {{ $application->job->type }}</p>
                </div>
                @if($application->status === 'ditolak' && $application->rejection_reason)
                    <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm font-bold text-red-700">Alasan penolakan</p>
                        <p class="mt-1 text-sm font-normal text-red-700 leading-relaxed">{{ $application->rejection_reason }}</p>
                    </div>
                @endif
                @if($application->status === 'perlu_perbaikan')
                    <div class="mt-4 bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <p class="text-sm font-normal text-amber-700 leading-relaxed">Berkas perlu diperbaiki. Tunggu email dari tim HRD untuk instruksi selanjutnya.</p>
                    </div>
                @endif
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 min-h-[44px] bg-white border border-slate-200 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">Kembali ke lowongan</a>
        </div>
    </section>

</main>
@endsection
