<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>magang-hertz - Portal Magang/PKL Balmon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --hz-navy: #08245C;
            --hz-blue: #1265ED;
            --hz-soft: #EAF3FF;
            --hz-light: #DCEBFF;
            --hz-text: #52637F;
        }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: #08245C; background: #fff; }
        html { scroll-behavior: smooth; scroll-padding-top: 7rem; }
        @media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } }
        @keyframes rise { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .anim-rise { opacity: 0; animation: rise .7s ease-out forwards; }
        .anim-d2 { animation-delay: .15s; } .anim-d3 { animation-delay: .3s; } .anim-d4 { animation-delay: .45s; }
        @media (prefers-reduced-motion: reduce) { .anim-rise { opacity: 1; animation: none; transform: none; } }
        .hz-shadow { box-shadow: 0 10px 35px rgba(8,36,92,.06); }
        .hz-card { background:#fff; border:1px solid #DCEBFF; border-radius:16px; box-shadow:0 8px 30px rgba(8,36,92,.04); }
        .hz-btn-primary { display:inline-flex; align-items:center; justify-content:center; gap:.5rem; min-height:48px; padding:.8rem 1.6rem; border-radius:12px; background:var(--hz-blue); color:#fff; font-weight:600; font-size:.9375rem; transition:all .25s ease; box-shadow:0 10px 24px rgba(18,101,237,.25); }
        .hz-btn-primary:hover { background:#0f56cc; transform:translateY(-1px); box-shadow:0 14px 28px rgba(18,101,237,.32); }
        .hz-btn-secondary { display:inline-flex; align-items:center; justify-content:center; gap:.5rem; min-height:48px; padding:.8rem 1.6rem; border-radius:12px; background:#fff; color:var(--hz-navy); font-weight:600; font-size:.9375rem; border:1px solid #DCEBFF; transition:all .25s ease; }
        .hz-btn-secondary:hover { background:#F7FAFF; border-color:#bfd6ff; transform:translateY(-1px); }
        @media (prefers-reduced-motion: reduce) { .hz-btn-primary,.hz-btn-secondary { transition:none; } .hz-btn-primary:hover,.hz-btn-secondary:hover { transform:none; } }
    </style>
</head>
<body class="antialiased font-sans overflow-x-hidden">
    @yield('content')
</body>
<script>
(function () {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (!('IntersectionObserver' in window) || !('animate' in Element.prototype)) return;
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var el = entry.target;
            io.unobserve(el);
            var delay = 0;
            var group = el.closest('[data-stagger]');
            if (group) {
                delay = Array.prototype.indexOf.call(group.querySelectorAll('[data-reveal]'), el) * 90;
            }
            el.animate(
                [{ opacity: 0, transform: 'translateY(20px)' }, { opacity: 1, transform: 'translateY(0)' }],
                { duration: 650, easing: 'cubic-bezier(.22,.61,.36,1)', fill: 'backwards', delay: delay }
            );
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('[data-reveal]').forEach(function (el) { io.observe(el); });
})();
</script>
</html>
