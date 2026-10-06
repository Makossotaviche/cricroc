{{--
    Vue : resources/views/users/edit.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Modifier utilisateur')
@section('page-title','Modifier utilisateur')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nom complet</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Rôle</label>
                    <select name="role" class="form-select" required>
                        <option value="admin"                  {{ $user->role==='admin'                  ?'selected':'' }}>Administrateur</option>
                        <option value="agent_commercial"       {{ $user->role==='agent_commercial'       ?'selected':'' }}>Agent Commercial</option>
                        <option value="gestionnaire_stock"     {{ $user->role==='gestionnaire_stock'     ?'selected':'' }}>Gestionnaire de Stock</option>
                        <option value="responsable_production" {{ $user->role==='responsable_production' ?'selected':'' }}>Responsable de Production</option>
                        <option value="livreur"                {{ $user->role==='livreur'                ?'selected':'' }}>Livreur</option>
                    </select>
                </div>
                <hr>
                <p class="text-muted" style="font-size:.82rem;">Laissez vide pour ne pas changer le mot de passe.</p>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control" minlength="8">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirmer</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-orange">
                        <i class="bi bi-save me-1"></i>Mettre à jour
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
