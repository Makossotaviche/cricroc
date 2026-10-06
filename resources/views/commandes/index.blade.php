{{--
    Vue : resources/views/commandes/index.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title', 'Commandes')
@section('page-title', 'Commandes')

@section('topbar-actions')
    <a href="{{ route('commandes.create') }}" class="btn btn-orange btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nouvelle commande
    </a>
@endsection

@section('content')
<div class="table-card">
    <div class="p-3 border-bottom d-flex gap-2 align-items-center flex-wrap">
        <i class="bi bi-cart3 text-primary fs-5"></i>
        <span class="fw-bold">Toutes les commandes</span>
        <span class="badge bg-secondary">{{ $commandes->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Facture</th>
                    <th>Livraison</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commandes as $cmd)
                <tr>
                    <td class="text-muted">#{{ $cmd->idcommande }}</td>
                    <td><strong>{{ $cmd->client->nom }}</strong></td>
                    <td>{{ $cmd->date_commande->format('d/m/Y') }}</td>
                    <td>{{ number_format($cmd->montant_total,0,',',' ') }} FCFA</td>
                    <td>
                        <span class="badge badge-{{ $cmd->statut }} px-2 py-1" style="border-radius:6px;">
                            {{ $cmd->statut_libelle }}
                        </span>
                    </td>
                    <td>
                        @if($cmd->facture)
                            <a href="{{ route('factures.show', $cmd->facture) }}" class="text-success">
                                <i class="bi bi-receipt-cutoff"></i> #{{ $cmd->facture->idfacture }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($cmd->livraison)
                            <span class="badge badge-{{ $cmd->livraison->statut }} px-2 py-1" style="border-radius:6px;">
                                {{ $cmd->livraison->statut_libelle }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('commandes.show', $cmd) }}" class="btn btn-sm btn-light">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if($cmd->statut === 'en_attente')
                            <form method="POST" action="{{ route('commandes.valider', $cmd) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Valider"
                                        onclick="return confirm('Valider cette commande ?')">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('commandes.rejeter', $cmd) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-danger" title="Rejeter"
                                        onclick="return confirm('Rejeter cette commande ?')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-cart fs-3 d-block mb-2"></i>Aucune commande
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($commandes->hasPages())
    <div class="p-3 border-top">{{ $commandes->links() }}</div>
    @endif
</div>
@endsection
