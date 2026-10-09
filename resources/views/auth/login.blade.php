<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f6f7f9">
    <title>Connexion — Cric Croc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --cric-orange: #f36a08; --cric-ink: #171923; --cric-muted: #64748b; }
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            background: #f8f9fb url("{{ asset('images/cricroc-login.png') }}") center center / cover no-repeat fixed;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            color: var(--cric-ink);
        }
        .visual-effects { position: fixed; inset: 0; overflow: hidden; pointer-events: none; z-index: 1; }
        .hero-image-layer {
            position: absolute; inset: 0;
            background: url("{{ asset('images/cricroc-login.png') }}") center center / cover no-repeat;
            clip-path: polygon(0 0, 54.7% 0, 54.7% 100%, 0 100%);
            transform-origin: 18% 52%;
            animation: kenBurns 30s ease-in-out infinite alternate;
        }
        .hero-image-layer::after {
            content: ''; position: absolute; inset: 0 45% 0 0;
            background: linear-gradient(110deg, rgba(8, 13, 24, .12), transparent 72%);
        }
        .image-sweep {
            position: absolute; top: -24%; left: -28%; width: 17vw; height: 148%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.11), transparent);
            transform: skewX(-22deg); opacity: 0;
            animation: imageSweep 18s ease-in-out infinite;
        }
        .floating-chip {
            position: absolute; z-index: 2; display: block;
            background:
                radial-gradient(ellipse at 30% 24%, rgba(255,248,204,.92) 0 7%, transparent 27%),
                radial-gradient(ellipse at 69% 65%, rgba(173,76,10,.34), transparent 44%),
                linear-gradient(145deg, #ffe58a 0%, #f5a92e 48%, #d97315 100%);
            clip-path: polygon(7% 33%, 19% 10%, 46% 5%, 71% 12%, 94% 36%, 86% 69%, 65% 91%, 37% 96%, 13% 78%, 3% 56%);
            filter: drop-shadow(0 5px 5px rgba(30, 12, 2, .36));
            transform-origin: 50% 55%;
        }
        .floating-chip--one { left: 33%; top: 59%; width: 56px; height: 35px; animation: chipFloatOne 7s ease-in-out infinite; }
        .floating-chip--two { left: 43%; top: 56%; width: 43px; height: 27px; animation: chipFloatTwo 8.5s ease-in-out infinite; }
        .floating-chip--three { left: 47%; top: 64%; width: 33px; height: 22px; animation: chipFloatThree 6.8s ease-in-out infinite; }
        .feature-strip {
            position: absolute; z-index: 2; left: 8.5%; top: 49%; width: 44%;
            display: flex; align-items: stretch; overflow: hidden;
            border: 1px solid rgba(255,255,255,.16); border-radius: 14px;
            background: rgba(13, 17, 23, .91);
            box-shadow: 0 12px 32px rgba(0,0,0,.25);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        }
        .feature-item {
            flex: 1; min-width: 0; display: flex; align-items: center; justify-content: center;
            gap: 10px; padding: 13px 10px; color: #fff;
            font-size: clamp(11px, .88vw, 14px); font-weight: 650; line-height: 1.2;
            opacity: 0; animation: featureReveal .8s cubic-bezier(.2,.7,.25,1) forwards;
        }
        .feature-item + .feature-item { border-left: 1px solid rgba(255,255,255,.2); }
        .feature-item:nth-child(1) { animation-delay: .25s; }
        .feature-item:nth-child(2) { animation-delay: .45s; }
        .feature-item:nth-child(3) { animation-delay: .65s; }
        .feature-icon { flex: 0 0 auto; color: #ff861f; font-size: clamp(20px, 2vw, 29px); }
        @keyframes kenBurns { from { transform: scale(1); } to { transform: scale(1.045); } }
        @keyframes chipFloatOne { 0%,100% { transform: translate(0,0) rotate(-7deg); } 50% { transform: translate(3px,-9px) rotate(-2deg); } }
        @keyframes chipFloatTwo { 0%,100% { transform: translate(0,0) rotate(8deg); } 50% { transform: translate(-3px,-7px) rotate(2deg); } }
        @keyframes chipFloatThree { 0%,100% { transform: translate(0,0) rotate(-3deg); } 50% { transform: translate(2px,-6px) rotate(4deg); } }
        @keyframes featureReveal { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes imageSweep {
            0%, 12% { left: -28%; opacity: 0; }
            18% { opacity: .55; }
            35% { left: 54%; opacity: .08; }
            40%, 100% { left: 62%; opacity: 0; }
        }
        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-card {
            position: fixed;
            top: 11.8%;
            left: 54.6%;
            width: 40.1%;
            height: 76.8%;
            padding: clamp(24px, 3vw, 48px);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255,255,255,.985);
            border: 1px solid rgba(255,255,255,.8);
            border-radius: 26px;
            box-shadow: 0 18px 55px rgba(16, 24, 40, .12);
            z-index: 3;
            animation: cardEntrance .8s cubic-bezier(.2,.7,.25,1) both;
        }
        .brand { text-align: center; margin-bottom: 20px; }
        .brand-name { font-size: clamp(2rem, 3.1vw, 3rem); line-height: 1; font-weight: 900; letter-spacing: -2px; }
        .brand-name span { color: var(--cric-orange); }
        .brand-tagline { color: #292c35; font-size: .85rem; font-weight: 700; font-style: italic; margin-top: 7px; }
        .brand-tagline::after { content: ''; display: block; width: 76px; height: 3px; border-radius: 3px; background: var(--cric-orange); margin: 5px auto 0; }
        .login-title { font-size: clamp(1.7rem, 2.3vw, 2.3rem); font-weight: 750; text-align: center; margin: 4px 0 8px; }
        .login-subtitle { color: var(--cric-muted); text-align: center; line-height: 1.45; margin: 0 0 26px; }
        .login-field { display: flex; align-items: center; border: 1px solid #d5dbe4; border-radius: 14px; min-height: 60px; padding: 0 16px; margin-bottom: 16px; background: #fff; transition: border-color .2s, box-shadow .2s; }
        .login-field:focus-within { border-color: var(--cric-orange); box-shadow: 0 0 0 3px rgba(243,106,8,.12); }
        .login-field > i { color: var(--cric-orange); font-size: 1.35rem; margin-right: 14px; }
        .login-field input { width: 100%; border: 0; outline: 0; background: transparent; color: #263247; font-size: .98rem; }
        .login-field input::placeholder { color: #718096; }
        .password-toggle { border: 0; background: transparent; color: var(--cric-muted); padding: 4px; font-size: 1.1rem; }
        .btn-login { background: linear-gradient(100deg, #ff7108, #ff5d00); color: #fff; border: 0; border-radius: 13px; min-height: 60px; font-size: 1.05rem; font-weight: 750; box-shadow: 0 7px 16px rgba(243,106,8,.18); transition: transform .2s, box-shadow .2s; }
        .btn-login:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 10px 22px rgba(243,106,8,.25); }
        .login-options { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin: 18px 0 24px; font-size: .83rem; }
        .form-check-input:checked { background-color: var(--cric-orange); border-color: var(--cric-orange); }
        .form-check-input:focus { border-color: var(--cric-orange); box-shadow: 0 0 0 .2rem rgba(243,106,8,.18); }
        .help-text { color: var(--cric-orange); font-weight: 600; }
        .secure-note { display: flex; justify-content: center; align-items: center; gap: 8px; color: var(--cric-muted); font-size: .83rem; margin-top: auto; padding-top: 15px; }
        .secure-note i { font-size: 1.1rem; }
        @media (min-aspect-ratio: 1.65) and (min-width: 900px) {
            .login-card { top: 4.5%; height: 91%; padding-top: 22px; padding-bottom: 22px; }
            .brand { margin-bottom: 12px; }
            .login-subtitle { margin-bottom: 18px; }
            .login-field { min-height: 54px; margin-bottom: 13px; }
            .btn-login { min-height: 54px; }
            .login-options { margin: 14px 0 16px; }
        }
        @media (max-width: 899px) {
            body { min-height: 100svh; padding: 22px 14px; background-position: 28% center; background-attachment: scroll; display: flex; align-items: center; justify-content: center; }
            .login-card { position: relative; inset: auto; transform: none; width: min(100%, 500px); height: auto; min-height: min(720px, calc(100svh - 44px)); padding: 30px clamp(22px, 7vw, 42px); border-radius: 22px; }
            .brand-name { font-size: 2.45rem; }
            .login-title { font-size: 1.9rem; }
            .hero-image-layer, .feature-strip, .floating-chip, .image-sweep { display: none; }
        }
        @media (max-height: 640px) and (min-width: 900px) {
            .login-card { justify-content: flex-start; }
            .secure-note { margin-top: 12px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; }
        }
    </style>
</head>
<body>
    <div class="visual-effects" aria-hidden="true">
        <div class="hero-image-layer"></div>
        <div class="image-sweep"></div>
        <span class="floating-chip floating-chip--one"></span>
        <span class="floating-chip floating-chip--two"></span>
        <span class="floating-chip floating-chip--three"></span>
        <div class="feature-strip">
            <div class="feature-item"><i class="feature-icon bi bi-patch-check"></i><span>Produits<br>de qualité</span></div>
            <div class="feature-item"><i class="feature-icon bi bi-shield-check"></i><span>Stock<br>sécurisé</span></div>
            <div class="feature-item"><i class="feature-icon bi bi-truck"></i><span>Livraison<br>rapide</span></div>
        </div>
    </div>
    <main class="login-card" aria-labelledby="loginTitle">
        <div class="brand" aria-label="Cric Croc, le goût du croustillant">
            <div class="brand-name">Cric<span>Croc</span></div>
            <div class="brand-tagline">Le goût du croustillant</div>
        </div>

        <h1 class="login-title" id="loginTitle">Connexion</h1>
        <p class="login-subtitle">Accédez à votre espace pour gérer vos activités<br class="d-none d-sm-inline"> en toute simplicité.</p>

        @if($errors->any())
            <div class="alert alert-danger py-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label class="visually-hidden" for="email">Adresse e-mail</label>
            <div class="login-field">
                <i class="bi bi-person" aria-hidden="true"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Adresse e-mail" autocomplete="username" required autofocus>
            </div>

            <label class="visually-hidden" for="password">Mot de passe</label>
            <div class="login-field">
                <i class="bi bi-lock" aria-hidden="true"></i>
                <input id="password" type="password" name="password" placeholder="Mot de passe" autocomplete="current-password" required>
                <button class="password-toggle" type="button" id="togglePassword" aria-label="Afficher le mot de passe">
                    <i class="bi bi-eye-slash" aria-hidden="true"></i>
                </button>
            </div>

            <div class="login-options">
                <div class="form-check mb-0">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label text-secondary" for="remember">Se souvenir de moi</label>
                </div>
                <span class="help-text">Système sécurisé</span>
            </div>

            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
            </button>
        </form>

        <div class="secure-note"><i class="bi bi-shield-check"></i><span>Système sécurisé — CricCroc</span></div>
    </main>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');
            const afficher = input.type === 'password';
            input.type = afficher ? 'text' : 'password';
            icon.className = afficher ? 'bi bi-eye' : 'bi bi-eye-slash';
            this.setAttribute('aria-label', afficher ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
        });
    </script>
</body>
</html>
