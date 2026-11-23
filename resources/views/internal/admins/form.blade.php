@extends('layouts.admin')

@php
    $isEditing = $admin !== null;
@endphp

@section('title', $isEditing ? 'Ubah Akun Admin' : 'Tambah Akun Admin')
@section('page_title', $isEditing ? 'Ubah Akun Admin' : 'Tambah Akun Admin')
@section('page_subtitle', 'Kelola data akun administrator sistem magang Balmon.')

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

    <form action="{{ $isEditing ? route('internal.admins.update', $admin) : route('internal.admins.store') }}"
          method="POST" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Nama Admin</label>
            <input id="name" name="name" type="text" maxlength="255" required
                   value="{{ old('name', $isEditing ? $admin->name : '') }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email" name="email" type="email" maxlength="255" required
                   value="{{ old('email', $isEditing ? $admin->email : '') }}"
                   class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
            <input id="password" name="password" type="password" maxlength="255"
                   {{ $isEditing ? '' : 'required' }}
                   class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5">
            <p class="mt-1 text-xs text-slate-500">
                {{ $isEditing ? 'Kosongkan jika kata sandi tidak ingin diubah.' : 'Minimal 8 karakter.' }}
            </p>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-slate-700">Status Akun</label>
            <select id="status" name="status" required
                    class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2.5 sm:max-w-xs">
                <option value="aktif" {{ (string) old('status', $isEditing ? $admin->status : 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ (string) old('status', $isEditing ? $admin->status : 'aktif') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
            <a href="{{ route('internal.admins.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                {{ $isEditing ? 'Simpan Perubahan' : 'Buat Akun' }}
            </button>
        </div>
    </form>
</div>
@endsection
