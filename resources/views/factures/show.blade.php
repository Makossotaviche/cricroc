{{--
    Vue : resources/views/factures/show.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Facture #'.$facture->idfacture)
@section('page-title','Facture #'.$facture->idfacture)

@section('topbar-actions')
    <a href="{{ route('factures.imprimer', $facture) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
        <i class="bi bi-printer me-1"></i>Imprimer
    </a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3">Détails</h6>
            <dl class="row mb-0" style="font-size:.9rem;">
                <dt class="col-5 text-muted">Facture</dt>
                <dd class="col-7">#{{ $facture->idfacture }}</dd>
                <dt class="col-5 text-muted">Date</dt>
                <dd class="col-7">{{ $facture->date_facture->format('d/m/Y') }}</dd>
                <dt class="col-5 text-muted">Client</dt>
                <dd class="col-7">{{ $facture->commande->client->nom }}</dd>
                <dt class="col-5 text-muted">Montant</dt>
                <dd class="col-7 fw-bold" style="color:#E8650A;">
                    {{ number_format($facture->montant_total,0,',',' ') }} FCFA
                </dd>
                <dt class="col-5 text-muted">Statut</dt>
                <dd class="col-7">
                    @if($facture->paiement)
                        <span class="badge" style="background:#D1FAE5;color:#065F46;">Payée</span>
                    @else
                        <span class="badge" style="background:#FEF3C7;color:#92400E;">Non payée</span>
                    @endif
                </dd>
            </dl>

            @if($facture->paiement)
            <hr>
            <h6 class="fw-bold mb-2">Paiement reçu</h6>
            <div style="font-size:.85rem;">
                <div><strong>{{ number_format($facture->paiement->montant,0,',',' ') }} FCFA</strong></div>
                <div class="text-muted">{{ $facture->paiement->date_paiement->format('d/m/Y') }}</div>
                <div class="text-muted">{{ ucfirst(str_replace('_',' ',$facture->paiement->mode)) }}</div>
            </div>
            @else
            @if(auth()->user()->isAgentCommercial())
            <hr>
            <h6 class="fw-bold mb-2">Enregistrer le paiement</h6>
            <form method="POST" action="{{ route('factures.paiement', $facture) }}">
                @csrf
                <div class="mb-2">
                    <input type="number" name="montant" class="form-control form-control-sm"
                           placeholder="Montant (FCFA)" value="{{ $facture->montant_total }}" required step="1">
                </div>
                <div class="mb-2">
                    <select name="mode" class="form-select form-select-sm" required>
                        <option value="especes">Espèces</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="virement">Virement</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-orange w-100">
                    <i class="bi bi-cash me-1"></i>Confirmer le paiement
                </button>
            </form>
            @endif
            @endif
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-card">
            <div class="p-3 border-bottom fw-bold">
                <i class="bi bi-list-ul me-2"></i>Détail des produits
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Sous-total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($facture->commande->lignes as $ligne)
                        <tr>
                            <td>{{ $ligne->produit->nom }}</td>
                            <td>{{ $ligne->quantite }} {{ $ligne->produit->unite }}</td>
                            <td>{{ number_format($ligne->prix_unitaire,0,',',' ') }} FCFA</td>
                            <td class="fw-bold">{{ number_format($ligne->sous_total,0,',',' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="3" class="text-end">TOTAL</th>
                            <th style="color:#E8650A;font-size:1.1rem;">
                                {{ number_format($facture->montant_total,0,',',' ') }} FCFA
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
