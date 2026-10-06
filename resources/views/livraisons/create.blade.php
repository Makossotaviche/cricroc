{{--
    Vue : resources/views/livraisons/create.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Nouvelle livraison')
@section('page-title','Créer une livraison')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('livraisons.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Commande validée <span class="text-danger">*</span></label>
                    <select name="idcommande" class="form-select @error('idcommande') is-invalid @enderror" required>
                        <option value="">— Choisir une commande —</option>
                        @foreach($commandes as $cmd)
                            <option value="{{ $cmd->idcommande }}" {{ old('idcommande') == $cmd->idcommande ? 'selected' : '' }}>
                                #{{ $cmd->idcommande }} — {{ $cmd->client->nom }}
                                ({{ number_format($cmd->montant_total,0,',',' ') }} FCFA)
                            </option>
                        @endforeach
                    </select>
                    @error('idcommande')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Assigner un livreur</label>
                    <select name="idlivreur" class="form-select">
                        <option value="">— Non assigné —</option>
                        @foreach($livreurs as $l)
                            <option value="{{ $l->id }}" {{ old('idlivreur') == $l->id ? 'selected' : '' }}>
                                {{ $l->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-orange">
                        <i class="bi bi-save me-1"></i>Créer la livraison
                    </button>
                    <a href="{{ route('livraisons.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
