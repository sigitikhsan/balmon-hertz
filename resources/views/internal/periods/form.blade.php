@extends('layouts.admin')

@php
    $isEditing = $period !== null;
@endphp

@section('title', $isEditing ? 'Ubah Periode' : 'Tambah Periode')
@section('page_title', $isEditing ? 'Ubah Periode' : 'Tambah Periode')
@section('page_subtitle', 'Isi informasi periode pelaksanaan dan kuota peserta.')

@section('content')
<div class="max-w-3xl space-y-6">
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEditing ? route('internal.periods.update', $period) : route('internal.periods.store') }}"
          method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Nama Periode</label>
            <input id="name" name="name" type="text" maxlength="255" required
                   value="{{ old('name', $isEditing ? $period->name : '') }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="start_date" class="block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                <input id="start_date" name="start_date" type="date" required
                       value="{{ old('start_date', $isEditing ? $period->start_date->format('Y-m-d') : '') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-slate-700">Tanggal Selesai</label>
                <input id="end_date" name="end_date" type="date" required
                       value="{{ old('end_date', $isEditing ? $period->end_date->format('Y-m-d') : '') }}"
                       class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
            </div>
        </div>

        <div>
            <label for="quota" class="block text-sm font-medium text-slate-700">Kuota Peserta</label>
            <input id="quota" name="quota" type="number" min="0" max="4294967295" required
                   value="{{ old('quota', $isEditing ? $period->quota : 0) }}"
                   class="mt-1 w-full sm:max-w-xs rounded-lg border border-slate-300 px-4 py-2.5">
        </div>

        <div>
            <label for="is_active" class="block text-sm font-medium text-slate-700">Status Periode</label>
            <select id="is_active" name="is_active" required
                    class="mt-1 w-full sm:max-w-xs rounded-lg border border-slate-300 px-4 py-2.5">
                <option value="1" {{ (string) old('is_active', $isEditing ? (int) $period->is_active : 1) === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ (string) old('is_active', $isEditing ? (int) $period->is_active : 1) === '0' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
            <a href="{{ route('internal.periods.index') }}"
               class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm text-center hover:bg-slate-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium">
                {{ $isEditing ? 'Simpan Perubahan' : 'Buat Periode' }}
            </button>
        </div>
    </form>
</div>
@endsection
