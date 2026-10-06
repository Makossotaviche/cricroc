{{--
    Vue : resources/views/clients/edit.blade.php
    Cette vue affiche les donnees de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title', 'Modifier client')
@section('page-title', 'Modifier client')

@section('content')
<div class="table-card">
    <div class="p-3 border-bottom d-flex align-items-center gap-2">
        <i class="bi bi-person-gear text-warning fs-5"></i>
        <span class="fw-bold">Informations du client</span>
        <span class="badge bg-light text-dark border">{{ $client->code_client ?? 'CLT-' . str_pad($client->idclient, 6, '0', STR_PAD_LEFT) }}</span>
    </div>

    <form method="POST" action="{{ route('clients.update', $client) }}">
        @csrf @method('PUT')

        <div class="p-4 border-bottom">
            <div class="d-flex align-items-center gap-2 fw-bold mb-3">
                <span class="rounded d-inline-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:var(--cc-orange);">
                    <i class="bi bi-person-badge"></i>
                </span>
                Identite du client
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Type de client <span class="text-danger">*</span></label>
                    <select name="type_client" class="form-select @error('type_client') is-invalid @enderror" required>
                        <option value="Particulier" @selected(old('type_client', $client->type_client) === 'Particulier')>Particulier</option>
                        <option value="Societe" @selected(old('type_client', $client->type_client) === 'Societe')>Societe</option>
                    </select>
                    @error('type_client')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom', $client->nom) }}" required>
                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Prenom(s)</label>
                    <input type="text" name="prenoms" class="form-control @error('prenoms') is-invalid @enderror"
                           value="{{ old('prenoms', $client->prenoms) }}">
                    @error('prenoms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Raison sociale</label>
                    <input type="text" name="raison_sociale" class="form-control @error('raison_sociale') is-invalid @enderror"
                           value="{{ old('raison_sociale', $client->raison_sociale) }}">
                    @error('raison_sociale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Code client</label>
                    <input type="text" name="code_client" class="form-control @error('code_client') is-invalid @enderror"
                           value="{{ old('code_client', $client->code_client) }}" readonly>
                    @error('code_client')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date d'enregistrement</label>
                    <input type="date" name="date_enregistrement" class="form-control @error('date_enregistrement') is-invalid @enderror"
                           value="{{ old('date_enregistrement', optional($client->date_enregistrement)->format('Y-m-d')) }}">
                    @error('date_enregistrement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="p-4 border-bottom">
            <div class="d-flex align-items-center gap-2 fw-bold mb-3">
                <span class="rounded d-inline-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:var(--cc-orange);">
                    <i class="bi bi-telephone-fill"></i>
                </span>
                Contact
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Telephone <span class="text-danger">*</span></label>
                    <input type="text" name="contact" class="form-control @error('contact') is-invalid @enderror"
                           value="{{ old('contact', $client->contact) }}" required>
                    @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Telephone secondaire</label>
                    <input type="text" name="telephone_secondaire" class="form-control @error('telephone_secondaire') is-invalid @enderror"
                           value="{{ old('telephone_secondaire', $client->telephone_secondaire) }}">
                    @error('telephone_secondaire')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $client->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                           value="{{ old('whatsapp', $client->whatsapp) }}">
                    @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="p-4 border-bottom">
            <div class="d-flex align-items-center gap-2 fw-bold mb-3">
                <span class="rounded d-inline-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:var(--cc-orange);">
                    <i class="bi bi-geo-alt-fill"></i>
                </span>
                Adresse
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pays</label>
                    <input type="text" name="pays" class="form-control @error('pays') is-invalid @enderror"
                           value="{{ old('pays', $client->pays) }}">
                    @error('pays')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ville</label>
                    <input type="text" name="ville" class="form-control @error('ville') is-invalid @enderror"
                           value="{{ old('ville', $client->ville) }}">
                    @error('ville')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Commune / Quartier</label>
                    <input type="text" name="commune" class="form-control @error('commune') is-invalid @enderror"
                           value="{{ old('commune', $client->commune) }}">
                    @error('commune')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Adresse complete</label>
                    <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror"
                           value="{{ old('adresse', $client->adresse) }}">
                    @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="d-flex align-items-center gap-2 fw-bold mb-3">
                <span class="rounded d-inline-flex align-items-center justify-content-center text-white" style="width:34px;height:34px;background:var(--cc-orange);">
                    <i class="bi bi-info-circle-fill"></i>
                </span>
                Informations complementaires
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Groupe / Categorie</label>
                    <input type="text" name="categorie" class="form-control @error('categorie') is-invalid @enderror"
                           value="{{ old('categorie', $client->categorie) }}">
                    @error('categorie')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Limite de credit (FCFA)</label>
                    <input type="number" name="limite_credit" class="form-control @error('limite_credit') is-invalid @enderror"
                           value="{{ old('limite_credit', $client->limite_credit) }}" min="0">
                    @error('limite_credit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Condition de paiement</label>
                    <input type="text" name="condition_paiement" class="form-control @error('condition_paiement') is-invalid @enderror"
                           value="{{ old('condition_paiement', $client->condition_paiement) }}">
                    @error('condition_paiement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Remise par defaut (%)</label>
                    <input type="number" name="remise_defaut" class="form-control @error('remise_defaut') is-invalid @enderror"
                           value="{{ old('remise_defaut', $client->remise_defaut) }}" min="0" max="100">
                    @error('remise_defaut')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $client->notes) }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="p-3 border-top d-flex flex-column flex-md-row justify-content-end gap-2">
            <a href="{{ route('clients.index') }}" class="btn btn-light border">
                <i class="bi bi-x-lg me-1"></i>Annuler
            </a>
            <button type="submit" class="btn btn-orange">
                <i class="bi bi-save me-1"></i>Mettre a jour
            </button>
        </div>
    </form>
</div>
@endsection
