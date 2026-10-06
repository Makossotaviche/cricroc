{{--
    Vue : resources/views/clients/show.blade.php
    Cette vue affiche les donnees de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title', $client->nom)
@section('page-title', $client->nom)

@section('topbar-actions')
    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-pencil me-1"></i>Modifier
    </a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="table-card p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold fs-4"
                     style="width:60px;height:60px;background:#E8650A;">
                    {{ strtoupper(substr($client->nom, 0, 1)) }}
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">{{ $client->nom }}</h5>
                    <small class="text-muted">{{ $client->code_client ?? 'CLT-' . str_pad($client->idclient, 6, '0', STR_PAD_LEFT) }}</small>
                </div>
            </div>

            <ul class="list-unstyled mb-0">
                <li class="mb-2"><i class="bi bi-person-badge me-2 text-muted"></i>{{ $client->type_client ?? 'Particulier' }}</li>
                @if($client->prenoms)<li class="mb-2"><i class="bi bi-person me-2 text-muted"></i>{{ $client->prenoms }}</li>@endif
                @if($client->raison_sociale)<li class="mb-2"><i class="bi bi-building me-2 text-muted"></i>{{ $client->raison_sociale }}</li>@endif
                <li class="mb-2"><i class="bi bi-telephone me-2 text-muted"></i>{{ $client->contact ?? 'Non renseigne' }}</li>
                @if($client->telephone_secondaire)<li class="mb-2"><i class="bi bi-telephone-plus me-2 text-muted"></i>{{ $client->telephone_secondaire }}</li>@endif
                @if($client->email)<li class="mb-2"><i class="bi bi-envelope me-2 text-muted"></i>{{ $client->email }}</li>@endif
                @if($client->whatsapp)<li class="mb-2"><i class="bi bi-whatsapp me-2 text-muted"></i>{{ $client->whatsapp }}</li>@endif
                <li class="mb-2"><i class="bi bi-geo-alt me-2 text-muted"></i>{{ $client->adresse ?? 'Non renseignee' }}</li>
                @if($client->ville || $client->commune || $client->pays)
                    <li><i class="bi bi-buildings me-2 text-muted"></i>{{ collect([$client->commune, $client->ville, $client->pays])->filter()->join(', ') }}</li>
                @endif
            </ul>
        </div>

        <div class="table-card p-4 mt-4">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-info-circle me-2 text-muted"></i>Informations complementaires
            </h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-2"><span class="text-muted">Categorie :</span> <span class="fw-semibold">{{ $client->categorie ?? 'Non renseignee' }}</span></li>
                <li class="mb-2"><span class="text-muted">Limite credit :</span> <span class="fw-semibold">{{ $client->limite_credit ? number_format($client->limite_credit, 0, ',', ' ') . ' FCFA' : 'Non renseignee' }}</span></li>
                <li class="mb-2"><span class="text-muted">Paiement :</span> <span class="fw-semibold">{{ $client->condition_paiement ?? 'Non renseigne' }}</span></li>
                <li><span class="text-muted">Remise :</span> <span class="fw-semibold">{{ $client->remise_defaut ?? 0 }}%</span></li>
            </ul>
            @if($client->notes)
                <div class="border-top mt-3 pt-3 small text-muted">{{ $client->notes }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-8">
        <div class="table-card">
            <div class="p-3 border-bottom fw-bold">
                <i class="bi bi-clock-history me-2"></i>Historique des commandes
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr><th>#</th><th>Date</th><th>Montant</th><th>Statut</th><th>Facture</th></tr>
                    </thead>
                    <tbody>
                        @forelse($client->commandes as $cmd)
                        <tr>
                            <td><a href="{{ route('commandes.show', $cmd) }}">#{{ $cmd->idcommande }}</a></td>
                            <td>{{ $cmd->date_commande->format('d/m/Y') }}</td>
                            <td>{{ number_format($cmd->montant_total, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <span class="badge badge-{{ $cmd->statut }} px-2 py-1" style="border-radius:6px;">
                                    {{ $cmd->statut_libelle }}
                                </span>
                            </td>
                            <td>
                                @if($cmd->facture)
                                    <a href="{{ route('factures.show', $cmd->facture) }}" class="btn btn-sm btn-light">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Aucune commande</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
