@extends('layouts.admin')

@section('title', 'Role & Hak Akses')
@section('page_title', 'Role & Hak Akses')
@section('page_subtitle', 'Kelola informasi peran dan hak akses pengguna sistem magang Balmon.')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                Daftar Role Pengguna
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Informasi peran dan hak akses setiap pengguna sistem.
            </p>
        </div>

        <div>
            <span class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-blue-50 text-blue-700 text-sm font-medium">
                <i class="fa-solid fa-shield-halved"></i>
                2 Role Terdaftar
            </span>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Role</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2">
                        {{ count($roles) }}
                    </h3>
                </div>

                <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fa-solid fa-user-shield text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Hak Akses</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2">
                        {{ count($roles[0]['permissions']) + count($roles[1]['permissions']) }}
                    </h3>
                </div>

                <div class="w-11 h-11 rounded-lg bg-purple-50 flex items-center justify-center">
                    <i class="fa-solid fa-key text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- ROLE LIST --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        @foreach ($roles as $role)

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

                {{-- ROLE HEADER --}}
                <div class="p-5 border-b border-slate-200">

                    <div class="flex items-start justify-between gap-3">

                        <div class="flex items-center gap-3">

                            @if ($role['color'] === 'purple')
                                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                                    <i class="fa-solid fa-crown text-xl"></i>
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <i class="fa-solid fa-user-gear text-xl"></i>
                                </div>
                            @endif

                            <div>
                                <h3 class="text-base font-semibold text-slate-800">
                                    {{ $role['name'] }}
                                </h3>

                                <p class="text-xs text-slate-500 mt-1">
                                    Kode: {{ $role['code'] }}
                                </p>
                            </div>

                        </div>

                        @if ($role['color'] === 'purple')
                            <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-semibold">
                                Super Admin
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                Admin
                            </span>
                        @endif

                    </div>

                    <p class="text-sm text-slate-500 mt-4">
                        {{ $role['description'] }}
                    </p>

                </div>

                {{-- ROLE INFORMATION --}}
                <div class="p-5 space-y-5">

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">
                            Tingkat Akses
                        </span>

                        <span class="text-sm font-semibold text-slate-700">
                            {{ $role['level'] }}
                        </span>
                    </div>

                    <div class="border-t border-slate-100 pt-5">

                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-semibold text-slate-800">
                                Hak Akses Menu
                            </h4>

                            <span class="text-xs font-medium text-slate-500">
                                {{ count($role['permissions']) }} Hak Akses
                            </span>
                        </div>

                        <div class="space-y-3">

                            @foreach ($role['permissions'] as $permission)

                                <div class="flex items-center gap-3">

                                    @if ($role['color'] === 'purple')
                                        <div class="w-6 h-6 rounded-full bg-purple-50 flex items-center justify-center">
                                            <i class="fa-solid fa-check text-purple-600 text-xs"></i>
                                        </div>
                                    @else
                                        <div class="w-6 h-6 rounded-full bg-blue-50 flex items-center justify-center">
                                            <i class="fa-solid fa-check text-blue-600 text-xs"></i>
                                        </div>
                                    @endif

                                    <span class="text-sm text-slate-600">
                                        {{ $permission }}
                                    </span>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

                {{-- ROLE FOOTER --}}
                <div class="px-5 py-4 bg-slate-50 border-t border-slate-200">

                    <div class="flex items-center justify-between">

                        <span class="text-xs text-slate-500">
                            Hak akses yang terdaftar
                        </span>

                        <span class="text-xs font-semibold text-slate-700">
                            {{ count($role['permissions']) }} Menu
                        </span>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

    {{-- INFORMATION --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">

        <div class="flex items-start gap-3">

            <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-circle-info"></i>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-blue-800">
                    Informasi Role & Hak Akses
                </h3>

                <p class="text-sm text-blue-700 mt-1 leading-relaxed">
                    Halaman ini menampilkan pembagian hak akses antara Super Admin
                    dan Admin. Pengaturan hak akses yang ditampilkan masih berupa
                    informasi statis dan belum dapat diubah melalui halaman ini.
                </p>
            </div>

        </div>

    </div>

</div>
@endsection