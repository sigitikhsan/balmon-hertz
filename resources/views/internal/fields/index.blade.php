@extends('layouts.admin')

@section('title', 'Bidang Magang')
@section('page_title', 'Bidang Magang')
@section('page_subtitle', 'Kelola bidang dan kategori penempatan peserta magang.')

@section('content')
<div class="space-y-6">

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Bidang</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-2">
                        {{ $totalFields }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Bidang Aktif</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-2">
                        {{ $activeFields }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Bidang Tidak Aktif</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-2">
                        {{ $inactiveFields }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Lowongan</p>
                    <h3 class="text-2xl font-bold text-purple-600 mt-2">
                        {{ number_format($totalJobs, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fa-solid fa-briefcase text-lg"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- Tabel Bidang --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">

        <div class="p-5 border-b border-slate-200">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">
                    Daftar Bidang Magang
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    Informasi bidang dan jumlah lowongan yang tersedia.
                </p>
            </div>
        </div>

        {{-- Pencarian dan Filter --}}
        <form method="GET" action="{{ route('internal.fields.index') }}"
            class="p-5 border-b border-slate-100">

            <div class="flex flex-col md:flex-row gap-3">

                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama atau deskripsi bidang..."
                        class="w-full pl-11 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400"
                    >
                </div>

                <select
                    name="status"
                    class="px-4 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">Semua Status</option>
                    <option value="1" {{ $status === '1' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="0" {{ $status === '0' ? 'selected' : '' }}>
                        Tidak Aktif
                    </option>
                </select>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition"
                >
                    <i class="fa-solid fa-filter mr-1"></i>
                    Filter
                </button>

                @if ($search || $status !== null && $status !== '')
                    <a
                        href="{{ route('internal.fields.index') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-sm font-medium text-center transition"
                    >
                        Reset
                    </a>
                @endif

            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">No.</th>
                        <th class="px-5 py-4 font-semibold">Nama Bidang</th>
                        <th class="px-5 py-4 font-semibold">Slug</th>
                        <th class="px-5 py-4 font-semibold">Deskripsi</th>
                        <th class="px-5 py-4 font-semibold">Jumlah Lowongan</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($fields as $index => $field)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-5 py-4 text-slate-500">
                                {{ $fields->firstItem() + $index }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-800">
                                    {{ $field->name }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-slate-500">
                                {{ $field->slug }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                <div class="max-w-xs truncate">
                                    {{ $field->description ?: 'Tidak ada deskripsi' }}
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-semibold text-slate-800">
                                    {{ $field->jobs_count }}
                                </span>
                                <span class="text-xs text-slate-500 ml-1">
                                    lowongan
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                @if ($field->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-2"></span>
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">

                                    <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-layer-group text-xl"></i>
                                    </div>

                                    <h4 class="font-semibold text-slate-700">
                                        Belum Ada Bidang Magang
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Data bidang magang belum tersedia.
                                    </p>

                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        @if ($fields->hasPages())
            <div class="px-5 py-4 border-t border-slate-200">
                {{ $fields->links() }}
            </div>
        @endif

    </div>

</div>
@endsection