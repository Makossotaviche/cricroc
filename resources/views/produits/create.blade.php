{{--
    Vue : resources/views/produits/create.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Nouveau produit')
@section('page-title','Nouveau produit')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('produits.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom') }}" required>
                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">— Choisir —</option>
                        <option value="matiere_premiere" {{ old('type')==='matiere_premiere' ? 'selected' : '' }}>Matière première</option>
                        <option value="produit_fini"     {{ old('type')==='produit_fini'     ? 'selected' : '' }}>Produit fini</option>
                        <option value="emballage"        {{ old('type')==='emballage'        ? 'selected' : '' }}>Emballage</option>
                    </select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-7">
                        <label class="form-label fw-semibold">Prix unitaire (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" name="prix_unitaire" step="1" min="0"
                               class="form-control @error('prix_unitaire') is-invalid @enderror"
                               value="{{ old('prix_unitaire',0) }}" required>
                    </div>
                    <div class="col-5">
                        <label class="form-label fw-semibold">Unité <span class="text-danger">*</span></label>
                        <input type="text" name="unite" class="form-control"
                               value="{{ old('unite','unité') }}" placeholder="kg, litre, paquet..." required>
                    </div>
                </div>
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Stock initial</label>
                        <input type="number" name="stock_initial" step="0.01" min="0"
                               class="form-control" value="{{ old('stock_initial',0) }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Seuil d'alerte</label>
                        <input type="number" name="seuil_alerte" step="0.01" min="0"
                               class="form-control" value="{{ old('seuil_alerte',10) }}">
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-orange">
                        <i class="bi bi-save me-1"></i>Enregistrer
                    </button>
                    <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
