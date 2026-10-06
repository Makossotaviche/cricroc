{{--
    Vue : resources/views/factures/index.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Factures')
@section('page-title','Factures')

@section('content')
<div class="table-card">
    <div class="p-3 border-bottom d-flex gap-2 align-items-center">
        <i class="bi bi-receipt text-primary fs-5"></i>
        <span class="fw-bold">Toutes les factures</span>
        <span class="badge bg-secondary">{{ $factures->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Client</th><th>Date</th><th>Montant</th><th>Paiement</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($factures as $f)
                <tr>
                    <td class="text-muted">#{{ $f->idfacture }}</td>
                    <td><strong>{{ $f->commande->client->nom }}</strong></td>
                    <td>{{ $f->date_facture->format('d/m/Y') }}</td>
                    <td>{{ number_format($f->montant_total,0,',',' ') }} FCFA</td>
                    <td>
                        @if($f->paiement)
                            <span class="badge" style="background:#D1FAE5;color:#065F46;border-radius:6px;">
                                <i class="bi bi-check-circle me-1"></i>Payée
                            </span>
                        @else
                            <span class="badge" style="background:#FEF3C7;color:#92400E;border-radius:6px;">
                                En attente
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('factures.show', $f) }}" class="btn btn-sm btn-light">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('factures.imprimer', $f) }}" class="btn btn-sm btn-light" target="_blank">
                            <i class="bi bi-printer"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Aucune facture</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($factures->hasPages())
    <div class="p-3 border-top">{{ $factures->links() }}</div>
    @endif
</div>
@endsection
