{{--
    Vue : resources/views/production/index.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Productions')
@section('page-title','Productions')

@section('topbar-actions')
    <a href="{{ route('production.create') }}" class="btn btn-orange btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Nouvelle production
    </a>
@endsection

@section('content')
<div class="table-card">
    <div class="p-3 border-bottom d-flex gap-2 align-items-center">
        <i class="bi bi-gear-wide-connected text-warning fs-5"></i>
        <span class="fw-bold">Historique des productions</span>
        <span class="badge bg-secondary">{{ $productions->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>#</th><th>Date</th><th>Produit fini</th><th>Quantité produite</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($productions as $p)
                <tr>
                    <td class="text-muted">#{{ $p->idproduction }}</td>
                    <td>{{ $p->date_production->format('d/m/Y') }}</td>
                    <td><strong>{{ $p->produit->nom }}</strong></td>
                    <td>{{ number_format($p->quantite_produite, 0, '.', '') }} {{ $p->produit->unite }}</td>
                    <td>
                        <a href="{{ route('production.show', $p) }}" class="btn btn-sm btn-light">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-gear fs-3 d-block mb-2"></i>Aucune production enregistrée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($productions->hasPages())
    <div class="p-3 border-top">{{ $productions->links() }}</div>
    @endif
</div>
@endsection
