{{--
    Vue : resources/views/factures/index.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Factures')
@section('page-title','Factures')

@section('topbar-actions')
    @if(auth()->user()->isAdmin())
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalTva">
            <i class="bi bi-percent me-1"></i>Configurer la TVA ({{ number_format($tauxTva, 2, ',', ' ') }} %)
        </button>
    @endif
@endsection

@section('content')
<div class="table-card">
    <div class="p-3 border-bottom d-flex gap-2 align-items-center">
        <i class="bi bi-receipt text-primary fs-5"></i>
        <span class="fw-bold">Toutes les factures</span>
        <span class="badge bg-secondary">{{ $factures->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Numéro</th><th>Client</th><th>Date</th><th>Total TTC</th><th>Paiement</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($factures as $f)
                <tr>
                    <td class="text-muted">{{ $f->numero_facture ?? '#' . $f->idfacture }}</td>
                    <td><strong>{{ $f->commande->client->nom }}</strong></td>
                    <td>{{ $f->date_facture->format('d/m/Y') }}</td>
                    <td>{{ number_format($f->montant_total,0,',',' ') }} FCFA</td>
                    <td>
                        @php($paye = (float) ($f->paiements_sum_montant ?? 0))
                        @if($paye >= (float) $f->montant_total)
                            <span class="badge" style="background:#D1FAE5;color:#065F46;border-radius:6px;">
                                <i class="bi bi-check-circle me-1"></i>Payée
                            </span>
                        @elseif($paye > 0)
                            <span class="badge bg-info text-dark">Partiellement payée</span>
                        @else
                            <span class="badge" style="background:#FEF3C7;color:#92400E;border-radius:6px;">
                                En attente
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('factures.show', $f) }}" class="btn btn-sm btn-light">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('factures.imprimer', $f) }}" class="btn btn-sm btn-light" target="_blank">
                            <i class="bi bi-printer"></i>
                        </a>
                        <a href="{{ route('factures.pdf', $f) }}" class="btn btn-sm btn-light" title="Télécharger PDF">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Aucune facture</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($factures->hasPages())
    <div class="p-3 border-top">{{ $factures->links() }}</div>
    @endif
</div>

@if(auth()->user()->isAdmin())
<div class="modal fade" id="modalTva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Taux de TVA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="{{ route('factures.tva') }}">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <label for="tauxTva" class="form-label">Taux applicable aux nouvelles factures (%)</label>
                    <input id="tauxTva" type="number" class="form-control" name="taux_tva" value="{{ old('taux_tva', $tauxTva) }}" min="0" max="100" step="0.01" required>
                    <div class="form-text">Le taux enregistré reste figé sur chaque facture déjà créée.</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-orange">Enregistrer</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
