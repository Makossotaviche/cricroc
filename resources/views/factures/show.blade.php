@extends('layouts.app')
@section('title','Facture '.$facture->numero_facture)
@section('page-title','Facture '.$facture->numero_facture)

@section('topbar-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('factures.pdf', $facture) }}" class="btn btn-sm btn-orange">
            <i class="bi bi-file-earmark-pdf me-1"></i>Télécharger PDF
        </a>
        <a href="{{ route('factures.imprimer', $facture) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
            <i class="bi bi-printer me-1"></i>Imprimer
        </a>
    </div>
@endsection

@section('content')
@php($totalPaye = (float) $facture->paiements->sum('montant'))
<div class="row g-4">
    <div class="col-md-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3">Détails</h6>
            <dl class="row mb-0" style="font-size:.9rem;">
                <dt class="col-5 text-muted">Facture</dt>
                <dd class="col-7">{{ $facture->numero_facture }}</dd>
                <dt class="col-5 text-muted">Date</dt>
                <dd class="col-7">{{ $facture->date_facture->format('d/m/Y') }}</dd>
                <dt class="col-5 text-muted">Client</dt>
                <dd class="col-7">{{ $facture->commande->client->nom }}</dd>
                <dt class="col-5 text-muted">Montant HT</dt>
                <dd class="col-7">{{ number_format($facture->montant_ht ?? $facture->montant_total, 0, ',', ' ') }} FCFA</dd>
                <dt class="col-5 text-muted">TVA ({{ number_format($facture->taux_tva ?? 0, 2, ',', ' ') }} %)</dt>
                <dd class="col-7">{{ number_format($facture->montant_tva ?? 0, 0, ',', ' ') }} FCFA</dd>
                <dt class="col-5 text-muted fw-bold">Total TTC</dt>
                <dd class="col-7 fw-bold" style="color:#E8650A;">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</dd>
                <dt class="col-5 text-muted">Statut</dt>
                <dd class="col-7">
                    @if($totalPaye >= (float) $facture->montant_total)
                        <span class="badge bg-success">Payée</span>
                    @elseif($totalPaye > 0)
                        <span class="badge bg-info text-dark">Partiellement payée</span>
                    @else
                        <span class="badge bg-warning text-dark">Non payée</span>
                    @endif
                </dd>
            </dl>

            <hr>
            <h6 class="fw-bold mb-2">Historique des paiements</h6>
            <div class="mb-2" style="font-size:.85rem;">
                <div>Total payé : <strong>{{ number_format($totalPaye, 0, ',', ' ') }} FCFA</strong></div>
                <div>Reste à payer : <strong>{{ number_format(max(0, $facture->montant_total - $totalPaye), 0, ',', ' ') }} FCFA</strong></div>
            </div>
            @if($facture->paiements->isNotEmpty())
            <div class="table-responsive mb-3">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Date</th><th>Mode</th><th class="text-end">Montant</th></tr></thead>
                    <tbody>
                        @foreach($facture->paiements as $paiement)
                        <tr>
                            <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $paiement->mode)) }}</td>
                            <td class="text-end">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-muted small">Aucun paiement enregistré.</p>
            @endif

            @if($facture->resteAPayer() > 0 && auth()->user()->isAgentCommercial())
            <h6 class="fw-bold mb-2">Enregistrer un paiement</h6>
            <form method="POST" action="{{ route('factures.paiement', $facture) }}">
                @csrf
                <div class="mb-2">
                    <input type="number" name="montant" class="form-control form-control-sm"
                           placeholder="Montant (FCFA)" value="{{ $facture->resteAPayer() }}" max="{{ $facture->resteAPayer() }}" required step="0.01" min="0.01">
                </div>
                <div class="mb-2">
                    <select name="mode" class="form-select form-select-sm" required>
                        <option value="especes">Espèces</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="virement">Virement</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-orange w-100">
                    <i class="bi bi-cash me-1"></i>Enregistrer le paiement
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-card">
            <div class="p-3 border-bottom fw-bold"><i class="bi bi-list-ul me-2"></i>Détail des produits</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Sous-total</th></tr></thead>
                    <tbody>
                        @foreach($facture->commande->lignes as $ligne)
                        <tr>
                            <td>{{ $ligne->produit->nom }}</td>
                            <td>{{ number_format($ligne->quantite, 0, '.', '') }} {{ $ligne->produit->unite }}</td>
                            <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                            <td class="fw-bold">{{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><th colspan="3" class="text-end">Total HT</th><th>{{ number_format($facture->montant_ht ?? $facture->montant_total, 0, ',', ' ') }} FCFA</th></tr>
                        <tr><th colspan="3" class="text-end">TVA ({{ number_format($facture->taux_tva ?? 0, 2, ',', ' ') }} %)</th><th>{{ number_format($facture->montant_tva ?? 0, 0, ',', ' ') }} FCFA</th></tr>
                        <tr class="table-light"><th colspan="3" class="text-end">TOTAL TTC</th><th style="color:#E8650A;font-size:1.1rem;">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</th></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
