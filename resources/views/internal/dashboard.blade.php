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
                <p class="text-xs mt-1 text-green-600">
                    <i class="fa-solid fa-arrow-up-right mr-1"></i>
                    +8% dari bulan lalu
                </p>
            </div>
        </div>

        <!-- Total Peserta -->
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 transition-transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-2">
                <h3 class="text-sm font-medium text-slate-600">Total Pendaftaran</h3>
                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-users text-purple-600"></i>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-2xl font-bold text-slate-900">{{ $totalApplications }}</p>
                <p class="text-xs mt-1 text-slate-500">
                    <i class="fa-solid fa-users mr-1"></i>
                    Peserta terdaftar
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
                <p class="text-2xl font-bold text-slate-900">{{ $activePeriodCount }}</p>
                <p class="text-xs mt-1 text-slate-500">
                    <i class="fa-solid fa-calendar mr-1"></i>
                    Periode sedang aktif
                </p>
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
                <p class="text-2xl font-bold text-slate-900">{{ $fieldCount }}</p>
                <p class="text-xs mt-1 text-slate-500">
                    <i class="fa-solid fa-briefcase mr-1"></i>
                    Terdaftar
                </p>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Distribusi Status Peserta</h2>
        <div class="relative h-80" id="statusChart">
            <canvas id="statusChartCanvas" class="absolute inset-0" height="80" width="400"></canvas>
        </div>
        <p class="mt-2 text-sm text-slate-500 center-align">Distribusi: belum_mulai, sedang_berlangsung, selesai</p>
    </div>

    <script>
        // Initialize Chart.js with real data from Laravel
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('statusChartCanvas')?.getContext('2d');
            if (!ctx) return;

            const statusCounts = {{ json_encode($statusCounts) }};

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Belum Mulai', 'Sedang Berlangsung', 'Selesai'],
                    datasets: [{
                        data: [
                            statusCounts.belum_mulai ?? 0,
                            statusCounts.sedang_berlangsung ?? 0,
                            statusCounts.selesai ?? 0
                        ],
                        backgroundColor: ['#FBBF24', '#3B82F6', '#10B981'],
                        borderColor: ['#FFFFFF', '#FFFFFF', '#FFFFFF'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
    @push('scripts')
    <script>
        // Guard for chart initialization (handled above in inline script)
    </script>
    @endpush
@endsection