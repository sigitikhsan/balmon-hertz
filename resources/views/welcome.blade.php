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

    <footer id="kontak" class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm">
            <p>&copy; {{ date('Y') }} magang-hertz Balmon. Hak cipta dilindungi.</p>
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
                <a href="#beranda" class="hover:text-white transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 rounded">Beranda</a>
                <a href="#katalog" class="hover:text-white transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 rounded">Katalog</a>
                <a href="#keunggulan" class="hover:text-white transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 rounded">Keunggulan</a>
                <a href="#alur" class="hover:text-white transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 rounded">Alur</a>
                <a href="#faq" class="hover:text-white transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 rounded">FAQ</a>
            </div>
        </div>
    </footer>

</main>
@endsection
