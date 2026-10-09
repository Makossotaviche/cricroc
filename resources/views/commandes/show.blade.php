{{--
    Vue : resources/views/commandes/show.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title', 'Commande #'.$commande->idcommande)
@section('page-title', 'Commande #'.$commande->idcommande)

@section('topbar-actions')
    @if($commande->statut === 'en_attente')
        <form method="POST" action="{{ route('commandes.valider', $commande) }}" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-success" onclick="return confirm('Valider cette commande ?')">
                <i class="bi bi-check-circle me-1"></i>Valider
            </button>
        </form>
        <form method="POST" action="{{ route('commandes.rejeter', $commande) }}" class="d-inline ms-1">
            @csrf
            <button class="btn btn-sm btn-danger" onclick="return confirm('Rejeter cette commande ?')">
                <i class="bi bi-x-circle me-1"></i>Rejeter
            </button>
        </form>
    @endif
@endsection

@section('content')
<div class="row g-4">

    {{-- Résumé --}}
    <div class="col-md-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3">Informations</h6>
            <dl class="row mb-0" style="font-size:.9rem;">
                <dt class="col-5 text-muted">Client</dt>
                <dd class="col-7">
                    <a href="{{ route('clients.show', $commande->client) }}">{{ $commande->client->nom }}</a>
                </dd>
                <dt class="col-5 text-muted">Date</dt>
                <dd class="col-7">{{ $commande->date_commande->format('d/m/Y') }}</dd>
                <dt class="col-5 text-muted">Statut</dt>
                <dd class="col-7">
                    <span class="badge badge-{{ $commande->statut }} px-2 py-1" style="border-radius:6px;">
                        {{ $commande->statut_libelle }}
                    </span>
                </dd>
                <dt class="col-5 text-muted">Montant</dt>
                <dd class="col-7 fw-bold" style="color:#E8650A;">
                    {{ number_format($commande->montant_total,0,',',' ') }} FCFA
                </dd>
            </dl>

            @if($commande->facture)
            <hr>
            <h6 class="fw-bold mb-2">Facture</h6>
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted">Facture #{{ $commande->facture->idfacture }}</span>
                <div class="d-flex gap-1">
                    <a href="{{ route('factures.show', $commande->facture) }}" class="btn btn-sm btn-light">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('factures.imprimer', $commande->facture) }}" class="btn btn-sm btn-light" target="_blank">
                        <i class="bi bi-printer"></i>
                    </a>
                </div>
            </div>
            @if($commande->facture->estPayee())
                <span class="badge bg-success mt-2">Payée</span>
            @else
                <span class="badge bg-warning text-dark mt-2">Non payée</span>
            @endif
            @endif

            @if($commande->livraison)
            <hr>
            <h6 class="fw-bold mb-2">Livraison</h6>
            <span class="badge badge-{{ $commande->livraison->statut }} px-2 py-1" style="border-radius:6px;">
                {{ $commande->livraison->statut_libelle }}
            </span>
            @if($commande->livraison->livreur)
                <div class="text-muted mt-1" style="font-size:.82rem;">
                    <i class="bi bi-person me-1"></i>{{ $commande->livraison->livreur->name }}
                </div>
            @endif
            @endif
        </div>
    </div>

    {{-- Lignes produits --}}
    <div class="col-md-8">
        <div class="table-card">
            <div class="p-3 border-bottom fw-bold">
                <i class="bi bi-list-ul me-2"></i>Produits commandés
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Sous-total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($commande->lignes as $ligne)
                        <tr>
                            <td>{{ $ligne->produit->nom }}</td>
                            <td>{{ number_format($ligne->quantite, 0, '.', '') }} {{ $ligne->produit->unite }}</td>
                            <td>{{ number_format($ligne->prix_unitaire,0,',',' ') }} FCFA</td>
                            <td class="fw-bold">{{ number_format($ligne->sous_total,0,',',' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="3" class="text-end">TOTAL</th>
                            <th style="color:#E8650A;font-size:1.1rem;">
                                {{ number_format($commande->montant_total,0,',',' ') }} FCFA
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
