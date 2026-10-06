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
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Nom</th><th>Type</th><th>Prix unitaire</th><th>Unité</th><th>Stock</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($produits as $p)
                <tr>
                    <td><strong>{{ $p->nom }}</strong></td>
                    <td><span class="badge bg-light text-dark border">{{ $p->type_libelle }}</span></td>
                    <td>{{ number_format($p->prix_unitaire,0,',',' ') }} FCFA</td>
                    <td>{{ $p->unite }}</td>
                    <td>
                        @if($p->stock)
                            <span class="{{ $p->stock->estEnRupture() ? 'text-danger fw-bold' : 'text-success' }}">
                                {{ $p->stock->quantite_disponible }}
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
                <tr><td colspan="6" class="text-center text-muted py-4">Aucun produit</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produits->hasPages())
    <div class="p-3 border-top">{{ $produits->links() }}</div>
    @endif
</div>
@endsection
