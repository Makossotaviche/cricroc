{{--
    Vue : resources/views/livraisons/show.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Livraison #'.$livraison->idlivraison)
@section('page-title','Livraison #'.$livraison->idlivraison)

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3">Informations</h6>
            <dl class="row mb-0" style="font-size:.9rem;">
                <dt class="col-5 text-muted">Client</dt>
                <dd class="col-7">{{ $livraison->commande->client->nom }}</dd>
                <dt class="col-5 text-muted">Livreur</dt>
                <dd class="col-7">{{ $livraison->livreur?->name ?? '—' }}</dd>
                <dt class="col-5 text-muted">Date livraison</dt>
                <dd class="col-7">{{ $livraison->date_livraison?->format('d/m/Y') ?? '—' }}</dd>
                <dt class="col-5 text-muted">Statut</dt>
                <dd class="col-7">
                    <span class="badge badge-{{ $livraison->statut }} px-2 py-1" style="border-radius:6px;">
                        {{ $livraison->statut_libelle }}
                    </span>
                </dd>
            </dl>

            @if($livraison->statut !== 'livree')
            <hr>
            <h6 class="fw-bold mb-2">Mettre à jour le statut</h6>
            <form method="POST" action="{{ route('livraisons.statut', $livraison) }}">
                @csrf @method('PATCH')
                <div class="mb-2">
                    <select name="statut" class="form-select form-select-sm">
                        <option value="en_attente" {{ $livraison->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="en_cours"   {{ $livraison->statut === 'en_cours'   ? 'selected' : '' }}>En cours</option>
                        <option value="livree"     {{ $livraison->statut === 'livree'     ? 'selected' : '' }}>Livrée</option>
                    </select>
                </div>
                <button class="btn btn-sm btn-orange w-100">
                    <i class="bi bi-check me-1"></i>Confirmer
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-card">
            <div class="p-3 border-bottom fw-bold">
                <i class="bi bi-box-seam me-2"></i>Produits à livrer
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Sous-total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($livraison->commande->lignes as $ligne)
                        <tr>
                            <td>{{ $ligne->produit->nom }}</td>
                            <td>{{ $ligne->quantite }} {{ $ligne->produit->unite }}</td>
                            <td>{{ number_format($ligne->prix_unitaire,0,',',' ') }} FCFA</td>
                            <td>{{ number_format($ligne->sous_total,0,',',' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
