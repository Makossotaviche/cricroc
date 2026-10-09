{{--
    Vue : resources/views/dashboard.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')

{{-- ── Cartes statistiques ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-icon" style="background:#FFF3CD;color:#E8650A;">
                    <i class="bi bi-people-fill"></i>
                </span>
                <i class="bi bi-arrow-up-right text-success"></i>
            </div>
            <div class="stat-value">{{ $stats['clients'] }}</div>
            <div class="stat-label">Clients</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-icon" style="background:#DBEAFE;color:#1D4ED8;">
                    <i class="bi bi-cart-fill"></i>
                </span>
                @if($stats['commandes_attente'] > 0)
                    <span class="badge bg-warning text-dark">{{ $stats['commandes_attente'] }} en attente</span>
                @endif
            </div>
            <div class="stat-value">{{ $stats['commandes_today'] }}</div>
            <div class="stat-label">Commandes aujourd'hui</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-icon" style="background:#D1FAE5;color:#065F46;">
                    <i class="bi bi-cash-coin"></i>
                </span>
            </div>
            <div class="stat-value" style="font-size:1.4rem;">{{ number_format($stats['ca_mois'], 0, ',', ' ') }}</div>
            <div class="stat-label">CA ce mois (FCFA)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="stat-icon" style="background:#FEE2E2;color:#991B1B;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </span>
                @if($stats['livraisons_cours'] > 0)
                    <span class="badge bg-info">{{ $stats['livraisons_cours'] }} en cours</span>
                @endif
            </div>
            <div class="stat-value">{{ $stats['ruptures'] }}</div>
            <div class="stat-label">Alertes stock</div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- ── Dernières commandes ── --}}
    <div class="col-lg-8">
        <div class="table-card">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                <h6 class="fw-bold mb-0"><i class="bi bi-cart3 me-2 text-primary"></i>Dernières commandes</h6>
                @if(auth()->user()->isAgentCommercial())
                    <a href="{{ route('commandes.index') }}" class="btn btn-sm btn-outline-secondary">Voir tout</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th><th>Client</th><th>Date</th><th>Montant</th><th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dernieresCommandes as $c)
                        <tr>
                            <td><span class="text-muted">#{{ $c->idcommande }}</span></td>
                            <td><strong>{{ $c->client->nom }}</strong></td>
                            <td>{{ $c->date_commande->format('d/m/Y') }}</td>
                            <td>{{ number_format($c->montant_total, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <span class="badge badge-{{ $c->statut }} px-2 py-1" style="border-radius:6px;">
                                    {{ $c->statut_libelle }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Aucune commande</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Alertes stock ── --}}
    <div class="col-lg-4">
        <div class="table-card h-100">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Stocks en alerte
                </h6>
                @if(auth()->user()->isGestionnaireStock())
                    <a href="{{ route('stock.index') }}" class="btn btn-sm btn-outline-secondary">Gérer</a>
                @endif
            </div>
            <div class="p-2">
                @forelse($stocksAlerte as $stock)
                    <div class="d-flex align-items-center gap-2 p-2 rounded mb-1"
                         style="background:#FEF2F2;">
                        <i class="bi bi-box-seam text-danger"></i>
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.85rem;">{{ $stock->produit->nom }}</div>
                            <div style="font-size:.75rem;color:#991B1B;">
                                {{ number_format($stock->quantite_disponible, 0, '.', '') }} {{ $stock->produit->type === 'produit_fini' ? 'ballot' : $stock->produit->unite }} restant(s)
                            </div>
                        </div>
                        <span class="badge bg-danger">!</span>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-1"></i>
                        Tous les stocks sont OK
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Productions récentes ── --}}
    @if(auth()->user()->isResponsableProduction())
    <div class="col-12">
        <div class="table-card">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                <h6 class="fw-bold mb-0"><i class="bi bi-gear me-2 text-warning"></i>Productions récentes</h6>
                <a href="{{ route('production.index') }}" class="btn btn-sm btn-outline-secondary">Voir tout</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Date</th><th>Produit</th><th>Quantité produite</th></tr>
                    </thead>
                    <tbody>
                        @forelse($dernieresProductions as $p)
                        <tr>
                            <td>{{ $p->date_production->format('d/m/Y') }}</td>
                            <td>{{ $p->produit->nom }}</td>
                            <td>{{ number_format($p->quantite_produite, 0, '.', '') }} {{ $p->produit->unite }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Aucune production</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="table-card p-3 p-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Rapport des ventes</h5>
                    <div class="text-muted small">Facturation et encaissements sur les 12 derniers mois</div>
                </div>
                <a href="{{ route('export.factures') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-download me-1"></i>Exporter les factures</a>
            </div>
            <div style="height:320px; position:relative"><canvas id="salesTrendChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="table-card p-3 p-md-4 h-100">
            <h6 class="fw-bold mb-1">Commandes validées</h6>
            <div class="text-muted small mb-3">Nombre par mois</div>
            <div style="height:280px; position:relative"><canvas id="ordersChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="table-card p-3 p-md-4 h-100">
            <h6 class="fw-bold mb-1">Encaissements par mode</h6>
            <div class="text-muted small mb-3">Répartition depuis janvier {{ now()->year }}</div>
            <div style="height:280px; position:relative"><canvas id="paymentModesChart"></canvas></div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (async function () {
            const money = value => new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(value) + ' FCFA';
            try {
                const response = await fetch('{{ route('dashboard.trends.sales') }}', { headers: { 'Accept': 'application/json' } });
                if (!response.ok) throw new Error('Impossible de charger les statistiques.');
                const report = await response.json();
                const common = { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 18 } } } };

                new Chart(document.getElementById('salesTrendChart'), {
                    type: 'line',
                    data: { labels: report.labels, datasets: [
                        { label: 'Montant facturé', data: report.facture, borderColor: '#E8650A', backgroundColor: 'rgba(232,101,10,.10)', fill: true, tension: .35, pointRadius: 3 },
                        { label: 'Montant encaissé', data: report.encaisse, borderColor: '#198754', backgroundColor: 'rgba(25,135,84,.08)', fill: true, tension: .35, pointRadius: 3 }
                    ] },
                    options: { ...common, interaction: { intersect: false, mode: 'index' }, scales: {
                        y: { beginAtZero: true, ticks: { callback: value => money(value) }, grid: { color: 'rgba(0,0,0,.06)' } },
                        x: { grid: { display: false } }
                    }, plugins: { ...common.plugins, tooltip: { callbacks: { label: context => context.dataset.label + ': ' + money(context.parsed.y) } } } }
                });

                new Chart(document.getElementById('ordersChart'), {
                    type: 'bar',
                    data: { labels: report.labels, datasets: [{ label: 'Commandes validées', data: report.commandes, backgroundColor: 'rgba(13,110,253,.72)', borderRadius: 6, maxBarThickness: 34 }] },
                    options: { ...common, plugins: { ...common.plugins, legend: { display: false } }, scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(0,0,0,.06)' } },
                        x: { grid: { display: false } }
                    } }
                });

                const colors = ['#E8650A', '#198754', '#0D6EFD'];
                new Chart(document.getElementById('paymentModesChart'), {
                    type: 'doughnut',
                    data: { labels: report.modes.labels, datasets: [{ data: report.modes.data, backgroundColor: colors, borderWidth: 0, hoverOffset: 7 }] },
                    options: { ...common, cutout: '66%', plugins: { ...common.plugins, tooltip: { callbacks: { label: context => context.label + ': ' + money(context.parsed) } } } }
                });
            } catch (error) {
                document.querySelectorAll('#salesTrendChart, #ordersChart, #paymentModesChart').forEach(canvas => {
                    canvas.parentElement.innerHTML = '<div class="text-muted text-center py-5">' + error.message + '</div>';
                });
            }
        })();
    </script>
@endpush

@endsection
