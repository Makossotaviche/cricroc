{{--
    Vue : resources/views/stock/index.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Stock')
@section('page-title','Gestion du Stock')

@section('content')

@if($ruptures > 0)
<div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <strong>{{ $ruptures }} produit(s)</strong> en dessous du seuil d'alerte !
</div>
@endif

<div class="d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-boxes text-primary fs-5"></i>
    <span class="fw-bold">État des stocks</span>
    <span class="badge bg-secondary">{{ $totalStocks }}</span>
    <div class="ms-auto d-flex gap-2">
        <a href="{{ route('export.stock.mouvements') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-earmark-excel me-1"></i>Historique Excel</a>
        <a href="{{ route('export.stock') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-earmark-excel me-1"></i>Stock Excel</a>
    </div>
</div>

@foreach($categories as $category)
<div class="table-card mb-4">
    <div class="p-3 border-bottom d-flex gap-2 align-items-center">
        <i class="bi {{ $category['icon'] }} text-{{ $category['color'] }} fs-5"></i>
        <span class="fw-bold">{{ $category['label'] }}</span>
        <span class="badge bg-secondary">{{ $category['stocks']->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Produit</th><th>Quantité disponible</th><th>Seuil alerte</th><th>État</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($category['stocks'] as $stock)
                <tr class="{{ $stock->estEnRupture() ? 'table-danger' : '' }}">
                    <td><strong>{{ $stock->produit->nom }}</strong></td>
                    <td><span class="fw-bold {{ $stock->estEnRupture() ? 'text-danger' : 'text-success' }}">{{ number_format($stock->quantite_disponible, 0, '.', '') }} {{ $stock->produit->type === 'produit_fini' ? 'ballot' : $stock->produit->unite }}</span></td>
                    <td>{{ number_format($stock->seuil_alerte, 0, '.', '') }} {{ $stock->produit->type === 'produit_fini' ? 'ballot' : $stock->produit->unite }}</td>
                    <td>
                        @if($stock->estEnRupture())
                            <span class="badge bg-danger rupture-badge">⚠ Rupture</span>
                        @else
                            <span class="badge bg-success">OK</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-success" title="Entrée" data-bs-toggle="modal" data-bs-target="#modalEntree" data-id="{{ $stock->idstock }}" data-nom="{{ $stock->produit->nom }}" data-unite="{{ $stock->produit->type === 'produit_fini' ? 'ballot' : $stock->produit->unite }}">
                                <i class="bi bi-arrow-down-circle"></i> Entrée
                            </button>
                            <button class="btn btn-sm btn-warning" title="Sortie" data-bs-toggle="modal" data-bs-target="#modalSortie" data-id="{{ $stock->idstock }}" data-nom="{{ $stock->produit->nom }}" data-unite="{{ $stock->produit->type === 'produit_fini' ? 'ballot' : $stock->produit->unite }}">
                                <i class="bi bi-arrow-up-circle"></i> Sortie
                            </button>
                            <button class="btn btn-sm btn-outline-primary" title="Inventaire physique" data-bs-toggle="modal" data-bs-target="#modalInventaire" data-id="{{ $stock->idstock }}" data-nom="{{ $stock->produit->nom }}" data-unite="{{ $stock->produit->type === 'produit_fini' ? 'ballot' : $stock->produit->unite }}" data-stock="{{ $stock->quantite_disponible }}">
                                <i class="bi bi-clipboard-check"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-dark" title="Modifier le seuil d'alerte" data-bs-toggle="modal" data-bs-target="#modalSeuil" data-id="{{ $stock->idstock }}" data-nom="{{ $stock->produit->nom }}" data-seuil="{{ $stock->seuil_alerte }}">
                                <i class="bi bi-bell"></i>
                            </button>
                            <a href="{{ route('stock.mouvements', $stock) }}" class="btn btn-sm btn-light" title="Historique"><i class="bi bi-clock-history"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Aucun produit dans cette catégorie</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($category['stocks']->hasPages())
    <div class="p-3 border-top">{{ $category['stocks']->links() }}</div>
    @endif
</div>
@endforeach

{{-- Modal Inventaire physique --}}
<div class="modal fade" id="modalInventaire" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title"><i class="bi bi-clipboard-check me-2"></i>Inventaire physique</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formInventaire">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-2" id="inventaireNom"></p>
                    <div class="small text-muted mb-3">Stock théorique : <span id="inventaireTheorique"></span></div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantité réellement comptée <span id="inventaireUnite"></span></label>
                        <input type="number" name="quantite_physique" id="quantitePhysique" class="form-control" step="1" min="0" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Justification <span class="text-danger">*</span></label>
                        <input type="text" name="motif" class="form-control" placeholder="Comptage, casse, perte..." maxlength="255" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Valider l'inventaire</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Seuil d'alerte --}}
<div class="modal fade" id="modalSeuil" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-bell me-2"></i>Seuil d'alerte</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formSeuil">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <p class="text-muted" id="seuilNom"></p>
                    <label class="form-label fw-semibold">Alerter à partir de</label>
                    <input type="number" name="seuil_alerte" id="seuilAlerte" class="form-control" step="1" min="0" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-orange">Enregistrer</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Entrée --}}
<div class="modal fade" id="modalEntree" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title"><i class="bi bi-arrow-down-circle me-2"></i>Entrée de stock</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formEntree">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3" id="entreeNom"></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantité <span id="entreeUnite"></span></label>
                        <input type="number" name="quantite" class="form-control" step="1" min="1" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Motif</label>
                        <input type="text" name="motif" class="form-control" placeholder="Approvisionnement, retour...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Confirmer</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Sortie --}}
<div class="modal fade" id="modalSortie" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h6 class="modal-title"><i class="bi bi-arrow-up-circle me-2"></i>Sortie de stock</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formSortie">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3" id="sortieNom"></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantité <span id="sortieUnite"></span></label>
                        <input type="number" name="quantite" class="form-control" step="1" min="1" required>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Motif</label>
                        <input type="text" name="motif" class="form-control" placeholder="Vente, perte...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning">Confirmer</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('modalEntree').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    this.querySelector('#entreeNom').textContent = btn.dataset.nom;
    this.querySelector('#entreeUnite').textContent = '(' + btn.dataset.unite + ')';
    document.getElementById('formEntree').action = '/stock/' + btn.dataset.id + '/entree';
});
document.getElementById('modalSortie').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    this.querySelector('#sortieNom').textContent = btn.dataset.nom;
    this.querySelector('#sortieUnite').textContent = '(' + btn.dataset.unite + ')';
    document.getElementById('formSortie').action = '/stock/' + btn.dataset.id + '/sortie';
});
document.getElementById('modalInventaire').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    this.querySelector('#inventaireNom').textContent = btn.dataset.nom;
    this.querySelector('#inventaireUnite').textContent = '(' + btn.dataset.unite + ')';
    this.querySelector('#inventaireTheorique').textContent = btn.dataset.stock + ' ' + btn.dataset.unite;
    this.querySelector('#quantitePhysique').value = btn.dataset.stock;
    document.getElementById('formInventaire').action = '/stock/' + btn.dataset.id + '/inventaire';
});
document.getElementById('modalSeuil').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    this.querySelector('#seuilNom').textContent = btn.dataset.nom;
    this.querySelector('#seuilAlerte').value = btn.dataset.seuil;
    document.getElementById('formSeuil').action = '/stock/' + btn.dataset.id + '/seuil';
});
</script>
@endpush

