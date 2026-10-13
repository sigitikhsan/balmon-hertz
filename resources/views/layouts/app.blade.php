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
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        html { scroll-behavior: smooth; scroll-padding-top: 5rem; }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-rise { opacity: 0; animation: rise 0.7s ease-out forwards; }
        .anim-d1 { animation-delay: 0.05s; }
        .anim-d2 { animation-delay: 0.15s; }
        .anim-d3 { animation-delay: 0.3s; }
        .anim-d4 { animation-delay: 0.45s; }
        @media (prefers-reduced-motion: reduce) {
            .anim-rise { opacity: 1; animation: none; }
        }
    </style>
</head>
<body class="antialiased font-sans">
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
