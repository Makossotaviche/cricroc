{{--
    Vue : resources/views/layouts/app.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cric Croc') — Gestion</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --cc-orange: #E8650A;
            --cc-orange-light: #F7923A;
            --cc-dark: #1A1A2E;
            --cc-sidebar-width: 260px;
        }

        body { background: #F4F6FB; font-family: 'Segoe UI', sans-serif; }

        /* ── Sidebar ── */
        #sidebar {
            width: var(--cc-sidebar-width);
            height: 100vh;
            background: var(--cc-dark);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform .25s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            overscroll-behavior: contain;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
            flex-shrink: 0;
        }

        .sidebar-brand .logo-badge {
            background: var(--cc-orange);
            color: #fff;
            font-weight: 800;
            font-size: 1.4rem;
            padding: .3rem .8rem;
            border-radius: 8px;
            letter-spacing: 1px;
        }

        .sidebar-brand small {
            display: block;
            color: rgba(255,255,255,.5);
            font-size: .7rem;
            margin-top: .35rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-section {
            padding: .5rem 1rem .25rem;
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,.3);
            font-weight: 600;
        }

        .sidebar-nav {
            flex: 1 0 auto;
            overflow: visible;
            padding-bottom: .75rem;
        }

        .sidebar-nav .nav-link {
            color: rgba(255,255,255,.7);
            padding: .55rem 1.25rem;
            border-radius: 8px;
            margin: .1rem .75rem;
            display: flex;
            align-items: center;
            gap: .65rem;
            font-size: .9rem;
            transition: all .15s;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: var(--cc-orange);
            color: #fff;
        }

        .sidebar-nav .nav-link .bi { font-size: 1rem; }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.1);
            background: var(--cc-dark);
            flex-shrink: 0;
        }

        /* ── Main content ── */
        #main-content {
            margin-left: var(--cc-sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: .75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar .page-title {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--cc-dark);
            margin: 0;
        }

        /* ── Cards ── */
        .stat-card {
            border: none;
            border-radius: 14px;
            padding: 1.25rem;
            background: #fff;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }

        .stat-card .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--cc-dark);
        }

        .stat-card .stat-label {
            font-size: .8rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* ── Tables ── */
        .table-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            overflow: hidden;
        }

        .table-card .table { margin: 0; }
        .table-card .table thead th {
            background: #F8F9FA;
            border-bottom: 2px solid #e9ecef;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6c757d;
            font-weight: 600;
            padding: .9rem 1rem;
        }

        .table-card .table tbody td { padding: .8rem 1rem; vertical-align: middle; }
        .table-card .table tbody tr:hover { background: #F8F9FA; }

        /* ── Badges statut ── */
        .badge-en_attente  { background: #FFF3CD; color: #856404; }
        .badge-validee     { background: #D1FAE5; color: #065F46; }
        .badge-rejetee     { background: #FEE2E2; color: #991B1B; }
        .badge-en_cours    { background: #DBEAFE; color: #1E40AF; }
        .badge-livree      { background: #D1FAE5; color: #065F46; }

        /* ── Btn orange ── */
        .btn-orange {
            background: var(--cc-orange);
            color: #fff;
            border: none;
        }
        .btn-orange:hover { background: var(--cc-orange-light); color: #fff; }

        /* ── Alert rupture ── */
        .rupture-badge { animation: pulse 1.5s infinite; }
        @keyframes pulse {
            0%,100% { opacity: 1; }
            50%      { opacity: .5; }
        }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- ═══════════════════════ SIDEBAR ═══════════════════════ -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <span class="logo-badge">CRIC CROC</span>
        <small>Gestion Commerciale</small>
    </div>

    <ul class="nav flex-column sidebar-nav mt-2 flex-grow-1">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Tableau de bord
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('notifications.index') }}"
               class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Notifications
                <span id="sidebarNotifBadge" class="badge bg-danger ms-auto" style="display:none;"></span>
            </a>
        </li>

        @if(auth()->user()->isAgentCommercial())
        <li><span class="nav-section">Commercial</span></li>
        <li class="nav-item">
            <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Clients
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('commandes.index') }}" class="nav-link {{ request()->routeIs('commandes.*') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i> Commandes
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('factures.index') }}" class="nav-link {{ request()->routeIs('factures.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Factures
            </a>
        </li>
        @endif

        @if(auth()->user()->isGestionnaireStock())
        <li><span class="nav-section">Stock</span></li>
        <li class="nav-item">
            <a href="{{ route('stock.index') }}" class="nav-link {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                <i class="bi bi-boxes"></i> Stock
                @php $ruptures = \App\Models\Stock::whereColumn('quantite_disponible','<=','seuil_alerte')->count() @endphp
                @if($ruptures > 0)
                    <span class="badge bg-danger ms-auto rupture-badge">{{ $ruptures }}</span>
                @endif
            </a>
        </li>
        @endif

        @if(auth()->user()->isResponsableProduction())
        <li><span class="nav-section">Production</span></li>
        <li class="nav-item">
            <a href="{{ route('production.index') }}" class="nav-link {{ request()->routeIs('production.*') ? 'active' : '' }}">
                <i class="bi bi-gear-wide-connected"></i> Productions
            </a>
        </li>
        @endif

        @if(auth()->user()->isLivreur())
        <li><span class="nav-section">Livraison</span></li>
        <li class="nav-item">
            <a href="{{ route('livraisons.index') }}" class="nav-link {{ request()->routeIs('livraisons.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> Livraisons
            </a>
        </li>
        @endif

        @if(auth()->user()->isAdmin())
        <li><span class="nav-section">Administration</span></li>
        <li class="nav-item">
            <a href="{{ route('produits.index') }}" class="nav-link {{ request()->routeIs('produits.*') ? 'active' : '' }}">
                <i class="bi bi-bag"></i> Produits
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i> Utilisateurs
            </a>
        </li>
        @endif
    </ul>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                 style="width:36px;height:36px;font-weight:700;font-size:.9rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="text-white fw-semibold" style="font-size:.82rem;">{{ auth()->user()->name }}</div>
                <div style="font-size:.7rem;color:rgba(255,255,255,.4);">{{ auth()->user()->role_libelle }}</div>
            </div>
        </div>

    </div>
</nav>
<!-- ═══════════════════════ END SIDEBAR ═══════════════════════ -->

<div id="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            {{-- Cloche notifications --}}
            <div class="position-relative me-1">
                <a href="{{ route('notifications.index') }}"
                   class="btn btn-sm btn-light position-relative" id="notifBtn" title="Notifications">
                    <i class="bi bi-bell fs-5"></i>
                    <span id="notifBadge"
                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                          style="font-size:.6rem;display:none;">0</span>
                </a>
            </div>
            <div class="d-none d-sm-flex align-items-center gap-2 me-1">
                <span class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold"
                      style="width:34px;height:34px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <span class="text-end" style="line-height:1.15;">
                    <span class="d-block fw-semibold" style="font-size:.82rem;">{{ auth()->user()->name }}</span>
                    <span class="d-block text-muted" style="font-size:.7rem;">{{ auth()->user()->role_libelle }}</span>
                </span>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Déconnexion">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-md-inline ms-1">Déconnexion</span>
                </button>
            </form>
            @yield('topbar-actions')
        </div>
    </div>

    <!-- Flash messages -->
    <div class="px-4 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Page content -->
    <main class="p-4 flex-grow-1">
        @yield('content')
    </main>

    <footer class="text-center text-muted py-3" style="font-size:.75rem;border-top:1px solid #e9ecef;">
        © {{ date('Y') }} Cric Croc — Système de gestion commerciale
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ── Polling notifications toutes les 30 secondes ──
function rafraichirNotifications() {
    fetch('{{ route('notifications.compteur') }}')
        .then(r => r.json())
        .then(data => {
            const badge        = document.getElementById('notifBadge');
            const sidebarBadge = document.getElementById('sidebarNotifBadge');
            if (data.count > 0) {
                badge.textContent        = data.count > 99 ? '99+' : data.count;
                badge.style.display      = 'inline-block';
                sidebarBadge.textContent = data.count;
                sidebarBadge.style.display = 'inline-block';
            } else {
                badge.style.display        = 'none';
                sidebarBadge.style.display = 'none';
            }
        })
        .catch(() => {});
}

// Lancer immédiatement puis toutes les 30s
rafraichirNotifications();
setInterval(rafraichirNotifications, 30000);
</script>

@stack('scripts')
</body>
</html>
