{{--
    Vue : resources/views/production/show.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Production #'.$production->idproduction)
@section('page-title','Production #'.$production->idproduction)

@section('topbar-actions')
    <a href="{{ route('production.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3">Informations</h6>
            <dl class="row mb-0" style="font-size:.9rem;">
                <dt class="col-5 text-muted">Date</dt>
                <dd class="col-7">{{ $production->date_production->format('d/m/Y') }}</dd>
                <dt class="col-5 text-muted">Produit fini</dt>
                <dd class="col-7 fw-bold">{{ $production->produit->nom }}</dd>
                <dt class="col-5 text-muted">Qté produite</dt>
                <dd class="col-7 fw-bold" style="color:#E8650A;">
                    {{ number_format($production->quantite_produite, 0, '.', '') }} {{ $production->produit->unite }}
                </dd>
            </dl>
        </div>
    </div>
    <div class="col-md-8">
        <div class="table-card">
            <div class="p-3 border-bottom fw-bold">
                <i class="bi bi-list-check me-2"></i>Matières premières consommées
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Matière première</th><th>Quantité consommée</th></tr>
                    </thead>
                    <tbody>
                        @forelse($production->consommations as $c)
                        <tr>
                            <td>{{ $c->produit->nom }}</td>
                            <td>{{ number_format($c->quantite_consommee, 0, '.', '') }} {{ $c->produit->unite }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted py-3">Aucune consommation</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
