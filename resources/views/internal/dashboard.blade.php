@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan aktivitas magang')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Lowongan Open -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 transition-transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-2">
                <h3 class="text-sm font-medium text-slate-600">Total Lowongan</h3>
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-briefcase text-blue-600"></i>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-2xl font-bold text-slate-900">{{ $jobCount }}</p>
                {{-- TODO: Ganti data dummy dengan data real dari backend --}}\n                <p class="text-xs mt-1 text-green-600">
                    <i class="fa-solid fa-arrow-up-right mr-1"></i>
                    +12% dari bulan lalu
                </p>
            </div>
        </div>

        <!-- Total Peserta -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 transition-transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-2">
                <h3 class="text-sm font-medium text-slate-600">Total Peserta</h3>
                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-users text-purple-600"></i>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-2xl font-bold text-slate-900">--</p>
                {{-- TODO: Ganti data dummy dengan data real dari backend --}}\n                <p class="text-xs mt-1 text-green-600">
                    <i class="fa-solid fa-arrow-up-right mr-1"></i>
                    +8% dari bulan lalu
                </p>
            </div>
        </div>

        <!-- Periode Aktif -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 transition-transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-2">
                <h3 class="text-sm font-medium text-slate-600">Periode Aktif</h3>
                <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-calendar-days text-orange-600"></i>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-2xl font-bold text-slate-900">--</p>
                {{-- TODO: Ganti data dummy dengan data real dari backend --}}\n                <p class="text-xs mt-1 text-slate-500">Angkutan {{ date('m/Y') }}</p>
            </div>
        </div>

        <!-- Bidang Magang -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 transition-transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-2">
                <h3 class="text-sm font-medium text-slate-600">Bidang Magang</h3>
                <div class="w-8 h-8 bg-teal-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-briefcase text-teal-600"></i>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-2xl font-bold text-slate-900">--</p>
                {{-- TODO: Ganti data dummy dengan data real dari backend --}}\n                <p class="text-xs mt-1 text-slate-500">Tersedia</p>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Distribusi Status Peserta</h2>
        <div class="relative h-80" id="statusChart">
            <div class="absolute inset-0 flex items-center justify-center text-slate-500">
                {{-- TODO: Ganti placeholder chart dengan Chart.js setelah data real tersedia --}}\n                <div class="text-center">
                    <i class="fa-solid fa-chart-bar text-4xl mb-2"></i>
                    <p>Chart akan ditampilkan setelah data dari backend tersedia</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Table -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Aktivitas Terbaru</h2>
        @include('internal.partials.activity-table')
    </div>
</div>

@push('scripts')
<script>
    // Guard for chart initialization
    document.addEventListener('DOMContentLoaded', function() {
        const chartEl = document.getElementById('statusChart');
        if (chartEl) {
            // TODO: Inisialisasi Chart.js dengan data real dari backend
            // const ctx = chartEl.getContext('2d');
            // new Chart(ctx, {
            //     type: 'doughnut',
            //     data: {
            //         labels: ['Menunggu', 'Diterima', 'Ditolak', 'Perlu Perbaikan'],
            //         datasets: [{
            //             data: [12, 8, 3, 5],
            //             backgroundColor: ['#FBBF24', '#10B981', '#EF4444', '#3B82F6'],
            //             borderColor: ['#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'],
            //             borderWidth: 2
            //         }]
            //     },
            //     options: {
            //         responsive: true,
            //         maintainAspectRatio: false,
            //         plugins: {
            //             legend: {
            //                 position: 'bottom',
            //             }
            //         }
            //     }
            // });
        }
    });
</script>
@endpush
@endsection
