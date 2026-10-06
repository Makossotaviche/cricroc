{{--
    Vue : resources/views/livraisons/index.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Livraisons')
@section('page-title','Livraisons')

@section('topbar-actions')
    @if(auth()->user()->isAgentCommercial())
    <a href="{{ route('livraisons.create') }}" class="btn btn-orange btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nouvelle livraison
    </a>
    @endif
@endsection

@section('content')
<div class="table-card">
    <div class="p-3 border-bottom d-flex gap-2 align-items-center">
        <i class="bi bi-truck text-primary fs-5"></i>
        <span class="fw-bold">Livraisons</span>
        <span class="badge bg-secondary">{{ $livraisons->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Client</th><th>Date livraison</th>
                    <th>Livreur</th><th>Statut</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($livraisons as $liv)
                <tr>
                    <td class="text-muted">#{{ $liv->idlivraison }}</td>
                    <td><strong>{{ $liv->commande->client->nom }}</strong></td>
                    <td>{{ $liv->date_livraison ? $liv->date_livraison->format('d/m/Y') : '—' }}</td>
                    <td>{{ $liv->livreur?->name ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $liv->statut }} px-2 py-1" style="border-radius:6px;">
                            {{ $liv->statut_libelle }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('livraisons.show', $liv) }}" class="btn btn-sm btn-light">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if($liv->statut !== 'livree')
                        <button class="btn btn-sm btn-success"
                                data-bs-toggle="modal" data-bs-target="#modalStatut"
                                data-id="{{ $liv->idlivraison }}"
                                data-statut="{{ $liv->statut }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Aucune livraison</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($livraisons->hasPages())
    <div class="p-3 border-top">{{ $livraisons->links() }}</div>
    @endif
</div>

{{-- Modal statut --}}
<div class="modal fade" id="modalStatut" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Mettre à jour le statut</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formStatut">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <select name="statut" class="form-select" required>
                        <option value="en_attente">En attente</option>
                        <option value="en_cours">En cours</option>
                        <option value="livree">Livrée</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-orange">Confirmer</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('modalStatut').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('formStatut').action = '/livraisons/' + btn.dataset.id + '/statut';
    this.querySelector('select[name=statut]').value = btn.dataset.statut;
});
</script>
@endpush
