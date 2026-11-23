{{-- resources/views/jobs/index.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="bg-slate-50 min-h-screen font-sans antialiased text-slate-800">

    @include('components.navbar')

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-12">
        <div class="max-w-2xl mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">Lowongan Tersedia</h1>
            <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Semua posisi magang dan PKL yang dibuka Balmon saat ini.</p>
        </div>

        <div class="mb-8 max-w-xs">
            <label for="filter-tipe" class="block text-sm font-bold text-slate-900 mb-2">Tipe pekerjaan</label>
            <select id="filter-tipe" class="w-full min-h-[44px] px-4 rounded-lg bg-white border border-slate-200 text-sm font-normal text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                <option value="">Semua tipe</option>
                @foreach($types as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>
        </div>

        @if($jobs->isEmpty())
            <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
                <p class="font-semibold text-slate-900">Belum ada lowongan dibuka</p>
                <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Silakan kembali lagi nanti.</p>
            </div>
        @else
            <div id="job-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jobs as $job)
                    <div class="job-card bg-white rounded-xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition duration-200" data-type="{{ $job->type }}">
                        <div>
                            <p class="text-xs font-normal text-slate-600 leading-relaxed">{{ $job->field->name ?? 'Balmon' }}</p>
                            <h2 class="font-bold text-slate-900 text-base mt-1">{{ $job->title }}</h2>
                            @if($job->jurusan)
                                <p class="mt-1 text-xs font-normal text-slate-600 leading-relaxed">Jurusan: {{ $job->jurusan }}</p>
                            @endif
                            <div class="flex flex-wrap gap-2 mt-3 mb-6">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-md">Kuota {{ $job->quota }}</span>
                            </div>
                        </div>
                        <button type="button" data-modal-open="job-modal-{{ $job->id }}" class="w-full inline-flex items-center justify-center py-2.5 px-4 min-h-[44px] bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                            Lihat Selengkapnya
                        </button>
                    </div>

                    <div id="job-modal-{{ $job->id }}" class="job-modal hidden fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="job-modal-title-{{ $job->id }}">
                        <div class="absolute inset-0 bg-slate-900/60" data-modal-close></div>
                        <div class="relative bg-white rounded-xl border border-slate-200 max-w-lg w-full p-6 sm:p-8 max-h-[90vh] overflow-y-auto">
                            <p class="text-xs font-normal text-slate-600 leading-relaxed">{{ $job->field->name ?? 'Balmon' }}</p>
                            <h2 id="job-modal-title-{{ $job->id }}" class="font-bold text-slate-900 text-xl mt-1">{{ $job->title }}</h2>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-md">{{ $job->type }}</span>
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded-md">{{ $job->duration }}</span>
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-md">Kuota {{ $job->quota }}</span>
                            </div>
                            <p class="mt-4 text-sm font-normal text-slate-600 leading-relaxed">{{ $job->description }}</p>
                            @if($job->responsibilities)
                                <h3 class="mt-4 font-bold text-slate-900 text-sm">Job Responsibility</h3>
                                <p class="mt-1 text-sm font-normal text-slate-600 leading-relaxed">{{ $job->responsibilities }}</p>
                            @endif
                            @if($job->requirements)
                                <h3 class="mt-4 font-bold text-slate-900 text-sm">What We're Looking For</h3>
                                <p class="mt-1 text-sm font-normal text-slate-600 leading-relaxed">{{ $job->requirements }}</p>
                            @endif
                            @if($job->jurusan)
                                <h3 class="mt-4 font-bold text-slate-900 text-sm">Jurusan</h3>
                                <p class="mt-1 text-sm font-normal text-slate-600 leading-relaxed">{{ $job->jurusan }}</p>
                            @endif
                            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('apply.create', $job->slug) }}" class="flex-1 inline-flex items-center justify-center py-2.5 px-4 min-h-[44px] bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">Daftar</a>
                                <button type="button" data-modal-close class="flex-1 inline-flex items-center justify-center py-2.5 px-4 min-h-[44px] bg-white border border-slate-200 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">Tutup</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        @endif
    </section>

    <footer class="relative bg-[#08245C] text-white overflow-hidden">
        <div class="absolute top-0 left-0 right-0 leading-[0] pointer-events-none" aria-hidden="true">
            <svg viewBox="0 0 1440 100" preserveAspectRatio="none" class="block w-full h-[56px] sm:h-[72px] lg:h-[90px]">
                <path d="M0,40 C240,100 480,100 720,70 C960,40 1200,0 1440,20 L1440,0 L0,0 Z" fill="#F7FBFF"/>
            </svg>
        </div>
        <div class="absolute bottom-0 right-0 w-72 h-72 pointer-events-none" aria-hidden="true">
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-[#1265ED]/15 rounded-tl-full"></div>
            <div class="absolute bottom-0 right-0 w-40 h-40 bg-[#1265ED]/10 rounded-tl-full"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 sm:pt-28 lg:pt-32 pb-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                    <a href="{{ url('/') }}#beranda" class="inline-flex items-center shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-offset-2 focus-visible:ring-offset-[#08245C] rounded-lg">
                        <span class="inline-flex items-center justify-center bg-white rounded-xl px-3 py-2">
                            <img src="/images/logo-hertz.png" alt="Logo magang-hertz" class="h-10 w-auto">
                        </span>
                    </a>
                    <div class="hidden sm:block w-px h-10 bg-white/20" aria-hidden="true"></div>
                    <p class="text-sm text-white/80">&copy; {{ date('Y') }} magang-hertz Balmon. Hak cipta dilindungi.</p>
                </div>
                <nav class="flex flex-wrap gap-x-6 gap-y-3 text-sm text-white/85" aria-label="Navigasi footer">
                    <a href="{{ url('/') }}#beranda" class="hover:text-white transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] rounded">Beranda</a>
                    <a href="{{ url('/') }}#katalog" class="hover:text-white transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] rounded">Katalog</a>
                </nav>
            </div>
        </div>
    </footer>

</main>

<script>
(function () {
    var filter = document.getElementById('filter-tipe');
    if (filter) {
        filter.addEventListener('change', function () {
            var val = filter.value;
            document.querySelectorAll('.job-card').forEach(function (card) {
                card.style.display = (!val || card.getAttribute('data-type') === val) ? '' : 'none';
            });
        });
    }

    var lastFocus = null;
    function openModal(modal) {
        lastFocus = document.activeElement;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        var focusable = modal.querySelector('a, button:not([data-modal-close])');
        if (focusable) focusable.focus();
    }
    function closeModal(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (lastFocus) lastFocus.focus();
    }
    document.querySelectorAll('[data-modal-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var modal = document.getElementById(btn.getAttribute('data-modal-open'));
            if (modal) openModal(modal);
        });
    });
    document.querySelectorAll('.job-modal').forEach(function (modal) {
        modal.querySelectorAll('[data-modal-close]').forEach(function (el) {
            el.addEventListener('click', function () { closeModal(modal); });
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.job-modal:not(.hidden)').forEach(closeModal);
        }
    });
})();
</script>
@endsection
