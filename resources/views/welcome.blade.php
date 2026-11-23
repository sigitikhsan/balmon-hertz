{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="bg-slate-50 min-h-screen font-sans antialiased text-slate-800">

    @include('components.navbar')

    @include('partials.hero')

    @include('partials.latest-internships')

    @include('partials.keunggulan')

    @include('partials.alur')

    @include('partials.faq')

    <footer id="kontak" class="relative bg-[#08245C] text-white overflow-hidden">
        <!-- curved wave top -->
        <div class="absolute top-0 left-0 right-0 leading-[0] pointer-events-none" aria-hidden="true">
            <svg viewBox="0 0 1440 100" preserveAspectRatio="none" class="block w-full h-[56px] sm:h-[72px] lg:h-[90px]">
                <path d="M0,40 C240,100 480,100 720,70 C960,40 1200,0 1440,20 L1440,0 L0,0 Z" fill="#F7FBFF"/>
            </svg>
        </div>
        <!-- decorative shape -->
        <div class="absolute bottom-0 right-0 w-72 h-72 pointer-events-none" aria-hidden="true">
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-[#1265ED]/15 rounded-tl-full"></div>
            <div class="absolute bottom-0 right-0 w-40 h-40 bg-[#1265ED]/10 rounded-tl-full"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 sm:pt-28 lg:pt-32 pb-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                    <a href="{{ $navBase ?? '' }}#beranda" class="inline-flex items-center shrink-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-offset-2 focus-visible:ring-offset-[#08245C] rounded-lg">
                        <span class="inline-flex items-center justify-center bg-white rounded-xl px-3 py-2">
                            <img src="/images/logo-hertz.png" alt="Logo magang-hertz" class="h-10 w-auto">
                        </span>
                    </a>
                    <div class="hidden sm:block w-px h-10 bg-white/20" aria-hidden="true"></div>
                    <p class="text-sm text-white/80">&copy; {{ date('Y') }} magang-hertz Balmon. Hak cipta dilindungi.</p>
                </div>

                <nav class="flex flex-wrap gap-x-6 gap-y-3 text-sm text-white/85" aria-label="Navigasi footer">
                    <a href="#beranda" class="hover:text-white transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] rounded">Beranda</a>
                    <a href="#katalog" class="hover:text-white transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] rounded">Katalog</a>
                    <a href="#keunggulan" class="hover:text-white transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] rounded">Keunggulan</a>
                    <a href="#alur" class="hover:text-white transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] rounded">Alur</a>
                    <a href="#faq" class="hover:text-white transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] rounded">FAQ</a>
                    <a href="{{ route('status.form') }}" class="hover:text-white transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] rounded">Cek Status</a>
                </nav>
            </div>
        </div>
    </footer>

</main>
@endsection
