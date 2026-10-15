{{-- resources/views/jobs/status.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="bg-slate-50 min-h-screen font-sans antialiased text-slate-800">

    @include('components.navbar')

    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Cek Status Lamaran</h1>
        <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Masukkan nomor lamaran atau email yang dipakai mendaftar.</p>

        <form method="GET" action="{{ route('status.result') }}" class="mt-6 bg-white rounded-xl border border-slate-200 p-6 space-y-5">
            <div>
                <label for="no" class="block text-sm font-bold text-slate-900 mb-2">Nomor lamaran</label>
                <input type="text" id="no" name="no" value="{{ request('no') }}" placeholder="MH-2026-0001" class="w-full min-h-[44px] px-4 rounded-lg bg-white border border-slate-200 text-sm font-normal text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
            </div>
            <div>
                <label for="email" class="block text-sm font-bold text-slate-900 mb-2">atau email</label>
                <input type="email" id="email" name="email" value="{{ request('email') }}" placeholder="nama@email.com" class="w-full min-h-[44px] px-4 rounded-lg bg-white border border-slate-200 text-sm font-normal text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center py-3 px-4 min-h-[44px] bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                Cek Status
            </button>
        </form>
    </section>

</main>
@endsection
