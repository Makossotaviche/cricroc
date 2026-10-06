{{--
    Vue : resources/views/produits/edit.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Modifier produit')
@section('page-title','Modifier produit')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('produits.update', $produit) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nom</label>
                    <input type="text" name="nom" class="form-control" value="{{ old('nom',$produit->nom) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="matiere_premiere" {{ $produit->type==='matiere_premiere' ? 'selected' : '' }}>Matière première</option>
                        <option value="produit_fini"     {{ $produit->type==='produit_fini'     ? 'selected' : '' }}>Produit fini</option>
                        <option value="emballage"        {{ $produit->type==='emballage'        ? 'selected' : '' }}>Emballage</option>
                    </select>
                </div>
                <div class="row g-2 mb-4">
                    <div class="col-7">
                        <label class="form-label fw-semibold">Prix unitaire (FCFA)</label>
                        <input type="number" name="prix_unitaire" step="1" min="0"
                               class="form-control" value="{{ old('prix_unitaire',$produit->prix_unitaire) }}" required>
                    </div>
                    <div class="col-5">
                        <label class="form-label fw-semibold">Unité</label>
                        <input type="text" name="unite" class="form-control"
                               value="{{ old('unite',$produit->unite) }}" required>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-orange">
                        <i class="bi bi-save me-1"></i>Mettre à jour
                    </button>
                    <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
