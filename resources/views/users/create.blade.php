{{--
    Vue : resources/views/users/create.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title','Nouvel utilisateur')
@section('page-title','Nouvel utilisateur')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="table-card p-4">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Rôle <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">— Choisir un rôle —</option>
                        <option value="admin"                   {{ old('role')==='admin'                   ? 'selected':'' }}>Administrateur</option>
                        <option value="agent_commercial"        {{ old('role')==='agent_commercial'        ? 'selected':'' }}>Agent Commercial</option>
                        <option value="gestionnaire_stock"      {{ old('role')==='gestionnaire_stock'      ? 'selected':'' }}>Gestionnaire de Stock</option>
                        <option value="responsable_production"  {{ old('role')==='responsable_production'  ? 'selected':'' }}>Responsable de Production</option>
                        <option value="livreur"                 {{ old('role')==='livreur'                 ? 'selected':'' }}>Livreur</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-orange">
                        <i class="bi bi-save me-1"></i>Créer l'utilisateur
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
