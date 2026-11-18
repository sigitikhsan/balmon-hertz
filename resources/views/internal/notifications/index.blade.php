@extends('layouts.admin')

@section('title', 'Notifikasi & Email')
@section('page_title', 'Notifikasi & Email')
@section('page_subtitle', 'Pantau riwayat pengiriman email dan kelola template notifikasi sistem.')

@section('content')
<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700" role="status">
            {{ session('success') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800" role="alert">
            {{ session('warning') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Total Email</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-2">
                {{ number_format($totalEmails, 0, ',', '.') }}
            </h3>
            <div class="mt-3 text-xs text-slate-500">Seluruh riwayat email</div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Email Terkirim</p>
            <h3 class="text-2xl font-bold text-green-600 mt-2">
                {{ number_format($sentEmails, 0, ',', '.') }}
            </h3>
            <div class="mt-3 text-xs text-green-600">
                <i class="fa-solid fa-circle-check mr-1"></i>
                Berhasil dikirim
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Email Gagal</p>
            <h3 class="text-2xl font-bold text-red-600 mt-2">
                {{ number_format($failedEmails, 0, ',', '.') }}
            </h3>
            <div class="mt-3 text-xs text-red-600">
                <i class="fa-solid fa-circle-xmark mr-1"></i>
                Gagal dikirim
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500">Email Menunggu</p>
            <h3 class="text-2xl font-bold text-amber-600 mt-2">
                {{ number_format($pendingEmails, 0, ',', '.') }}
            </h3>
            <div class="mt-3 text-xs text-amber-600">
                <i class="fa-solid fa-clock mr-1"></i>
                Menunggu pengiriman
            </div>
        </div>
    </div>

    {{-- Riwayat Email --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-5 border-b border-slate-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Riwayat Pengiriman Email</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Daftar aktivitas pengiriman email kepada peserta.
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('internal.notifications.index') }}"
                  class="mt-5 flex flex-col md:flex-row gap-3">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari email penerima atau subjek..."
                       class="w-full md:flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                <select name="status"
                        class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="sent" {{ $status === 'sent' ? 'selected' : '' }}>Terkirim</option>
                    <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Gagal</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="superseded" {{ $status === 'superseded' ? 'selected' : '' }}>Kedaluwarsa</option>
                </select>

                <button type="submit"
                        class="rounded-lg bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 text-sm font-medium">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i>
                    Cari
                </button>

                <a href="{{ route('internal.notifications.index') }}"
                   class="rounded-lg border border-slate-300 hover:bg-slate-50 text-slate-600 px-5 py-2.5 text-sm text-center">
                    Reset
                </a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr>
                        <th class="px-5 py-4">Penerima</th>
                        <th class="px-5 py-4">Subjek Email</th>
                        <th class="px-5 py-4">Channel</th>
                        <th class="px-5 py-4">Tanggal Pengiriman</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Keterangan</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($emailLogs as $email)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <div class="font-medium text-slate-800">
                                    {{ $email->to_email }}
                                </div>
                                <div class="text-xs text-slate-400 mt-1">
                                    {{ $email->application ? $email->application->application_no : 'Email sistem' }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-slate-700">
                                {{ $email->subject }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ ucfirst($email->channel) }}
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $email->sent_at ? $email->sent_at->format('d M Y, H:i') : '-' }}
                            </td>

                            <td class="px-5 py-4">
                                @if($email->status === 'sent')
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Terkirim
                                    </span>
                                @elseif($email->status === 'failed')
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        Gagal
                                    </span>
                                @elseif($email->status === 'pending')
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        Menunggu
                                    </span>
                                @elseif($email->status === 'superseded')
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        Kedaluwarsa
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        {{ ucfirst($email->status) }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $email->error_message ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if($email->status === 'failed' && $email->application_status_history_id)
                                    <form action="{{ route('internal.notifications.retry', $email) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-2 rounded-lg text-sm text-blue-700 bg-blue-50 hover:bg-blue-100">
                                            <i class="fa-solid fa-rotate-right mr-2"></i>
                                            Kirim ulang
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-regular fa-envelope-open text-4xl text-slate-300 mb-3"></i>
                                    <p class="font-medium text-slate-600">Belum ada riwayat email</p>
                                    <p class="text-sm text-slate-400 mt-1">
                                        Riwayat pengiriman email akan ditampilkan di sini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($emailLogs->hasPages())
            <div class="p-5 border-t border-slate-200">
                {{ $emailLogs->links() }}
            </div>
        @endif
    </div>

    {{-- Template Email --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-5 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Template Email</h3>
            <p class="text-sm text-slate-500 mt-1">
                Daftar template email yang digunakan oleh sistem.
            </p>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($templates as $template)
                <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h4 class="font-semibold text-slate-800">
                            {{ $template->subject }}
                        </h4>
                        <p class="text-xs text-slate-400 mt-1">
                            Key: {{ $template->key }}
                        </p>
                        <p class="text-sm text-slate-500 mt-2">
                            {{ \Illuminate\Support\Str::limit(strip_tags($template->body), 120) }}
                        </p>
                    </div>

                    <div>
                        @if($template->is_active)
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <i class="fa-regular fa-file-lines text-4xl text-slate-300 mb-3"></i>
                    <p class="font-medium text-slate-600">Belum ada template email</p>
                    <p class="text-sm text-slate-400 mt-1">
                        Template email akan muncul setelah ditambahkan ke sistem.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
