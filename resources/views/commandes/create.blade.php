{{--
    Vue : resources/views/commandes/create.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title', 'Nouvelle commande')
@section('page-title', 'Nouvelle commande')

@push('styles')
<style>
    .ligne-row { background:#F8F9FA; border-radius:10px; padding:1rem; margin-bottom:.75rem; }
    .ligne-row:hover { background:#F0F2F5; }
    .total-box { background: linear-gradient(135deg,#E8650A,#F7923A); color:#fff; border-radius:12px; padding:1rem 1.5rem; }
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('commandes.store') }}" id="formCommande">
@csrf
<div class="row g-4">

    {{-- Infos générales --}}
    <div class="col-md-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informations</h6>
            <div class="mb-3">
                <label class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                <select name="idclient" class="form-select @error('idclient') is-invalid @enderror" required>
                    <option value="">— Choisir un client —</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->idclient }}" {{ old('idclient') == $client->idclient ? 'selected' : '' }}>
                            {{ $client->nom }}
                        </option>
                    @endforeach
                </select>
                @error('idclient')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="total-box mt-4">
                <div style="font-size:.8rem;opacity:.85;">MONTANT TOTAL</div>
                <div id="totalDisplay" class="fw-bold" style="font-size:1.6rem;">0 FCFA</div>
            </div>
            <div class="mt-4 d-flex flex-column gap-2">
                <button type="submit" class="btn btn-orange w-100">
                    <i class="bi bi-save me-2"></i>Enregistrer la commande
                </button>
                <a href="{{ route('commandes.index') }}" class="btn btn-outline-secondary w-100">Annuler</a>
            </div>
        </div>
    </div>

    {{-- Lignes de commande --}}
    <div class="col-md-8">
        <div class="table-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>Produits commandés</h6>
                <button type="button" class="btn btn-sm btn-orange" id="btnAjoutLigne">
                    <i class="bi bi-plus-lg me-1"></i>Ajouter un produit
                </button>
            </div>

            <div id="lignesContainer"></div>

            {{-- Template ligne (masqué) --}}
            <template id="ligneTemplate">
                <div class="ligne-row" data-index="__IDX__">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Produit</label>
                            <select name="lignes[__IDX__][idproduit]" class="form-select form-select-sm produit-select" required>
                                <option value="">— Choisir —</option>
                                @foreach($produits as $p)
                                    <option value="{{ $p->idproduit }}"
                                            data-prix="{{ $p->prix_unitaire }}"
                                            data-stock="{{ $p->stock?->quantite_disponible ?? 0 }}"
                                            data-unite="{{ $p->unite }}">
                                        {{ $p->nom }} ({{ number_format($p->stock?->quantite_disponible ?? 0, 0, '.', '') }} {{ $p->unite }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Qté</label>
                            <input type="number" name="lignes[__IDX__][quantite]" class="form-control form-control-sm qte-input"
                                   step="1" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Prix unitaire (FCFA)</label>
                            <input type="number" name="lignes[__IDX__][prix_unitaire]" class="form-control form-control-sm prix-input"
                                   step="0.01" min="0" required>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">S/Total</label>
                            <div class="fw-bold text-orange sous-total" style="font-size:.85rem;padding:.375rem 0;">0</div>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-ligne mt-3">
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
let index = 0;

function formatFCFA(n) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(n)) + ' FCFA';
}

function recalcTotal() {
    let total = 0;
    document.querySelectorAll('.ligne-row').forEach(row => {
        const qte  = parseFloat(row.querySelector('.qte-input').value)  || 0;
        const prix = parseFloat(row.querySelector('.prix-input').value) || 0;
        const st   = qte * prix;
        row.querySelector('.sous-total').textContent = formatFCFA(st);
        total += st;
    });
    document.getElementById('totalDisplay').textContent = formatFCFA(total);
}

function ajouterLigne() {
    const tpl = document.getElementById('ligneTemplate').innerHTML.replaceAll('__IDX__', index++);
    const div = document.createElement('div');
    div.innerHTML = tpl;
    const row = div.firstElementChild;
    document.getElementById('lignesContainer').appendChild(row);

    // Remplir le prix auto quand produit choisi
    row.querySelector('.produit-select').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        row.querySelector('.prix-input').value = opt.dataset.prix || '';
        recalcTotal();
    });

    row.querySelector('.qte-input').addEventListener('input', recalcTotal);
    row.querySelector('.prix-input').addEventListener('input', recalcTotal);

    row.querySelector('.btn-remove-ligne').addEventListener('click', function() {
        row.remove();
        recalcTotal();
    });
}

document.getElementById('btnAjoutLigne').addEventListener('click', ajouterLigne);

// Ajouter une ligne dès le chargement
ajouterLigne();
</script>
@endpush
