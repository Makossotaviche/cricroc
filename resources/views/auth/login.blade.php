{{--
    Vue : resources/views/auth/login.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Cric Croc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1A1A2E 0%, #16213E 60%, #0F3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0,0,0,.4);
        }
        .logo-badge {
            background: #E8650A;
            color: #fff;
            font-weight: 800;
            font-size: 2rem;
            padding: .4rem 1.2rem;
            border-radius: 12px;
            letter-spacing: 2px;
        }
        .btn-login {
            background: #E8650A;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .75rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all .2s;
        }
        .btn-login:hover { background: #F7923A; color: #fff; transform: translateY(-1px); }
        .form-control { border-radius: 10px; padding: .65rem 1rem; }
        .form-control:focus { border-color: #E8650A; box-shadow: 0 0 0 .2rem rgba(232,101,10,.2); }
        .hint-box {
            background: #F8F9FA;
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <span class="logo-badge">CRIC CROC</span>
            <p class="text-muted mt-2 mb-0" style="font-size:.85rem;">Système de Gestion Commerciale</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Adresse email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input type="email" name="email" class="form-control border-start-0"
                           value="{{ old('email') }}" placeholder="votre@email.com" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" name="password" class="form-control border-start-0"
                           placeholder="••••••••" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label text-muted" for="remember">Se souvenir de moi</label>
            </div>
            <button type="submit" class="btn btn-login w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
            </button>
        </form>

        <div class="hint-box mt-3">
            <strong>Comptes de démonstration :</strong><br>
            makossotaviche@gmail.com · commercial@cricroc.cg<br>
            stock@cricroc.cg · production@cricroc.cg · livreur@cricroc.cg<br>
            <em>Mot de passe : password</em>
        </div>
    </div>
</body>
</html>
