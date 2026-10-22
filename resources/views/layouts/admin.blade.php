<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Magang Hertz</title>
    <!-- Tailwind CSS (CDN untuk mempermudah teman) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Ikon (FontAwesome/Heroicons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .bg-navy { background-color: #0B2A5B; }
        .text-navy { color: #0B2A5B; }
        .bg-blue-primary { background-color: #1E6FE8; }
        .text-blue-primary { color: #1E6FE8; }
        .bg-light-gray { background-color: #F3F6FB; }
    </style>
</head>
<body class="bg-light-gray font-sans text-gray-800">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-navy text-white flex-shrink-0 flex flex-col">
            <div class="p-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded flex items-center justify-center text-blue-primary font-bold text-xl">H</div>
                <div>
                    <h1 class="text-sm font-bold leading-none">MAGANG</h1>
                    <p class="text-xs tracking-widest uppercase">HERTZ</p>
                </div>
            </div>

            <nav class="mt-6 flex-grow">
                <ul class="space-y-1">
                    @php
                        $role = auth()->user()->role ?? 'admin';
                        $menu = [];
                        if ($role === 'super_admin') {
                            $menu = [
                                ['icon' => 'gauge', 'label' => 'Dashboard', 'route' => '#'],
                                ['icon' => 'users-gear', 'label' => 'Manajemen Akun Admin', 'route' => '#'],
                                ['icon' => 'shield-halved', 'label' => 'Role & Hak Akses', 'route' => '#'],
                                ['icon' => 'file-signature', 'label' => 'Pendaftaran Peserta', 'route' => '#'],
                                ['icon' => 'users', 'label' => 'Peserta PKL', 'route' => '#'],
                                ['icon' => 'calendar-days', 'label' => 'Periode & Kuota', 'route' => '#'],
                                ['icon' => 'briefcase', 'label' => 'Bidang Magang', 'route' => '#'],
                                ['icon' => 'bell', 'label' => 'Notifikasi & Email', 'route' => '#'],
                                ['icon' => 'clock-rotate-left', 'label' => 'Audit Log Aktivitas', 'route' => '#'],
                                ['icon' => 'gear', 'label' => 'Pengaturan Sistem', 'route' => '#'],
                            ];
                        } else {
                            $menu = [
                                ['icon' => 'gauge', 'label' => 'Dashboard', 'route' => '#'],
                                ['icon' => 'file-signature', 'label' => 'Pendaftaran Peserta', 'route' => '#'],
                                ['icon' => 'clipboard-check', 'label' => 'Verifikasi Dokumen', 'route' => '#'],
                                ['icon' => 'users', 'label' => 'Peserta PKL', 'route' => '#'],
                                ['icon' => 'calendar-days', 'label' => 'Periode & Kuota', 'route' => '#'],
                                ['icon' => 'briefcase', 'label' => 'Bidang Magang', 'route' => '#'],
                                ['icon' => 'map-location-dot', 'label' => 'Penempatan Peserta', 'route' => '#'],
                                ['icon' => 'user-check', 'label' => 'Status PKL', 'route' => '#'],
                                ['icon' => 'bell', 'label' => 'Notifikasi & Email', 'route' => '#'],
                                ['icon' => 'clock-rotate-left', 'label' => 'Audit Log Aktivitas', 'route' => '#'],
                                ['icon' => 'chart-line', 'label' => 'Laporan & Rekapitulasi', 'route' => '#'],
                            ];
                        }
                    @endphp

                    @foreach($menu as $item)
                        <li>
                            <a href="{{ $item['route'] }}" class="flex items-center gap-3 px-6 py-3 text-sm hover:bg-blue-primary/20 transition-colors {{ Request::is($item['route']) ? 'bg-blue-primary text-white border-l-4 border-white' : 'text-gray-300' }}">
                                <i class="fa-solid fa-{{ $item['icon'] }} w-5"></i>
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="p-6 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-sm text-gray-300 hover:text-white">
                        <i class="fa-solid fa-right-from-bracket w-5"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b flex items-center justify-between px-8">
                <div class="flex-grow max-w-md">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Cari menu, peserta, atau data lainnya..." class="w-full bg-light-gray border-none rounded-lg py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-blue-primary">
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <button class="relative text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    
                    <div class="flex items-center gap-3 border-l pl-6">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold leading-none">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', auth()->user()->role ?? 'Admin') }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-primary rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-8 flex-grow overflow-auto">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-navy">@yield('page_title')</h2>
                        <p class="text-gray-500 text-sm">@yield('page_subtitle')</p>
                    </div>
                    <div>
                        @yield('actions')
                    </div>
                </div>

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>