@extends('layouts.admin')

@php
    $isEditing = $field !== null;
@endphp

@section('title', $isEditing ? 'Ubah Bidang Magang' : 'Tambah Bidang Magang')
@section('page_title', $isEditing ? 'Ubah Bidang Magang' : 'Tambah Bidang Magang')
@section('page_subtitle', 'Kelola nama, deskripsi, dan status bidang magang.')

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

    @if ($isEditing && $field->jobs()->exists())
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800" role="status">
            Bidang ini digunakan oleh lowongan. Perubahan data tidak menghapus atau memindahkan lowongan tersebut.
        </div>
    @endif

    <form action="{{ $isEditing ? route('internal.fields.update', $field) : route('internal.fields.store') }}"
          method="POST" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Nama Bidang</label>
            <input id="name" name="name" type="text" maxlength="255" required
                   value="{{ old('name', $isEditing ? $field->name : '') }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi</label>
            <textarea id="description" name="description" rows="5"
                      class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">{{ old('description', $isEditing ? $field->description : '') }}</textarea>
        </div>
        <p class="-mt-3 text-xs text-slate-500">
            Kode teknis dibuat otomatis dari nama bidang dan tetap sama jika nama diubah.
        </p>

        <div>
            <label for="is_active" class="block text-sm font-medium text-slate-700">Status Bidang</label>
            <select id="is_active" name="is_active" required
                    class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 sm:max-w-xs">
                <option value="1" {{ (string) old('is_active', $isEditing ? (int) $field->is_active : 1) === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ (string) old('is_active', $isEditing ? (int) $field->is_active : 1) === '0' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
            <a href="{{ route('internal.fields.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                {{ $isEditing ? 'Simpan Perubahan' : 'Buat Bidang' }}
            </button>
        </div>
    </form>
</div>
@endsection
