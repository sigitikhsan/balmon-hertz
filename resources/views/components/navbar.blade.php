<header class="fixed top-0 left-0 right-0 z-50 px-4 sm:px-6 lg:px-8 pt-4 sm:pt-5">
@php($navBase = request()->is('/') ? '' : url('/'))
@php($isJobs = request()->routeIs('jobs.*') || request()->routeIs('apply.*'))
@php($isStatus = request()->routeIs('status.*'))
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between gap-4 h-[72px] px-5 sm:px-7 rounded-full bg-white/85 backdrop-blur-xl border border-white/70 shadow-[0_10px_30px_rgba(8,36,92,0.08)]">
            <a href="{{ $navBase }}#beranda" class="shrink-0 inline-flex items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-offset-2 rounded-lg" aria-label="magang-hertz - Beranda">
                <img src="{{ asset('storage/images/logo.jpeg') }}" alt="Logo magang-hertz" class="h-11 sm:h-12 w-auto">
            </a>

            <nav class="hidden lg:flex items-center gap-1 text-[15px] font-medium text-[#08245C]" aria-label="Navigasi utama">
                <a href="{{ $navBase }}#beranda" class="px-3 py-2 rounded-lg hover:text-[#1265ED] hover:bg-[#EAF3FF]/70 transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]">Beranda</a>
                <a href="{{ $navBase }}#katalog" class="px-3 py-2 rounded-lg hover:text-[#1265ED] hover:bg-[#EAF3FF]/70 transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]">Katalog</a>
                <a href="{{ route('jobs.index') }}" class="px-3 py-2 rounded-lg transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] {{ $isJobs ? 'text-[#1265ED] bg-[#EAF3FF] font-semibold' : 'hover:text-[#1265ED] hover:bg-[#EAF3FF]/70' }}">Lowongan</a>
                <a href="{{ $navBase }}#keunggulan" class="px-3 py-2 rounded-lg hover:text-[#1265ED] hover:bg-[#EAF3FF]/70 transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]">Keunggulan</a>
                <a href="{{ $navBase }}#alur" class="px-3 py-2 rounded-lg hover:text-[#1265ED] hover:bg-[#EAF3FF]/70 transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]">Alur</a>
                <a href="{{ $navBase }}#faq" class="px-3 py-2 rounded-lg hover:text-[#1265ED] hover:bg-[#EAF3FF]/70 transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]">FAQ</a>
                <a href="{{ route('status.form') }}" class="px-3 py-2 rounded-lg transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] {{ $isStatus ? 'text-[#1265ED] bg-[#EAF3FF] font-semibold' : 'hover:text-[#1265ED] hover:bg-[#EAF3FF]/70' }}">Cek Status</a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="https://wa.me/6288213414826" target="_blank" rel="noopener" class="hidden sm:inline-flex items-center justify-center min-h-[44px] px-5 py-2.5 rounded-full text-sm font-semibold text-white bg-[#1265ED] hover:bg-[#0f56cc] shadow-[0_8px_20px_rgba(18,101,237,0.25)] transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-offset-2" aria-label="Hubungi Kami via WhatsApp">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Hubungi Kami
                </a>
                <button type="button" id="nav-toggle" class="lg:hidden inline-flex items-center justify-center w-11 h-11 rounded-full border border-[#DCEBFF] text-[#08245C] bg-white hover:bg-[#F7FAFF] transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-offset-2" aria-expanded="false" aria-controls="nav-mobile" aria-label="Buka menu navigasi">
                    <svg id="nav-icon-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <svg id="nav-icon-close" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <nav id="nav-mobile" class="hidden lg:hidden mt-3 rounded-2xl border border-[#DCEBFF] bg-white/95 backdrop-blur-xl px-4 sm:px-6 py-4 shadow-[0_14px_30px_rgba(8,36,92,0.08)]" aria-label="Navigasi seluler">
            <div class="space-y-1 text-sm font-medium text-[#08245C]">
                <a href="{{ $navBase }}#beranda" class="block px-3 py-3 rounded-xl hover:bg-[#EAF3FF] hover:text-[#1265ED] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]">Beranda</a>
                <a href="{{ $navBase }}#katalog" class="block px-3 py-3 rounded-xl hover:bg-[#EAF3FF] hover:text-[#1265ED] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]">Katalog</a>
                <a href="{{ route('jobs.index') }}" class="block px-3 py-3 rounded-xl transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] {{ $isJobs ? 'bg-[#EAF3FF] text-[#1265ED] font-semibold' : 'hover:bg-[#EAF3FF] hover:text-[#1265ED]' }}">Lowongan</a>
                <a href="{{ $navBase }}#keunggulan" class="block px-3 py-3 rounded-xl hover:bg-[#EAF3FF] hover:text-[#1265ED] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]">Keunggulan</a>
                <a href="{{ $navBase }}#alur" class="block px-3 py-3 rounded-xl hover:bg-[#EAF3FF] hover:text-[#1265ED] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]">Alur</a>
                <a href="{{ $navBase }}#faq" class="block px-3 py-3 rounded-xl hover:bg-[#EAF3FF] hover:text-[#1265ED] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]">FAQ</a>
                <a href="{{ route('status.form') }}" class="block px-3 py-3 rounded-xl transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] {{ $isStatus ? 'bg-[#EAF3FF] text-[#1265ED] font-semibold' : 'hover:bg-[#EAF3FF] hover:text-[#1265ED]' }}">Cek Status</a>
                <a href="https://wa.me/6288213414826" target="_blank" rel="noopener" class="sm:hidden mt-2 flex items-center justify-center min-h-[44px] px-4 py-3 rounded-xl text-sm font-semibold text-white bg-[#1265ED] hover:bg-[#0f56cc] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED]" aria-label="Hubungi Kami via WhatsApp">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Hubungi Kami
                </a>
            </div>
        </nav>
    </div>
</header>
<script>
(function () {
    var toggle = document.getElementById('nav-toggle');
    var menu = document.getElementById('nav-mobile');
    var iconOpen = document.getElementById('nav-icon-open');
    var iconClose = document.getElementById('nav-icon-close');
    if (!toggle || !menu) return;
    function setOpen(open) {
        menu.classList.toggle('hidden', !open);
        iconOpen.classList.toggle('hidden', open);
        iconClose.classList.toggle('hidden', !open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
    }
    var isOpen = false;
    toggle.addEventListener('click', function () { isOpen = !isOpen; setOpen(isOpen); });
    menu.addEventListener('click', function (e) { if (e.target.closest('a')) { isOpen = false; setOpen(false); } });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && isOpen) { isOpen = false; setOpen(false); toggle.focus(); } });
})();
</script>
