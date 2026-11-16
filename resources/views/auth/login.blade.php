<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#111214">
    <title>Connexion — CricCroc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --orange:#ff6900; --ink:#111214; --muted:#b7bbc4; }
        * { box-sizing:border-box; }
        body { min-height:100vh; margin:0; color:#fff; font-family:system-ui,-apple-system,"Segoe UI",sans-serif; background:radial-gradient(ellipse at 63% 40%,rgba(105,47,13,.36),transparent 42%),radial-gradient(ellipse at 12% 86%,rgba(91,39,8,.3),transparent 38%),linear-gradient(125deg,#080a0d 0%,#17191d 48%,#090a0c 100%); }
        body::before { content:""; position:fixed; inset:0; background:linear-gradient(90deg,rgba(3,5,7,.12),rgba(5,7,10,.06) 53%,rgba(3,5,7,.22)),linear-gradient(0deg,rgba(0,0,0,.3),transparent 45%); }
        .page { position:relative; z-index:1; min-height:100vh; padding:28px clamp(22px,4vw,64px) 18px; display:flex; flex-direction:column; }
        .topline { display:flex; justify-content:flex-end; align-items:center; gap:20px; color:#d0d3d9; font-size:.77rem; letter-spacing:.12em; }
        .topline i { width:36px; height:2px; background:var(--orange); }
        .content { flex:1; display:grid; grid-template-columns:minmax(0,1.15fr) minmax(390px,.85fr); align-items:center; gap:clamp(28px,5vw,80px); max-width:1450px; width:100%; margin:8px auto 0; }
        .hero { padding:16px 0 30px; }
        .hero-kicker { color:#ff842e; font-size:clamp(1rem,1.6vw,1.45rem); font-style:italic; font-weight:750; line-height:1.1; margin-bottom:12px; }
        .logo { font-size:clamp(4.2rem,8.4vw,8.4rem); line-height:.88; letter-spacing:-.085em; font-weight:950; text-shadow:0 4px 14px #000; }
        .logo .white { color:#fff; }.logo .orange { color:var(--orange); }
        .logo-sub { margin:10px 0 24px 7%; font-size:clamp(.9rem,1.5vw,1.35rem); font-style:italic; font-weight:700; }
        .hero h1 { max-width:560px; margin:0 0 24px 4%; font-size:clamp(1.35rem,2.2vw,2rem); line-height:1.15; font-weight:800; }
        .hero h1 span { color:var(--orange); }
        .features { display:flex; max-width:580px; margin-left:4%; background:rgba(7,8,10,.44); border-radius:50px; }
        .feature { flex:1; display:flex; align-items:center; gap:10px; padding:11px 15px; font-weight:650; font-size:clamp(.75rem,1vw,.95rem); line-height:1.15; }
        .feature + .feature { border-left:1px solid rgba(255,128,35,.68); }
        .feature i { color:#ff8a00; font-size:1.75rem; }
        .tagline { margin:32px 0 0 2%; color:#ff7b17; font-style:italic; font-weight:750; }
        .login-card { width:100%; max-width:590px; justify-self:end; padding:clamp(28px,3.3vw,50px); border:1px solid #f8790b; border-radius:38px; background:rgba(8,10,13,.84); box-shadow:0 20px 75px rgba(0,0,0,.56),inset 0 0 40px rgba(255,110,0,.09); backdrop-filter:blur(17px); }
        .brand { text-align:center; margin-bottom:20px; }
        .brand-name { color:#fff; font-size:clamp(2.15rem,3.1vw,3rem); line-height:.95; letter-spacing:-.07em; font-weight:950; }
        .brand-name span { color:var(--orange); }
        .brand-tagline { margin-top:7px; font-size:.82rem; font-style:italic; font-weight:700; }
        .brand-tagline::after { content:""; display:block; width:74px; height:3px; margin:5px auto 0; background:var(--orange); border-radius:4px; transform:rotate(-9deg); }
        .login-title { margin:0 0 8px; text-align:center; font-size:clamp(1.8rem,2.4vw,2.35rem); font-weight:800; }
        .login-subtitle { color:#d2d4db; text-align:center; line-height:1.45; margin:0 0 26px; }
        .login-field { display:flex; align-items:center; min-height:60px; margin-bottom:16px; padding:0 16px; border:1px solid #777b83; border-radius:18px; background:rgba(20,23,28,.72); transition:.2s; }
        .login-field:focus-within { border-color:#ff8500; box-shadow:0 0 0 2px rgba(255,122,0,.2),0 0 22px rgba(255,111,0,.2); }
        .login-field > i { color:#ff9700; font-size:1.4rem; margin-right:15px; }
        .login-field input { width:100%; color:white; border:0; outline:0; background:transparent; font-size:.98rem; }
        .login-field input::placeholder { color:#d0d1d5; }
        .password-toggle { border:0; color:#bec4ce; padding:4px; background:transparent; font-size:1.1rem; }
        .btn-login { display:flex; align-items:center; justify-content:center; gap:12px; min-height:60px; border:1px solid #ff9a00; border-radius:18px; color:#fff; background:linear-gradient(100deg,#ff7300,#ff4d00); box-shadow:0 0 20px rgba(255,105,0,.44); font-size:1.05rem; font-weight:800; }
        .btn-login:hover { color:white; background:linear-gradient(100deg,#ff8500,#ff5b00); box-shadow:0 0 28px rgba(255,105,0,.6); }
        .login-options { display:flex; align-items:center; justify-content:space-between; gap:10px; margin:18px 2px 20px; font-size:.82rem; }
        .form-check-input { background-color:#202329; border-color:#858994; }
        .form-check-input:checked { background-color:var(--orange); border-color:var(--orange); }
        .form-check-input:focus { border-color:var(--orange); box-shadow:0 0 0 .2rem rgba(255,106,0,.2); }
        .form-check-label { color:#f1f1f2; }
        .help-text { color:#ff8700; text-decoration:none; font-weight:700; }.help-text:hover { color:#ffad4a; text-decoration:underline; }
        .or-divider { display:flex; align-items:center; gap:16px; color:#bfc2c9; font-size:.85rem; margin:0 0 20px; }.or-divider::before,.or-divider::after { content:""; height:1px; flex:1; background:#858890; }
        .google-button { width:100%; min-height:50px; display:flex; align-items:center; justify-content:center; gap:12px; border:1px solid #777b83; border-radius:28px; color:#fff; background:rgba(20,23,28,.62); font-size:.85rem; font-weight:650; }
        .google-button .google-g { font:900 1.3rem Arial,sans-serif; color:#4285f4; text-shadow:1px 0 #34a853,-1px 0 #fbbc05; }
        .secure-note { display:flex; justify-content:center; align-items:center; gap:9px; color:#c5c8ce; font-size:.82rem; margin-top:19px; }.secure-note i { color:#ff8900; font-size:1.15rem; }
        .footer { display:flex; align-items:center; justify-content:center; gap:14px; color:#e1e1e3; font-size:.78rem; }.footer i { width:30px; height:1px; background:#ff8700; }.footer b { color:#ff8700; }
        @media(max-width:900px) { .page { padding:20px 16px 14px; }.content { display:flex; justify-content:center; margin:14px auto; }.hero { display:none; }.login-card { max-width:520px; justify-self:auto; padding:30px clamp(22px,7vw,42px); border-radius:28px; }.topline { font-size:.68rem; gap:12px; }.footer { font-size:.72rem; } }
        @media(max-height:720px) and (min-width:901px) { .page { padding-top:15px; }.login-card { padding-top:22px; padding-bottom:22px; }.brand { margin-bottom:12px; }.login-subtitle { margin-bottom:16px; }.login-field { min-height:52px; margin-bottom:12px; }.btn-login { min-height:52px; }.login-options { margin:13px 2px; }.google-button { min-height:44px; }.secure-note { margin-top:12px; } }
        @media(prefers-reduced-motion:reduce) { *,*::before,*::after { scroll-behavior:auto!important; transition:none!important; } }
    </style>
</head>
<body>
    <div class="page">
        <nav class="topline" aria-label="Valeurs CricCroc"><span>QUALITÉ</span><b>·</b><span>SÉCURITÉ</span><b>·</b><span>CROISSANCE</span><i></i></nav>
        <div class="content">
            <section class="hero" aria-label="CricCroc">
                <div class="hero-kicker">Le goût<br>du croustillant</div>
                <div class="logo"><span class="white">Cric</span><span class="orange">Croc</span></div>
                <div class="logo-sub">Chips croustillants · 100% plaisir</div>
                <h1>Une entreprise congolaise,<br>au service de <span>votre plaisir !</span></h1>
                <div class="features">
                    <div class="feature"><i class="bi bi-patch-check"></i><span>Produits<br>de qualité</span></div>
                    <div class="feature"><i class="bi bi-shield-check"></i><span>Stock<br>sécurisé</span></div>
                    <div class="feature"><i class="bi bi-truck"></i><span>Livraison<br>rapide</span></div>
                </div>
                <div class="tagline">CricCroc,<br>toujours plus proche de vous !</div>
            </section>
            <main class="login-card" aria-labelledby="loginTitle">
                <div class="brand" aria-label="CricCroc, le goût du croustillant">
                    <div class="brand-name">Cric<span>Croc</span></div>
                    <div class="brand-tagline">Le goût du croustillant</div>
                </div>
                <h1 class="login-title" id="loginTitle">Connexion</h1>
                <p class="login-subtitle">Accédez à votre espace pour gérer vos activités<br class="d-none d-sm-inline"> en toute simplicité.</p>
                @if($errors->any())
                    <div class="alert alert-danger py-2" role="alert"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <label class="visually-hidden" for="email">Adresse e-mail</label>
                    <div class="login-field"><i class="bi bi-person" aria-hidden="true"></i><input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Nom d’utilisateur" autocomplete="username" required autofocus></div>
                    <label class="visually-hidden" for="password">Mot de passe</label>
                    <div class="login-field"><i class="bi bi-lock" aria-hidden="true"></i><input id="password" type="password" name="password" placeholder="Mot de passe" autocomplete="current-password" required><button class="password-toggle" type="button" id="togglePassword" aria-label="Afficher le mot de passe"><i class="bi bi-eye-slash" aria-hidden="true"></i></button></div>
                    <button type="submit" class="btn btn-login w-100"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>Se connecter <i class="bi bi-chevron-right ms-auto me-2" aria-hidden="true"></i></button>
                    <div class="login-options"><div class="form-check mb-0"><input type="checkbox" class="form-check-input" name="remember" id="remember" checked><label class="form-check-label" for="remember">Se souvenir de moi</label></div><span class="help-text">Mot de passe oublié ?</span></div>
                </form>
                <div class="or-divider"><span>OU</span></div>
                <button type="button" class="google-button" aria-label="Connexion Google non configurée"><span class="google-g">G</span>Se connecter avec Google</button>
                <div class="secure-note"><i class="bi bi-shield-lock"></i><span>Système sécurisé — CricCroc</span></div>
            </main>
        </div>
        <footer class="footer"><i></i><span>CricCroc</span><b>•</b><span>Le goût du croustillant</span><i></i></footer>
    </div>
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
