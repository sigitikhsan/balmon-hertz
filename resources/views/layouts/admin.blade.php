<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Magang Hertz</title>

    {{-- Tailwind CSS: dipertahankan untuk kompatibilitas halaman lama --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- Chart.js untuk halaman dashboard --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- CSS utama --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>

<body class="admin-body">

    @php
        $currentUser = auth()->user();

        $userName = $currentUser->name ?? 'Administrator';

        $userRole = strtolower($currentUser->role ?? 'admin');

        $isSuperAdmin = in_array($userRole, [
            'superadmin',
            'super_admin',
            'super-admin'
        ]);

        $roleLabel = $isSuperAdmin ? 'Super Admin' : 'Admin';

        $userInitial = strtoupper(substr($userName, 0, 1));

        /*
        |--------------------------------------------------------------------------
        | Menu Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdminMenu = [
            [
                'label' => 'Dashboard',
                'icon' => 'fa-gauge-high',
                'url' => route('internal.dashboard'),
                'section' => 'main'
            ],
            [
                'label' => 'Manajemen Akun Admin',
                'icon' => 'fa-users-gear',
                'url' => route('internal.admins.index'),
                'section' => 'main'
            ],
            [
                'label' => 'Role & Hak Akses',
                'icon' => 'fa-shield-halved',
                'url' => route('internal.roles.index'),
                'section' => 'main'
            ],
            [
                'label' => 'Pendaftaran Peserta',
                'icon' => 'fa-file-signature',
                'url' => route('internal.applications.index'),
                'section' => 'main'
            ],
            [
                'label' => 'Peserta PKL',
                'icon' => 'fa-users',
                'url' => route('internal.participants.index'),
                'section' => 'main'
            ],
            [
                'label' => 'Periode & Kuota',
                'icon' => 'fa-calendar-days',
                'url' => route('internal.periods.index'),
                'section' => 'main'
            ],
            [
                'label' => 'Bidang Magang',
                'icon' => 'fa-briefcase',
                'url' => route('internal.fields.index'),
                'section' => 'main'
            ],
            [
                'label' => 'Notifikasi & Email',
                'icon' => 'fa-bell',
                'url' => route('internal.notifications.index'),
                'section' => 'main'
            ],
            [
                'label' => 'Audit Log Aktivitas',
                'icon' => 'fa-clock-rotate-left',
                'url' =>  route('internal.audit-logs.index'),
                'section' => 'main'
            ],
            [
                'label' => 'Pengaturan Sistem',
                'icon' => 'fa-gear',
                'url' => route('internal.settings.index'),
                'section' => 'main'
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Menu Admin
        |--------------------------------------------------------------------------
        */

        $adminMenu = [
            [
                'label' => 'Dashboard',
                'icon' => 'fa-gauge-high',
                'url' => '#dashboard',
                'section' => 'main'
            ],
            [
                'label' => 'Pendaftaran Peserta',
                'icon' => 'fa-file-signature',
                'url' => '#pendaftaran',
                'section' => 'main'
            ],
            [
                'label' => 'Verifikasi Dokumen',
                'icon' => 'fa-clipboard-check',
                'url' => '#verifikasi',
                'section' => 'main'
            ],
            [
                'label' => 'Peserta PKL',
                'icon' => 'fa-users',
                'url' => '#peserta',
                'section' => 'main'
            ],
            [
                'label' => 'Periode & Kuota',
                'icon' => 'fa-calendar-days',
                'url' => '#periode',
                'section' => 'main'
            ],
            [
                'label' => 'Bidang Magang',
                'icon' => 'fa-briefcase',
                'url' => '#bidang',
                'section' => 'main'
            ],
            [
                'label' => 'Penempatan Peserta',
                'icon' => 'fa-map-location-dot',
                'url' => '#penempatan',
                'section' => 'main'
            ],
            [
                'label' => 'Status PKL',
                'icon' => 'fa-user-check',
                'url' => '#status-pkl',
                'section' => 'main'
            ],
            [
                'label' => 'Notifikasi & Email',
                'icon' => 'fa-bell',
                'url' => '#notifikasi',
                'section' => 'main'
            ],
            [
                'label' => 'Audit Log Aktivitas',
                'icon' => 'fa-clock-rotate-left',
                'url' => '#audit-log',
                'section' => 'main'
            ],
            [
                'label' => 'Laporan & Rekapitulasi',
                'icon' => 'fa-chart-line',
                'url' => '#laporan',
                'section' => 'main'
            ],
        ];

        $menuItems = $isSuperAdmin ? $superAdminMenu : $adminMenu;

        $pageTitle = trim($__env->yieldContent('page_title'));

        $isDashboard = strtolower($pageTitle) === 'dashboard';
    @endphp


    {{-- MOBILE OVERLAY --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>


    {{-- MAIN APPLICATION --}}
    <div class="admin-wrapper">

        {{-- ==========================================
             SIDEBAR
        =========================================== --}}

        <aside class="admin-sidebar" id="adminSidebar">

            {{-- LOGO --}}
            <div class="sidebar-brand">

                <div class="brand-logo">
                    <span>H</span>
                </div>

                <div class="brand-text">
                    <h2>MAGANG</h2>
                    <span>HERTZ</span>
                </div>

                <button type="button"
                    class="sidebar-close"
                    id="sidebarClose"
                    aria-label="Tutup sidebar">

                    <i class="fa-solid fa-xmark"></i>

                </button>

            </div>


            {{-- ROLE LABEL --}}
            <div class="sidebar-role">

                <div class="role-indicator"></div>

                <span>{{ $roleLabel }}</span>

                <span class="role-access">
                    Akses Sistem
                </span>

            </div>


            {{-- NAVIGATION --}}
            <nav class="sidebar-navigation">

                <div class="sidebar-menu-label">
                    MENU UTAMA
                </div>

                <ul class="sidebar-menu">

                    @foreach ($menuItems as $index => $item)

                        <li class="sidebar-menu-item">

                            <a href="{{ $item['url'] }}"
                                class="sidebar-link {{ ($index === 0 && $isDashboard) ? 'active' : '' }}"
                                data-menu-label="{{ $item['label'] }}">

                                <span class="sidebar-icon">
                                    <i class="fa-solid {{ $item['icon'] }}"></i>
                                </span>

                                <span class="sidebar-link-text">
                                    {{ $item['label'] }}
                                </span>

                                @if ($item['label'] === 'Pendaftaran Peserta')
                                    <span class="sidebar-menu-indicator">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </span>
                                @endif

                            </a>

                        </li>

                    @endforeach

                </ul>

            </nav>


            {{-- SIDEBAR FOOTER --}}
            <div class="sidebar-footer">

                <div class="sidebar-footer-info">

                    <div class="footer-logo">
                        <i class="fa-solid fa-building"></i>
                    </div>

                    <div>
                        <p>Magang Hertz</p>
                        <span>Internship Management</span>
                    </div>

                </div>

                <form action="{{ route('internal.logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="sidebar-logout">

                        <i class="fa-solid fa-right-from-bracket"></i>

                        <span>Keluar Sistem</span>

                    </button>

                </form>

            </div>

        </aside>



        {{-- ==========================================
             MAIN CONTENT
        =========================================== --}}

        <div class="admin-main">


            {{-- TOP NAVBAR --}}
            <header class="admin-navbar">

                <div class="navbar-left">

                    <button type="button"
                        class="sidebar-toggle"
                        id="sidebarToggle"
                        aria-label="Buka navigasi">

                        <i class="fa-solid fa-bars"></i>

                    </button>


                    <div class="navbar-search">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input type="text"
                            id="globalSearch"
                            placeholder="Cari menu, peserta, atau data lainnya..."
                            autocomplete="off">

                    </div>

                </div>


                <div class="navbar-right">


                    {{-- NOTIFICATION --}}
                    <button type="button"
                        class="navbar-notification"
                        aria-label="Notifikasi">

                        <i class="fa-regular fa-bell"></i>

                        <span class="notification-indicator"></span>

                    </button>


                    {{-- DIVIDER --}}
                    <div class="navbar-divider"></div>


                    {{-- USER PROFILE --}}
                    <div class="navbar-profile">

                        <div class="profile-information">

                            <p class="profile-name">
                                {{ $userName }}
                            </p>

                            <span class="profile-role">
                                {{ $roleLabel }}
                            </span>

                        </div>


                        <button type="button"
                            class="profile-avatar"
                            aria-label="Profil pengguna">

                            {{ $userInitial }}

                        </button>

                    </div>

                </div>

            </header>



            {{-- MOBILE TOPBAR --}}
            <div class="mobile-navbar">

                <button type="button"
                    id="mobileMenuToggle"
                    aria-label="Buka menu">

                    <i class="fa-solid fa-bars"></i>

                </button>

                <div class="mobile-brand">

                    <strong>MAGANG HERTZ</strong>

                    <span>{{ $roleLabel }}</span>

                </div>

                <div class="mobile-avatar">
                    {{ $userInitial }}
                </div>

            </div>



            {{-- PAGE CONTENT --}}
            <main class="admin-content">

                {{-- PAGE HEADER --}}
                <div class="page-header">

                    <div class="page-heading">

                        <div class="page-breadcrumb">

                            <span>Magang Hertz</span>

                            <i class="fa-solid fa-chevron-right"></i>

                            <span>{{ $roleLabel }}</span>

                        </div>


                        <h1>
                            @yield('page_title', 'Dashboard')
                        </h1>


                        <p>
                            @yield('page_subtitle', 'Selamat datang di sistem manajemen magang.')
                        </p>

                    </div>


                    <div class="page-header-actions">
                        @yield('actions')
                    </div>

                </div>



                {{-- FLASH MESSAGE --}}
                @if (session('success'))

                    <div class="alert alert-success">

                        <i class="fa-solid fa-circle-check"></i>

                        <span>{{ session('success') }}</span>

                    </div>

                @endif


                @if (session('error'))

                    <div class="alert alert-error">

                        <i class="fa-solid fa-circle-exclamation"></i>

                        <span>{{ session('error') }}</span>

                    </div>

                @endif


                {{-- VALIDATION ERRORS --}}
                @if ($errors->any())

                    <div class="alert alert-error">

                        <i class="fa-solid fa-circle-exclamation"></i>

                        <div>

                            <strong>Terjadi kesalahan:</strong>

                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>

                    </div>

                @endif



                {{-- CONTENT FROM CHILD VIEW --}}
                <section class="page-content">

                    @yield('content')

                </section>


                {{-- FOOTER --}}
                <footer class="admin-footer">

                    <span>
                        &copy; {{ date('Y') }} Magang Hertz.
                        All rights reserved.
                    </span>

                    <span>
                        Internship Management System
                    </span>

                </footer>

            </main>

        </div>

    </div>



    {{-- SIDEBAR JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const sidebar = document.getElementById('adminSidebar');

            const overlay = document.getElementById('sidebarOverlay');

            const toggleButton = document.getElementById('sidebarToggle');

            const mobileToggle = document.getElementById('mobileMenuToggle');

            const closeButton = document.getElementById('sidebarClose');


            function openSidebar() {

                sidebar.classList.add('sidebar-open');

                overlay.classList.add('overlay-active');

                document.body.classList.add('sidebar-active');

            }


            function closeSidebar() {

                sidebar.classList.remove('sidebar-open');

                overlay.classList.remove('overlay-active');

                document.body.classList.remove('sidebar-active');

            }


            if (toggleButton) {
                toggleButton.addEventListener('click', openSidebar);
            }


            if (mobileToggle) {
                mobileToggle.addEventListener('click', openSidebar);
            }


            if (closeButton) {
                closeButton.addEventListener('click', closeSidebar);
            }


            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }


            document.addEventListener('keydown', function (event) {

                if (event.key === 'Escape') {
                    closeSidebar();
                }

            });


            window.addEventListener('resize', function () {

                if (window.innerWidth > 1024) {
                    closeSidebar();
                }

            });


            /*
             * Pencarian sederhana untuk menu sidebar.
             * Fitur pencarian data akan dikembangkan pada tahap berikutnya.
             */

            const searchInput = document.getElementById('globalSearch');

            if (searchInput) {

                searchInput.addEventListener('keydown', function (event) {

                    if (event.key === 'Enter') {

                        const keyword = this.value.trim().toLowerCase();

                        if (!keyword) {
                            return;
                        }

                        const menuLinks = document.querySelectorAll('.sidebar-link');

                        let found = false;

                        menuLinks.forEach(function (link) {

                            const label = link.innerText.toLowerCase();

                            if (!found && label.includes(keyword)) {

                                link.click();

                                found = true;

                            }

                        });

                        if (!found) {
                            alert('Menu "' + this.value + '" tidak ditemukan.');
                        }

                    }

                });

            }


        });
    </script>


    {{-- PAGE SCRIPTS --}}
    @stack('scripts')

</body>

</html>