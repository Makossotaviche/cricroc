{{--
    Vue : resources/views/production/create.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Nouvelle production')
@section('page-title','Nouvelle production')

@push('styles')
<style>
    .conso-row { background:#F8F9FA; border-radius:10px; padding:.85rem; margin-bottom:.6rem; }
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('production.store') }}" id="formProd">
@csrf
<div class="row g-4">

    {{-- Paramètres production --}}
    <div class="col-md-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-gear me-2 text-warning"></i>Production</h6>
            <div class="mb-3">
                <label class="form-label fw-semibold">Produit fini <span class="text-danger">*</span></label>
                <select name="idproduit" class="form-select @error('idproduit') is-invalid @enderror" required>
                    <option value="">— Choisir —</option>
                    @foreach($produitsFinis as $p)
                        <option value="{{ $p->idproduit }}" {{ old('idproduit') == $p->idproduit ? 'selected' : '' }}>
                            {{ $p->nom }}
                        </option>
                    @endforeach
                </select>
                @error('idproduit')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Quantité produite <span class="text-danger">*</span></label>
                <input type="number" name="quantite_produite" step="0.01" min="0.01"
                       class="form-control @error('quantite_produite') is-invalid @enderror"
                       value="{{ old('quantite_produite') }}" required>
                @error('quantite_produite')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex flex-column gap-2">
                <button type="submit" class="btn btn-orange">
                    <i class="bi bi-save me-1"></i>Enregistrer
                </button>
                <a href="{{ route('production.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </div>
    </div>

    {{-- Consommation matières premières --}}
    <div class="col-md-8">
        <div class="table-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-list-check me-2 text-primary"></i>Matières premières consommées
                </h6>
                <button type="button" class="btn btn-sm btn-orange" id="btnAjoutConso">
                    <i class="bi bi-plus-lg me-1"></i>Ajouter
                </button>
            </div>
            <div id="consoContainer"></div>

            <template id="consoTemplate">
                <div class="conso-row" data-idx="__IDX__">
                    <div class="row g-2 align-items-end">
                        <div class="col-7">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Matière première</label>
                            <select name="consommations[__IDX__][idproduit]" class="form-select form-select-sm" required>
                                <option value="">— Choisir —</option>
                                @foreach($matieresPremiere as $mp)
                                    <option value="{{ $mp->idproduit }}" data-unite="{{ $mp->unite }}"
                                            data-stock="{{ $mp->stock?->quantite_disponible ?? 0 }}">
                                        {{ $mp->nom }} ({{ $mp->stock?->quantite_disponible ?? 0 }} {{ $mp->unite }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Quantité</label>
                            <input type="number" name="consommations[__IDX__][quantite_consommee]"
                                   class="form-control form-control-sm" step="0.01" min="0.01" required>
                        </div>
                        <div class="col-2 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-conso mt-3">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
let idx = 0;
function ajouterConso() {
    const tpl = document.getElementById('consoTemplate').innerHTML.replaceAll('__IDX__', idx++);
    const div = document.createElement('div');
    div.innerHTML = tpl;
    const row = div.firstElementChild;
    document.getElementById('consoContainer').appendChild(row);
    row.querySelector('.btn-remove-conso').addEventListener('click', () => row.remove());
}
document.getElementById('btnAjoutConso').addEventListener('click', ajouterConso);
ajouterConso();
</script>
@endpush
