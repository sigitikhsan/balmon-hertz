@extends('layouts.admin')

@php
    $isEditing = $job !== null;
@endphp

@section('title', $isEditing ? 'Ubah Lowongan' : 'Tambah Lowongan')
@section('page_title', $isEditing ? 'Ubah Lowongan' : 'Tambah Lowongan')
@section('page_subtitle', 'Atur rincian lowongan, periode, dan alokasi kuota peserta.')

@section('content')
<div class="max-w-4xl space-y-6">
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($periods->isEmpty() || $fields->isEmpty())
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800" role="alert">
            Lowongan memerlukan setidaknya satu periode dan satu bidang yang sudah tersedia.
            @if ($periods->isEmpty())
                <a href="{{ route('internal.periods.create') }}" class="font-semibold underline">Buat periode</a>.
            @endif
        </div>
    @endif

    <form action="{{ $isEditing ? route('internal.jobs.update', $job) : route('internal.jobs.store') }}"
          method="POST" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="period_id" class="block text-sm font-medium text-slate-700">Periode</label>
                <select id="period_id" name="period_id" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
                    <option value="">Pilih periode</option>
                    @foreach ($periods as $period)
                        <option value="{{ $period->id }}" {{ (string) old('period_id', $isEditing ? $job->period_id : '') === (string) $period->id ? 'selected' : '' }}>
                            {{ $period->name }} (kuota {{ $period->quota }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="field_id" class="block text-sm font-medium text-slate-700">Bidang</label>
                <select id="field_id" name="field_id" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
                    <option value="">Pilih bidang</option>
                    @foreach ($fields as $field)
                        <option value="{{ $field->id }}" {{ (string) old('field_id', $isEditing ? $job->field_id : '') === (string) $field->id ? 'selected' : '' }}>
                            {{ $field->name }}{{ $field->is_active ? '' : ' (tidak aktif)' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label for="title" class="block text-sm font-medium text-slate-700">Nama Lowongan</label>
            <input id="title" name="title" type="text" maxlength="255" required
                   value="{{ old('title', $isEditing ? $job->title : '') }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
            <p class="mt-1 text-xs text-slate-500">
                URL dibuat otomatis dari nama lowongan dan tetap sama jika nama diubah.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div>
                <label for="type" class="block text-sm font-medium text-slate-700">Tipe</label>
                <input id="type" name="type" type="text" maxlength="20" required placeholder="WFO / WFH"
                       value="{{ old('type', $isEditing ? $job->type : '') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
            </div>
            <div>
                <label for="duration" class="block text-sm font-medium text-slate-700">Durasi</label>
                <input id="duration" name="duration" type="text" maxlength="50" required placeholder="3 Bulan"
                       value="{{ old('duration', $isEditing ? $job->duration : '') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
            </div>
            <div>
                <label for="quota" class="block text-sm font-medium text-slate-700">Kuota Lowongan</label>
                <input id="quota" name="quota" type="number" min="0" max="4294967295" required
                       value="{{ old('quota', $isEditing ? $job->quota : '') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
            </div>
        </div>
        <p class="-mt-3 text-xs text-slate-500">Jumlah kuota lowongan dalam satu periode tidak boleh melebihi kuota periode.</p>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi</label>
            <textarea id="description" name="description" rows="5" required
                      class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">{{ old('description', $isEditing ? $job->description : '') }}</textarea>
        </div>

        <div>
            <label for="responsibilities" class="block text-sm font-medium text-slate-700">Tanggung Jawab</label>
            <textarea id="responsibilities" name="responsibilities" rows="4"
                      class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">{{ old('responsibilities', $isEditing ? $job->responsibilities : '') }}</textarea>
        </div>

        <div>
            <label for="requirements" class="block text-sm font-medium text-slate-700">Persyaratan</label>
            <textarea id="requirements" name="requirements" rows="4"
                      class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">{{ old('requirements', $isEditing ? $job->requirements : '') }}</textarea>
        </div>

        <div>
            <label for="jurusan" class="block text-sm font-medium text-slate-700">Jurusan yang Relevan</label>
            <textarea id="jurusan" name="jurusan" rows="3"
                      class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">{{ old('jurusan', $isEditing ? $job->jurusan : '') }}</textarea>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-slate-700">Status Lowongan</label>
            <select id="status" name="status" required class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 sm:max-w-xs">
                <option value="">Pilih status</option>
                <option value="draft" {{ old('status', $isEditing ? $job->status : '') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="open" {{ old('status', $isEditing ? $job->status : '') === 'open' ? 'selected' : '' }}>Terbuka untuk publik</option>
                <option value="closed" {{ old('status', $isEditing ? $job->status : '') === 'closed' ? 'selected' : '' }}>Ditutup</option>
            </select>
        </div>

        <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
            <a href="{{ route('internal.jobs.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit" {{ $periods->isEmpty() || $fields->isEmpty() ? 'disabled' : '' }}
                    class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                {{ $isEditing ? 'Simpan Perubahan' : 'Buat Lowongan' }}
            </button>
        </div>
    </form>
</div>
@endsection
