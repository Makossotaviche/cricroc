{{--
    Vue : resources/views/stock/mouvements.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Mouvements — '.$stock->produit->nom)
@section('page-title','Mouvements : '.$stock->produit->nom)

@section('topbar-actions')
    <a href="{{ route('stock.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Retour au stock
    </a>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:#D1FAE5;color:#065F46;"><i class="bi bi-boxes"></i></div>
            <div class="stat-value">{{ $stock->quantite_disponible }}</div>
            <div class="stat-label">{{ $stock->produit->unite }} en stock</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:#DBEAFE;color:#1D4ED8;"><i class="bi bi-arrow-down-circle"></i></div>
            <div class="stat-value">{{ $mouvements->where('type','entree')->sum('quantite') }}</div>
            <div class="stat-label">Entrées totales</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:#FEE2E2;color:#991B1B;"><i class="bi bi-arrow-up-circle"></i></div>
            <div class="stat-value">{{ $mouvements->where('type','sortie')->sum('quantite') }}</div>
            <div class="stat-label">Sorties totales</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:#FFF3CD;color:#856404;"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-value">{{ $stock->seuil_alerte }}</div>
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
                        @if($m->type === 'entree')
                            <span class="badge" style="background:#D1FAE5;color:#065F46;">
                                <i class="bi bi-arrow-down me-1"></i>Entrée
                            </span>
                        @else
                            <span class="badge" style="background:#FEE2E2;color:#991B1B;">
                                <i class="bi bi-arrow-up me-1"></i>Sortie
                            </span>
                        @endif
                    </td>
                    <td class="fw-bold">{{ $m->quantite }} {{ $stock->produit->unite }}</td>
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
