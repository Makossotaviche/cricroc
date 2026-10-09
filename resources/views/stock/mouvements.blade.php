{{--
    Vue : resources/views/stock/mouvements.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Mouvements — '.$stock->produit->nom)
@section('page-title','Mouvements : '.$stock->produit->nom)

@section('topbar-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('export.stock.mouvements', ['idstock' => $stock->idstock]) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-file-earmark-excel me-1"></i>Exporter en Excel
        </a>
        <a href="{{ route('stock.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Retour au stock
        </a>
    </div>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:#D1FAE5;color:#065F46;"><i class="bi bi-boxes"></i></div>
            <div class="stat-value">{{ number_format($stock->quantite_disponible, 0, '.', '') }}</div>
            <div class="stat-label">{{ $stock->produit->type === 'produit_fini' ? 'ballot' : $stock->produit->unite }} en stock</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:#DBEAFE;color:#1D4ED8;"><i class="bi bi-arrow-down-circle"></i></div>
            <div class="stat-value">{{ number_format($mouvements->where('type','entree')->sum('quantite'), 0, '.', '') }}</div>
            <div class="stat-label">Entrées totales</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:#FEE2E2;color:#991B1B;"><i class="bi bi-arrow-up-circle"></i></div>
            <div class="stat-value">{{ number_format($mouvements->where('type','sortie')->sum('quantite'), 0, '.', '') }}</div>
            <div class="stat-label">Sorties totales</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:#FFF3CD;color:#856404;"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-value">{{ number_format($stock->seuil_alerte, 0, '.', '') }}</div>
            <div class="stat-label">Seuil d'alerte</div>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="p-3 border-bottom fw-bold">
        <i class="bi bi-clock-history me-2"></i>Historique des mouvements
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Date</th><th>Type</th><th>Quantité</th><th>Motif</th></tr>
            </thead>
            <tbody>
                @forelse($mouvements as $m)
                <tr>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if(str_starts_with($m->motif ?? '', 'Ajustement inventaire physique'))
                            <span class="badge" style="background:#EDE9FE;color:#5B21B6;">
                                <i class="bi bi-clipboard-check me-1"></i>Ajustement
                            </span>
                        @elseif($m->type === 'entree')
                            <span class="badge" style="background:#D1FAE5;color:#065F46;">
                                <i class="bi bi-arrow-down me-1"></i>Entrée
                            </span>
                        @else
                            <span class="badge" style="background:#FEE2E2;color:#991B1B;">
                                <i class="bi bi-arrow-up me-1"></i>Sortie
                            </span>
                        @endif
                    </td>
                    <td class="fw-bold">{{ number_format($m->quantite, 0, '.', '') }} {{ $stock->produit->type === 'produit_fini' ? 'ballot' : $stock->produit->unite }}</td>
                    <td class="text-muted">{{ $m->motif ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Aucun mouvement</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mouvements->hasPages())
    <div class="p-3 border-top">{{ $mouvements->links() }}</div>
    @endif
</div>
@endsection
