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

<div class="table-card">
    <div class="p-3 border-bottom d-flex gap-2 align-items-center">
        <i class="bi bi-boxes text-primary fs-5"></i>
        <span class="fw-bold">État des stocks</span>
        <span class="badge bg-secondary">{{ $stocks->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Type</th>
                    <th>Quantité disponible</th>
                    <th>Seuil alerte</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                <tr class="{{ $stock->estEnRupture() ? 'table-danger' : '' }}">
                    <td><strong>{{ $stock->produit->nom }}</strong></td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            {{ $stock->produit->type_libelle }}
                        </span>
                    </td>
                    <td>
                        <span class="fw-bold {{ $stock->estEnRupture() ? 'text-danger' : 'text-success' }}">
                            {{ $stock->quantite_disponible }} {{ $stock->produit->unite }}
                        </span>
                    </td>
                    <td>{{ $stock->seuil_alerte }} {{ $stock->produit->unite }}</td>
                    <td>
                        @if($stock->estEnRupture())
                            <span class="badge bg-danger rupture-badge">⚠ Rupture</span>
                        @else
                            <span class="badge bg-success">OK</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-success" title="Entrée"
                                    data-bs-toggle="modal" data-bs-target="#modalEntree"
                                    data-id="{{ $stock->idstock }}"
                                    data-nom="{{ $stock->produit->nom }}"
                                    data-unite="{{ $stock->produit->unite }}">
                                <i class="bi bi-arrow-down-circle"></i> Entrée
                            </button>
                            <button class="btn btn-sm btn-warning" title="Sortie"
                                    data-bs-toggle="modal" data-bs-target="#modalSortie"
                                    data-id="{{ $stock->idstock }}"
                                    data-nom="{{ $stock->produit->nom }}"
                                    data-unite="{{ $stock->produit->unite }}">
                                <i class="bi bi-arrow-up-circle"></i> Sortie
                            </button>
                            <a href="{{ route('stock.mouvements', $stock) }}" class="btn btn-sm btn-light" title="Historique">
                                <i class="bi bi-clock-history"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Aucun stock</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stocks->hasPages())
    <div class="p-3 border-top">{{ $stocks->links() }}</div>
    @endif
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
                        <input type="number" name="quantite" class="form-control" step="0.01" min="0.01" required>
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
                        <input type="number" name="quantite" class="form-control" step="0.01" min="0.01" required>
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
</script>
@endpush
