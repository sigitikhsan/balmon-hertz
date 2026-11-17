@extends('layouts.admin')

@section('title', 'Audit Log Aktivitas')
@section('page_title', 'Audit Log Aktivitas')
@section('page_subtitle', 'Pantau dan telusuri riwayat aktivitas pengguna dalam sistem Magang-Hertz.')

@section('content')
<div class="space-y-6">

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Total Aktivitas</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-2">
                {{ number_format($totalActivities, 0, ',', '.') }}
            </h3>
            <div class="mt-3 text-xs text-slate-500">
                Seluruh aktivitas yang tercatat
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Aktivitas Hari Ini</p>
            <h3 class="text-2xl font-bold text-blue-600 mt-2">
                {{ number_format($todayActivities, 0, ',', '.') }}
            </h3>
            <div class="mt-3 text-xs text-blue-600">
                <i class="fa-solid fa-calendar-day mr-1"></i>
                Aktivitas pada hari ini
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Pengguna Tercatat</p>
            <h3 class="text-2xl font-bold text-indigo-600 mt-2">
                {{ number_format($totalUsers, 0, ',', '.') }}
            </h3>
            <div class="mt-3 text-xs text-indigo-600">
                <i class="fa-solid fa-users mr-1"></i>
                Pengguna yang memiliki aktivitas
            </div>
        </div>

    </div>

    {{-- Tabel Aktivitas --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">

        <div class="p-5 border-b border-slate-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">
                        Riwayat Aktivitas
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Daftar aktivitas yang tercatat dalam sistem.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('internal.audit-logs.index') }}"
                      class="flex flex-col sm:flex-row gap-3">

                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari aktivitas..."
                            class="w-full sm:w-56 pl-9 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                    </div>

                    <select
                        name="action"
                        class="px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <option value="">Semua Aktivitas</option>

                        @foreach($actions as $item)
                            <option value="{{ $item }}" {{ $action === $item ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $item)) }}
                            </option>
                        @endforeach

                    </select>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                        <i class="fa-solid fa-filter mr-1"></i>
                        Filter
                    </button>

                    @if($search || $action)
                        <a href="{{ route('internal.audit-logs.index') }}"
                           class="px-4 py-2 bg-slate-100 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-200 text-center">
                            Reset
                        </a>
                    @endif

                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Pengguna</th>
                        <th class="px-5 py-4 font-semibold">Aktivitas</th>
                        <th class="px-5 py-4 font-semibold">Objek Data</th>
                        <th class="px-5 py-4 font-semibold">Alamat IP</th>
                        <th class="px-5 py-4 font-semibold">Waktu</th>
                        <th class="px-5 py-4 font-semibold">Detail</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($auditLogs as $log)
                        <tr class="hover:bg-slate-50">

                            <td class="px-5 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $log->user ? $log->user->name : 'Sistem / Pengguna dihapus' }}
                                </div>
                                @if($log->user)
                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ $log->user->email }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                @if($log->model_type)
                                    <div>{{ class_basename($log->model_type) }}</div>
                                    <div class="text-xs text-slate-400 mt-1">
                                        ID: {{ $log->model_id ?? '-' }}
                                    </div>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $log->ip_address ?: '-' }}
                            </td>

                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                {{ $log->created_at ? $log->created_at->format('d/m/Y H:i') : '-' }}
                            </td>

                            <td class="px-5 py-4">
                                <details class="relative">
                                    <summary class="cursor-pointer text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat
                                    </summary>

                                    <div class="mt-2 p-3 bg-slate-50 border border-slate-200 rounded-lg min-w-64 max-w-sm">
                                        <p class="text-xs font-semibold text-slate-700 mb-1">
                                            Data Sebelumnya
                                        </p>
                                        <pre class="text-xs text-slate-600 whitespace-pre-wrap break-words">{{ $log->old_values ? json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'Tidak tersedia' }}</pre>

                                        <p class="text-xs font-semibold text-slate-700 mt-3 mb-1">
                                            Data Terbaru
                                        </p>
                                        <pre class="text-xs text-slate-600 whitespace-pre-wrap break-words">{{ $log->new_values ? json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'Tidak tersedia' }}</pre>
                                    </div>
                                </details>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-clipboard-list text-4xl text-slate-300 mb-3"></i>
                                    <p class="font-medium text-slate-600">
                                        Belum ada aktivitas tercatat
                                    </p>
                                    <p class="text-sm text-slate-400 mt-1">
                                        Riwayat aktivitas akan muncul di halaman ini setelah sistem mencatat aktivitas.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        @if($auditLogs->hasPages())
            <div class="p-5 border-t border-slate-200">
                {{ $auditLogs->links() }}
            </div>
        @endif

    </div>

</div>
@endsection