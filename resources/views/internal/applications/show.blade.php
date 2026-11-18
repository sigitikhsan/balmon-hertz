@extends('layouts.admin')

@section('title', 'Detail Pendaftaran')
@section('page_title', 'Detail Pendaftaran')
@section('page_subtitle', 'Tinjau data dan dokumen peserta, lalu perbarui status lamaran.')

@section('content')
@php
    $statusLabels = [
        'menunggu' => 'Menunggu',
        'diterima' => 'Diterima',
        'ditolak' => 'Ditolak',
        'perlu_perbaikan' => 'Perlu Perbaikan',
    ];
@endphp

<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700" role="status">
            {{ session('success') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800" role="alert">
            {{ session('warning') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
            <div>
                <p class="text-sm text-slate-500">Nomor pendaftaran</p>
                <h2 class="text-xl font-semibold text-slate-900">{{ $application->application_no }}</h2>
            </div>
            <span class="inline-flex w-fit px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-700">
                {{ $statusLabels[$application->status] ?? $application->status }}
            </span>
        </div>

        <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <dt class="text-sm text-slate-500">Nama</dt>
                <dd class="mt-1 font-medium text-slate-900">{{ $application->nama }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Email</dt>
                <dd class="mt-1 font-medium text-slate-900">{{ $application->email }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Nomor telepon</dt>
                <dd class="mt-1 font-medium text-slate-900">{{ $application->no_hp }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Tempat, tanggal lahir</dt>
                <dd class="mt-1 font-medium text-slate-900">
                    {{ $application->ttl_tempat }}, {{ $application->ttl_tanggal->format('d M Y') }}
                </dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Posisi</dt>
                <dd class="mt-1 font-medium text-slate-900">{{ $application->job->title ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Bidang</dt>
                <dd class="mt-1 font-medium text-slate-900">{{ $application->job->field->name ?? '-' }}</dd>
            </div>
            @if ($application->rejection_reason)
                <div class="sm:col-span-2">
                    <dt class="text-sm text-slate-500">Alasan penolakan</dt>
                    <dd class="mt-1 text-slate-900">{{ $application->rejection_reason }}</dd>
                </div>
            @endif
        </dl>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800">Dokumen Lamaran</h2>
        <div class="mt-4 divide-y divide-slate-100">
            @foreach (['cv' => 'CV', 'surat' => 'Surat Lamaran'] as $document => $label)
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-4 first:pt-0 last:pb-0">
                    <span class="font-medium text-slate-700">{{ $label }}</span>
                    @if ($application->{$document . '_path'})
                        <div class="flex gap-2">
                            <a href="{{ route('internal.applications.documents.view', [$application, $document]) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="px-3 py-2 rounded-lg text-sm text-blue-700 bg-blue-50 hover:bg-blue-100">
                                Lihat
                            </a>
                            <a href="{{ route('internal.applications.documents.download', [$application, $document]) }}"
                               class="px-3 py-2 rounded-lg text-sm text-slate-700 bg-slate-100 hover:bg-slate-200">
                                Unduh
                            </a>
                        </div>
                    @else
                        <span class="text-sm text-slate-500">Dokumen tidak tersedia</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800">Perbarui Status</h2>
        @if (count($allowedStatuses))
            <form action="{{ route('internal.applications.status', $application) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700">Status berikutnya</label>
                    <select id="status" name="status" required
                            class="mt-1 w-full sm:max-w-md px-3 py-2 border border-slate-300 rounded-lg">
                        @foreach ($allowedStatuses as $status)
                            <option value="{{ $status }}" {{ old('status') === $status ? 'selected' : '' }}>
                                {{ $statusLabels[$status] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="note" class="block text-sm font-medium text-slate-700">Catatan</label>
                    <textarea id="note" name="note" rows="3" maxlength="2000"
                              class="mt-1 w-full px-3 py-2 border border-slate-300 rounded-lg"
                              placeholder="Wajib diisi jika ditolak atau perlu perbaikan">{{ old('note') }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Catatan akan dikirim kepada peserta melalui email.</p>
                </div>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Simpan Status
                </button>
            </form>
        @else
            <p class="mt-2 text-sm text-slate-500">Status lamaran ini sudah final dan tidak dapat diubah.</p>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800">Riwayat Status</h2>
        <ol class="mt-4 space-y-4">
            @forelse ($histories as $history)
                <li class="border-l-2 border-slate-200 pl-4">
                    <p class="font-medium text-slate-800">
                        {{ $statusLabels[$history->from_status] ?? 'Baru' }}
                        &rarr;
                        {{ $statusLabels[$history->to_status] ?? $history->to_status }}
                    </p>
                    <p class="text-xs text-slate-500">
                        {{ $history->created_at->format('d M Y H:i') }}
                        &middot; {{ $history->changer->name ?? 'Sistem' }}
                    </p>
                    @if ($history->note)
                        <p class="mt-1 text-sm text-slate-600">{{ $history->note }}</p>
                    @endif
                </li>
            @empty
                <li class="text-sm text-slate-500">Belum ada riwayat status.</li>
            @endforelse
        </ol>
    </div>
</div>
@endsection
