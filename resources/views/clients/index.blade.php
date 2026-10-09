{{--
    Vue : resources/views/clients/index.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title', 'Clients')
@section('page-title', 'Clients')

@section('topbar-actions')
    <a href="{{ route('clients.create') }}" class="btn btn-orange btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nouveau client
    </a>
@endsection

@section('content')
<div class="table-card">
    <div class="p-3 border-bottom d-flex gap-2 align-items-center">
        <i class="bi bi-people text-primary fs-5"></i>
        <span class="fw-bold">Liste des clients</span>
        <span class="badge bg-secondary ms-1">{{ $clients->total() }}</span>
    </div>
    <form method="GET" action="{{ route('clients.index') }}" class="p-3 border-bottom">
        <div class="row g-2 align-items-end">
            <div class="col-lg-5">
                <label for="rechercheClient" class="form-label small fw-semibold">Recherche</label>
                <input id="rechercheClient" type="search" name="recherche" class="form-control"
                       value="{{ request('recherche') }}" placeholder="Nom, code, téléphone, email, CNI ou adresse">
            </div>
            <div class="col-sm-6 col-lg-3">
                <label for="typeClient" class="form-label small fw-semibold">Type de client</label>
                <select id="typeClient" name="type_client" class="form-select">
                    <option value="">Tous les types</option>
                    <option value="Particulier" @selected(request('type_client') === 'Particulier')>Particulier</option>
                    <option value="Societe" @selected(request('type_client') === 'Societe')>Société</option>
                </select>
            </div>
            <div class="col-sm-6 col-lg-2">
                <label for="avecCommandes" class="form-label small fw-semibold">Commandes</label>
                <select id="avecCommandes" name="avec_commandes" class="form-select">
                    <option value="">Toutes</option>
                    <option value="oui" @selected(request('avec_commandes') === 'oui')>Avec commandes</option>
                    <option value="non" @selected(request('avec_commandes') === 'non')>Sans commande</option>
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-orange flex-grow-1"><i class="bi bi-search me-1"></i>Filtrer</button>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary" title="Effacer les filtres"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Adresse</th>
                    <th>Commandes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td class="text-muted">{{ $client->idclient }}</td>
                    <td><strong>{{ $client->nom }} {{ $client->prenoms }}</strong></td>
                    <td>{{ $client->type_client ?? 'Particulier' }}</td>
                    <td>{{ $client->contact ?? '—' }}</td>
                    <td>{{ $client->email ?? '-' }}</td>
                    <td>{{ $client->adresse ?? '—' }}</td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $client->commandes_count }}</span>
                    </td>
                    <td>
                        <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-light" title="Voir">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-light" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('clients.destroy', $client) }}" class="d-inline"
                              onsubmit="return confirm('Supprimer ce client ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-people fs-3 d-block mb-2"></i>Aucun client enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clients->hasPages())
    <div class="p-3 border-top">{{ $clients->links() }}</div>
    @endif
</div>
@endsection
