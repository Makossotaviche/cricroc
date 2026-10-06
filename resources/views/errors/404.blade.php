{{--
    Vue : resources/views/errors/404.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>404 — Page introuvable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#F4F6FB; display:flex; align-items:center; justify-content:center; min-height:100vh; }
        .error-box { text-align:center; }
        .error-code { font-size:6rem; font-weight:900; color:#E8650A; line-height:1; }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="error-code">404</div>
        <h2 class="mt-2 fw-bold">Page introuvable</h2>
        <p class="text-muted">La page que vous cherchez n'existe pas ou a été déplacée.</p>
        <a href="{{ route('dashboard') }}" class="btn" style="background:#E8650A;color:#fff;">
            Retour au tableau de bord
        </a>
    </div>
</body>
</html>
