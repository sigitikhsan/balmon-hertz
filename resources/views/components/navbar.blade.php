<header class="bg-[#f7f7f7] border-b border-slate-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="#beranda" class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 rounded" aria-label="magang-hertz - Beranda">
            <img src="{{ asset('storage/images/logo.jpeg') }}" alt="Logo magang-hertz" class="h-14 w-auto">
        </a>
        <nav class="hidden md:flex items-center space-x-8 text-sm font-medium text-slate-600" aria-label="Navigasi utama">
            <a href="#beranda" class="hover:text-slate-900 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 rounded">Beranda</a>
            <a href="#katalog" class="hover:text-slate-900 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 rounded">Katalog</a>
            <a href="#keunggulan" class="hover:text-slate-900 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 rounded">Keunggulan</a>
            <a href="#alur" class="hover:text-slate-900 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 rounded">Alur</a>
            <a href="#testimoni" class="hover:text-slate-900 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 rounded">Testimoni</a>
            <a href="#faq" class="hover:text-slate-900 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 rounded">FAQ</a>
        </nav>
        <div class="flex items-center space-x-3">
            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 rounded">Masuk</a>
            <a href="{{ route('register') }}" class="text-sm font-medium bg-slate-900 text-white px-4 py-2 min-h-[44px] inline-flex items-center rounded-lg hover:bg-slate-800 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">Daftar</a>
            <button type="button" id="nav-toggle" class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2" aria-expanded="false" aria-controls="nav-mobile" aria-label="Buka menu navigasi">
                <svg id="nav-icon-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                <svg id="nav-icon-close" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
    <nav id="nav-mobile" class="hidden md:hidden border-t border-slate-200 bg-[#f7f7f7] px-4 py-3 space-y-1 text-sm font-medium text-slate-600" aria-label="Navigasi seluler">
        <a href="#beranda" class="block px-3 py-3 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">Beranda</a>
        <a href="#katalog" class="block px-3 py-3 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">Katalog</a>
        <a href="#keunggulan" class="block px-3 py-3 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">Keunggulan</a>
        <a href="#alur" class="block px-3 py-3 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">Alur</a>
        <a href="#testimoni" class="block px-3 py-3 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">Testimoni</a>
        <a href="#faq" class="block px-3 py-3 rounded-lg hover:bg-slate-50 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">FAQ</a>
    </nav>
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
    toggle.addEventListener('click', function () {
        isOpen = !isOpen;
        setOpen(isOpen);
    });
    menu.addEventListener('click', function (e) {
        if (e.target.closest('a')) {
            isOpen = false;
            setOpen(false);
        }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) {
            isOpen = false;
            setOpen(false);
            toggle.focus();
        }
    });
})();
</script>
