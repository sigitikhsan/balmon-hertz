@extends('layouts.app')

@section('content')
<style>
    .login-page {
        min-height: 100vh;
        overflow-x: hidden;
        background: radial-gradient(ellipse at 14% 36%, rgba(255,255,255,.95), transparent 34%),
                    linear-gradient(135deg, #eaf7ff 0%, #d9efff 55%, #eef9ff 100%);
        font-family: Inter, "Plus Jakarta Sans", "Segoe UI", Arial, sans-serif;
        color: #0a3a70;
    }
    .login-shell { max-width: 1600px; margin: 0 auto; padding: 18px 26px 24px; }
    .login-header {
        display: flex; align-items: center; gap: 24px; min-height: 70px;
        padding: 4px 12px 16px; border-bottom: 1px solid rgba(186,230,253,.5); flex-wrap: wrap;
    }
    .login-brand { display: block; width: 132px; flex-shrink: 0; }
    .login-brand img { display: block; width: 100%; height: auto; }
    .login-system { border-left: 1px solid #93c5fd; padding-left: 20px; font-size: 14px; line-height: 1.4; color: #103f73; }
    .login-system strong { font-weight: 600; }
    .login-slogan { margin-left: auto; text-align: right; font-size: 10px; font-weight: 600; font-style: italic; line-height: 1.6; color: #174d83; }
    .login-main {
        display: grid; align-items: center; gap: 24px; padding: 32px 0;
        min-height: calc(100vh - 88px);
        grid-template-columns: minmax(0, 1fr) minmax(360px, .86fr);
    }
    .welcome-panel {
        position: relative; isolation: isolate; display: flex; align-items: center;
        min-height: 430px; overflow: hidden; border-radius: 14px; padding: 32px;
    }
    .welcome-panel::before, .welcome-panel::after {
        content: ''; position: absolute; z-index: -1; width: 480px; height: 224px;
        border-radius: 50%; background: rgba(125,211,252,.15); transform: rotate(-12deg);
    }
    .welcome-panel::before { bottom: -112px; left: -96px; }
    .welcome-panel::after { bottom: -144px; right: -160px; }
    .welcome-copy { position: relative; z-index: 2; width: 55%; max-width: 390px; }
    .welcome-kicker { margin: 0 0 12px; font-size: 10px; font-weight: 800; letter-spacing: 1.7px; color: #0369a1; }
    .welcome-title { margin: 0 0 12px; font-size: clamp(25px, 2.35vw, 36px); font-weight: 700; line-height: 1.2; letter-spacing: -0.5px; color: #073f7b; }
    .welcome-desc { margin: 0; max-width: 350px; font-size: 14px; line-height: 1.65; color: #527aa5; }
    .building-art {
        position: absolute; right: -2%; bottom: 32px; z-index: 0; width: 54%; max-width: 410px;
        height: auto; object-fit: contain; pointer-events: none; user-select: none;
    }
    .login-card {
        position: relative; z-index: 10; width: 100%; max-width: 500px; justify-self: center;
        border-radius: 14px; border: 1px solid rgba(190,221,245,.9); background: rgba(255,255,255,.94);
        padding: 28px 30px; box-shadow: 0 20px 60px rgba(10,59,120,.08);
    }
    .login-card-logo { display: flex; justify-content: center; margin-bottom: 20px; }
    .login-card-logo img { width: 130px; height: auto; display: block; }
    .login-card h2 { margin: 0 0 4px; font-size: 25px; font-weight: 700; letter-spacing: -0.4px; color: #083f78; }
    .login-card .login-sub { margin: 0 0 24px; font-size: 12px; color: #6a8db5; }
    .field-label { display: block; margin: 16px 0 6px; font-size: 12px; font-weight: 700; color: #164777; }
    .field-wrap {
        display: flex; align-items: stretch; min-height: 44px; overflow: hidden;
        border-radius: 8px; border: 1px solid #cfe1f2; background: #fbfdff; transition: all .2s;
    }
    .field-wrap:focus-within { border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(224,242,254,.8); }
    .field-icon {
        width: 42px; flex-shrink: 0; display: grid; place-items: center;
        border-right: 1px solid #e0edf8; background: #f0f8ff; color: #4c83b7;
    }
    .field-input {
        flex: 1; min-width: 0; border: 0; background: transparent; padding: 10px 12px;
        font-size: 12px; color: #173f6b; outline: none;
    }
    .field-input::placeholder { color: #88a4c4; }
    .field-toggle {
        width: 40px; flex-shrink: 0; display: grid; place-items: center; border: 0;
        background: transparent; color: #4b80b4; cursor: pointer; transition: color .2s;
    }
    .field-toggle:hover { color: #073f78; }
    .field-error { margin: 8px 0 0; font-size: 12px; color: #dc2626; }
    .login-submit {
        margin-top: 24px; width: 100%; display: flex; align-items: center; justify-content: center; gap: 12px;
        border: 0; border-radius: 9px; padding: 12px 16px; font-size: 14px; font-weight: 700; color: #fff;
        background: linear-gradient(to right, #06457f, #07518e); box-shadow: 0 2px 8px rgba(6,69,127,.2);
        cursor: pointer; transition: all .2s;
    }
    .login-submit:hover { background: linear-gradient(to right, #063966, #06457f); }
    .login-submit:focus-visible { outline: 2px solid #07518e; outline-offset: 2px; }
    @media (max-width: 900px) {
        .login-main { grid-template-columns: 1fr; min-height: auto; padding: 24px 0; }
        .welcome-panel { min-height: 340px; }
        .login-card { max-width: 100%; }
    }
    @media (max-width: 640px) {
        .login-shell { padding: 12px 12px 20px; }
        .login-header { gap: 12px 24px; padding: 4px 4px 16px; }
        .login-brand { width: 105px; }
        .login-system { padding-left: 16px; font-size: 12px; }
        .login-slogan { margin-left: 0; width: 100%; text-align: left; }
        .welcome-panel { padding: 32px 20px; min-height: 340px; }
        .welcome-copy { width: 58%; padding-bottom: 80px; }
        .welcome-title { font-size: 23px; }
        .building-art { width: 58%; right: -6%; bottom: 20px; }
        .login-card { padding: 24px 20px; }
        .login-card-logo img { width: 120px; }
    }
</style>

<div class="login-page">
    <div class="login-shell">
        <header class="login-header">
            <a href="{{ url('/') }}" class="login-brand" aria-label="Magang Hertz">
                <img src="{{ asset('images/login/magang-hertz-logo.png') }}" alt="Magang Hertz">
            </a>
            <div class="login-system">
                Sistem Pendaftaran Magang / PKL<br><strong>Hertz</strong>
            </div>
            <p class="login-slogan">
                “Bersama Membangun Kompetensi,<br>untuk Masa Depan yang Lebih Baik”
            </p>
        </header>

        <main class="login-main">
            <section class="welcome-panel" aria-label="Selamat datang">
                <div class="welcome-copy">
                    <p class="welcome-kicker">PORTAL PENDAFTARAN</p>
                    <h1 class="welcome-title">Selamat Datang di<br>Program Magang &amp; PKL Hertz</h1>
                    <p class="welcome-desc">Masuk untuk mengelola pendaftaran, verifikasi, dan proses program magang/PKL Hertz.</p>
                </div>
                <img src="{{ asset('images/login/building-illustration.png') }}" alt="Ilustrasi gedung dan menara komunikasi" class="building-art">
            </section>

            <section class="login-card" aria-label="Form login">
                <div class="login-card-logo">
                    <img src="{{ asset('images/login/magang-hertz-logo.png') }}" alt="Magang Hertz">
                </div>

                <h2>Login</h2>
                <p class="login-sub">Masuk dengan email dan password Anda</p>

                <form action="{{ route('internal.login.post') }}" method="POST">
                    @csrf

                    <label for="email" class="field-label">Email</label>
                    <div class="field-wrap">
                        <span class="field-icon" aria-hidden="true">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="Masukkan email Anda" class="field-input">
                    </div>
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror

                    <label for="password" class="field-label">Password</label>
                    <div class="field-wrap">
                        <span class="field-icon" aria-hidden="true">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/><path d="M12 14v3"/></svg>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Masukkan password Anda" class="field-input">
                        <button type="button" class="field-toggle" id="toggle-password" aria-label="Tampilkan password">
                            <svg id="eye-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            <svg id="eye-off-icon" style="display:none" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m3 3 18 18"/><path d="M10.6 6.2A10.5 10.5 0 0 1 12 6c6.1 0 9.5 6 9.5 6a16.3 16.3 0 0 1-3.1 3.7M6.2 6.7C3.8 8.2 2.5 12 2.5 12s3.4 6 9.5 6c1 0 1.9-.2 2.7-.4"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="login-submit">
                        Login
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                    </button>
                </form>
            </section>
        </main>
    </div>
</div>

<script>
(function () {
    var btn = document.getElementById('toggle-password');
    var input = document.getElementById('password');
    var eye = document.getElementById('eye-icon');
    var eyeOff = document.getElementById('eye-off-icon');
    if (!btn || !input) return;
    btn.addEventListener('click', function () {
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        eye.style.display = show ? 'none' : '';
        eyeOff.style.display = show ? '' : 'none';
        btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
    });
})();
</script>
@endsection
