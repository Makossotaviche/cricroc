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
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Contact</th>
                    <th>Adresse</th>
                    <th>Commandes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td class="text-muted">{{ $client->idclient }}</td>
                    <td><strong>{{ $client->nom }}</strong></td>
                    <td>{{ $client->contact ?? '—' }}</td>
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
                    <td colspan="6" class="text-center text-muted py-4">
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
