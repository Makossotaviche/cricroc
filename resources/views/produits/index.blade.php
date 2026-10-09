{{--
    Vue : resources/views/produits/index.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Produits')
@section('page-title','Produits')

@section('topbar-actions')
    <a href="{{ route('produits.create') }}" class="btn btn-orange btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nouveau produit
    </a>
@endsection

@section('content')
<div class="table-card">
    <div class="p-3 border-bottom d-flex gap-2 align-items-center">
        <i class="bi bi-bag text-primary fs-5"></i>
        <span class="fw-bold">Catalogue produits</span>
        <span class="badge bg-secondary">{{ $produits->total() }}</span>
    </div>
    <form method="GET" action="{{ route('produits.index') }}" class="p-3 border-bottom">
        <div class="row g-2 align-items-end">
            <div class="col-md-7">
                <label for="rechercheProduit" class="form-label small fw-semibold">Recherche</label>
                <input id="rechercheProduit" type="search" name="recherche" class="form-control"
                       value="{{ request('recherche') }}" placeholder="Nom du produit ou unité">
            </div>
            <div class="col-md-3">
                <label for="categorieProduit" class="form-label small fw-semibold">Cat&#233;gorie</label>
                <select id="categorieProduit" name="categorie" class="form-select">
                    <option value="">Toutes les cat&#233;gories</option>
                    <option value="matiere_premiere" @selected(request('categorie') === 'matiere_premiere')>Mati&#232;re premi&#232;re</option>
                    <option value="produit_fini" @selected(request('categorie') === 'produit_fini')>Produit fini</option>
                    <option value="emballage" @selected(request('categorie') === 'emballage')>Emballage</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-orange flex-grow-1"><i class="bi bi-search me-1"></i>Filtrer</button>
                <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary" title="Effacer les filtres"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Nom</th><th>Cat&#233;gorie</th><th>Prix de vente</th><th>Prix de revient</th><th>Unit&#233;</th><th>Stock</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($produits as $p)
                <tr>
                    <td><strong>{{ $p->nom }}</strong></td>
                    <td><span class="badge bg-light text-dark border">{{ $p->type_libelle }}</span></td>
                    <td>{{ number_format($p->prix_unitaire,0,',',' ') }} FCFA</td>
                    <td>{{ $p->prix_revient !== null ? number_format($p->prix_revient, 0, ',', ' ') . ' FCFA' : 'Non renseign&eacute;' }}</td>
                    <td>{{ $p->type === 'produit_fini' ? 'ballot' : $p->unite }}</td>
                    <td>
                        @if($p->stock)
                            <span class="{{ $p->stock->estEnRupture() ? 'text-danger fw-bold' : 'text-success' }}">
                                {{ number_format($p->stock->quantite_disponible, 0, '.', '') }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('produits.edit', $p) }}" class="btn btn-sm btn-light">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('produits.destroy', $p) }}" class="d-inline"
                              onsubmit="return confirm('Supprimer ce produit ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Aucun produit</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produits->hasPages())
    <div class="p-3 border-top">{{ $produits->links() }}</div>
    @endif
</div>
@endsection
