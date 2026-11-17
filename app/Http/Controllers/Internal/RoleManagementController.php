<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;

class RoleManagementController extends Controller
{
    public function index()
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'code' => 'superadmin',
                'description' => 'Memiliki akses penuh untuk mengelola seluruh sistem.',
                'level' => 'Akses Penuh',
                'color' => 'purple',
                'permissions' => [
                    'Dashboard',
                    'Manajemen Akun Admin',
                    'Role & Hak Akses',
                    'Pendaftaran Peserta',
                    'Peserta PKL',
                    'Periode & Kuota',
                    'Bidang Magang',
                    'Notifikasi & Email',
                    'Audit Log Aktivitas',
                    'Pengaturan Sistem',
                ],
            ],
            [
                'name' => 'Admin',
                'code' => 'admin',
                'description' => 'Mengelola operasional pendaftaran dan pelaksanaan PKL.',
                'level' => 'Akses Operasional',
                'color' => 'blue',
                'permissions' => [
                    'Dashboard',
                    'Pendaftaran Peserta',
                    'Verifikasi Pendaftaran',
                    'Peserta PKL',
                    'Periode & Kuota',
                    'Bidang Magang',
                    'Penempatan Peserta',
                    'Status PKL',
                    'Notifikasi',
                    'Audit Log',
                    'Laporan & Rekap',
                ],
            ],
        ];

        return view('internal.roles.index', compact('roles'));
    }
}