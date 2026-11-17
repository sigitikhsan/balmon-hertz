@extends('layouts.admin')

@section('title', 'Peserta PKL')
@section('page_title', 'Peserta PKL')
@section('page_subtitle', 'Kelola data peserta yang telah diterima dalam program magang/PKL.')

@section('content')
<div class="space-y-6">

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Peserta PKL</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-2">
                        {{ $totalParticipants }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Data Ditampilkan</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-2">
                        {{ $participants->total() }}
                    </h3>
                </div>
                <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                    <i class="fa-solid fa-user-check text-lg"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- Tabel Peserta --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">

        <div class="p-5 border-b border-slate-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h3 class="text-lg font-semibold text-slate-800">
                        Daftar Peserta PKL
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Data peserta yang telah dinyatakan diterima.
                    </p>
                </div>

                <form action="{{ route('internal.participants.index') }}"
                      method="GET"
                      class="flex flex-col sm:flex-row gap-2">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama, email, nomor..."
                        class="w-full sm:w-72 px-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i>
                        Cari
                    </button>

                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">

                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">No.</th>
                        <th class="px-5 py-4 font-semibold">Peserta</th>
                        <th class="px-5 py-4 font-semibold">Nomor Pendaftaran</th>
                        <th class="px-5 py-4 font-semibold">Posisi Magang</th>
                        <th class="px-5 py-4 font-semibold">Bidang</th>
                        <th class="px-5 py-4 font-semibold">Periode</th>
                        <th class="px-5 py-4 font-semibold">Tanggal Diterima</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse ($participants as $index => $participant)

                        <tr class="hover:bg-slate-50 transition">

                            <td class="px-5 py-4 text-slate-500">
                                {{ $participants->firstItem() + $index }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $participant->nama }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ $participant->email }}
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ $participant->no_hp }}
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-medium text-blue-600">
                                    {{ $participant->application_no }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-slate-700">
                                {{ $participant->job->title ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $participant->job->field->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $participant->job->period->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $participant->decided_at
                                    ? $participant->decided_at->format('d M Y')
                                    : '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">

                                    <div class="w-14 h-14 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-user-group text-xl"></i>
                                    </div>

                                    <h4 class="font-semibold text-slate-700">
                                        Belum Ada Peserta PKL
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Peserta yang telah diterima akan ditampilkan di sini.
                                    </p>

                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        @if ($participants->hasPages())
            <div class="px-5 py-4 border-t border-slate-200">
                {{ $participants->links() }}
            </div>
        @endif

    </div>

</div>
@endsection