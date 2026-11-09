@extends('layouts.admin')

@section('content')

<div class="p-8">
    <h1 class="text-2xl font-bold text-slate-900 mb-4">Dashboard Internal</h1>
    <p class="text-slate-600">Selamat datang, {{ Auth::user()->name }} ({{ Auth::user()->role }})</p>
    <!-- Contoh widget ringkasan, dapat dikembangkan -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800">Total Lowongan</h2>
            <p class="text-3xl font-bold text-slate-900 mt-2">{{ $latestInternships->count() }}</p>
        </div>
        <!-- Tambah widget lain sesuai kebutuhan -->
    </div>

</div>
@endsection
