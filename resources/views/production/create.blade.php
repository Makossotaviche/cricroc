@extends('layouts.app')
@section('title', 'Nouvelle production')
@section('page-title', 'Nouvelle production')

@push('styles')
<style>
    .production-row { background:#F8F9FA; border:1px solid #E5E7EB; border-radius:10px; padding:1rem; margin-bottom:1rem; }
    .conso-row { background:#fff; border-radius:8px; padding:.6rem; margin-bottom:.5rem; }
</style>
@endpush

@section('content')
@if($errors->has('productions'))
    <div class="alert alert-danger">{{ $errors->first('productions') }}</div>
@endif

<form method="POST" action="{{ route('production.store') }}" id="formProd">
@csrf
<div class="table-card p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h6 class="fw-bold mb-1"><i class="bi bi-gear me-2 text-warning"></i>Produits finis à fabriquer</h6>
            <div class="text-muted small">Ajoutez un produit fini par ligne et indiquez le nombre de ballots à produire.</div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAjoutProduction">
            <i class="bi bi-plus-lg me-1"></i>Ajouter un produit
        </button>
    </div>

    <div id="productionContainer"></div>

    <div class="d-flex gap-2 justify-content-end mt-3">
        <a href="{{ route('production.index') }}" class="btn btn-outline-secondary">Annuler</a>
        <button type="submit" class="btn btn-orange"><i class="bi bi-save me-1"></i>Enregistrer les productions</button>
    </div>
</div>

<template id="productionTemplate">
    <section class="production-row" data-production-index="__P__">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold mb-0">Produit fini <span class="production-number"></span></h6>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-production" aria-label="Supprimer ce produit">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-md-7">
                <label class="form-label fw-semibold">Produit fini <span class="text-danger">*</span></label>
                <select name="productions[__P__][idproduit]" class="form-select" required>
                    <option value="">— Choisir un produit fini —</option>
                    @foreach($produitsFinis as $p)
                        <option value="{{ $p->idproduit }}">{{ $p->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Quantité produite <span class="text-danger">*</span></label>
                <input type="number" name="productions[__P__][quantite_produite]" class="form-control" step="1" min="1" required>
            </div>
        </div>
        <div class="mt-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label fw-semibold mb-0">Matières premières consommées pour ce produit</label>
                <button type="button" class="btn btn-sm btn-outline-secondary btn-add-conso"><i class="bi bi-plus-lg me-1"></i>Ajouter une matière</button>
            </div>
            <div class="conso-container"></div>
        </div>
    </section>
</template>

<template id="consoTemplate">
    <div class="conso-row" data-consommation-index="__C__">
        <div class="row g-2 align-items-end">
            <div class="col-7">
                <label class="form-label small fw-semibold">Matière première / emballage</label>
                <select name="productions[__P__][consommations][__C__][idproduit]" class="form-select form-select-sm" required>
                    <option value="">— Choisir —</option>
                    @foreach($matieresPremiere as $mp)
                        <option value="{{ $mp->idproduit }}">{{ $mp->nom }} ({{ number_format($mp->stock?->quantite_disponible ?? 0, 0, '.', '') }} {{ $mp->unite }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                <label class="form-label small fw-semibold">Quantité consommée</label>
                <input type="number" name="productions[__P__][consommations][__C__][quantite_consommee]" class="form-control form-control-sm" step="1" min="1" required>
            </div>
            <div class="col-2 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-conso" aria-label="Supprimer cette matière"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    </div>
</template>
</form>
@endsection

@push('scripts')
<script>
(() => {
    const productionContainer = document.getElementById('productionContainer');
    const productionTemplate = document.getElementById('productionTemplate').innerHTML;
    const consoTemplate = document.getElementById('consoTemplate').innerHTML;
    const anciennesProductions = @json(old('productions', []));
    let prochainIndexProduction = 0;

    function ajouterConsommation(ligne, ancienne = null) {
        const pIndex = ligne.dataset.productionIndex;
        const cIndex = ligne.dataset.nextConsoIndex || 0;
        ligne.dataset.nextConsoIndex = Number(cIndex) + 1;
        const wrapper = document.createElement('div');
        wrapper.innerHTML = consoTemplate.replaceAll('__P__', pIndex).replaceAll('__C__', cIndex);
        const row = wrapper.firstElementChild;
        if (ancienne) {
            row.querySelector('select').value = ancienne.idproduit || '';
            row.querySelector('input').value = ancienne.quantite_consommee || '';
        }
        ligne.querySelector('.conso-container').appendChild(row);
    }

    function ajouterProduction(ancienne = {}) {
        const index = prochainIndexProduction++;
        const wrapper = document.createElement('div');
        wrapper.innerHTML = productionTemplate.replaceAll('__P__', index);
        const ligne = wrapper.firstElementChild;
        ligne.dataset.productionIndex = index;
        ligne.querySelector('.production-number').textContent = index + 1;
        ligne.querySelector('select[name$="[idproduit]"]').value = ancienne.idproduit || '';
        ligne.querySelector('input[name$="[quantite_produite]"]').value = ancienne.quantite_produite || '';
        ligne.querySelector('.btn-remove-production').addEventListener('click', () => ligne.remove());
        ligne.querySelector('.btn-add-conso').addEventListener('click', () => ajouterConsommation(ligne));
        productionContainer.appendChild(ligne);
        (ancienne.consommations || []).forEach(item => ajouterConsommation(ligne, item));
        return ligne;
    }

    document.getElementById('btnAjoutProduction').addEventListener('click', () => ajouterProduction());
    productionContainer.addEventListener('click', event => {
        const bouton = event.target.closest('.btn-remove-conso');
        if (bouton) bouton.closest('.conso-row').remove();
    });

    if (anciennesProductions.length) {
        anciennesProductions.forEach(ligne => ajouterProduction(ligne));
    } else {
        ajouterProduction();
    }
})();
</script>
@endpush
