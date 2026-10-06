{{--
    Vue : resources/views/clients/create.blade.php
    Cette vue affiche les donnees de l'application Cricroc.
--}}

@extends('layouts.app')
@section('title', 'Nouveau client')
@section('page-title', 'Nouveau client')

@push('styles')
<style>
    .client-create-shell {
        --form-accent: var(--cc-orange);
        --form-accent-light: var(--cc-orange-light);
        --form-ink: var(--cc-dark);
        --form-muted: #6c757d;
        --form-line: #e7ebf3;
    }

    .client-hero {
        background: linear-gradient(135deg, var(--cc-dark) 0%, #242445 58%, var(--form-accent) 100%);
        border-radius: 14px;
        color: #fff;
        overflow: hidden;
        box-shadow: 0 14px 35px rgba(26, 26, 46, .18);
    }

    .client-hero-brand {
        border-right: 1px solid rgba(255, 255, 255, .22);
    }

    .client-logo-mark,
    .client-title-icon,
    .client-section-icon {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .client-logo-mark {
        background: #fff;
        color: var(--form-accent);
        font-size: 1.7rem;
        box-shadow: 0 8px 22px rgba(0, 0, 0, .18);
    }

    .client-title-icon {
        background: rgba(255, 255, 255, .16);
        font-size: 1.55rem;
    }

    .client-hero h2 {
        font-size: clamp(1.45rem, 2.4vw, 2.2rem);
        letter-spacing: 0;
    }

    .client-form-wrap {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 10px 35px rgba(26, 26, 46, .1);
        overflow: hidden;
    }

    .client-side-panel {
        background:
            linear-gradient(180deg, rgba(232, 101, 10, .12), rgba(26, 26, 46, .04)),
            #fff;
        border-right: 1px solid var(--form-line);
    }

    .client-clipboard {
        width: min(190px, 70%);
        aspect-ratio: 4 / 5;
        border-radius: 12px;
        background: #fff;
        border: 1px solid rgba(26, 26, 46, .12);
        box-shadow: 0 18px 35px rgba(26, 26, 46, .16);
        position: relative;
    }

    .client-clipboard::before {
        content: "";
        position: absolute;
        width: 48%;
        height: 26px;
        top: -12px;
        left: 26%;
        border-radius: 8px 8px 4px 4px;
        background: var(--form-accent);
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .25);
    }

    .client-avatar {
        width: 66px;
        height: 66px;
        border-radius: 50%;
        background: rgba(232, 101, 10, .14);
        color: var(--form-accent);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-top: 46px;
    }

    .client-paper-line {
        width: 66%;
        height: 5px;
        border-radius: 50px;
        background: #d7ddea;
        margin: 12px auto 0;
    }

    .client-help-card {
        background: rgba(255, 255, 255, .82);
        border: 1px solid rgba(26, 26, 46, .08);
        border-radius: 14px;
    }

    .client-store-visual {
        width: min(210px, 78%);
        aspect-ratio: 1.25 / 1;
        border-radius: 12px;
        margin-inline: auto;
        background:
            linear-gradient(90deg, rgba(232, 101, 10, .9) 0 18%, transparent 18% 100%),
            linear-gradient(180deg, #fff 0 35%, #eef2f8 35% 100%);
        border: 1px solid rgba(26, 26, 46, .08);
        box-shadow: 0 14px 28px rgba(26, 26, 46, .1);
        position: relative;
    }

    .client-store-visual::before {
        content: "";
        position: absolute;
        left: 16%;
        right: 12%;
        top: 24%;
        height: 28px;
        border-radius: 0 0 18px 18px;
        background: repeating-linear-gradient(90deg, var(--form-accent) 0 28px, #fff 28px 56px);
        box-shadow: 0 8px 18px rgba(26, 26, 46, .12);
    }

    .client-form-main {
        background: linear-gradient(180deg, #fff 0%, #fbfcff 100%);
    }

    .client-section {
        border-bottom: 1px solid var(--form-line);
        padding: 1.3rem 1.45rem;
    }

    .client-section:last-of-type {
        border-bottom: 0;
    }

    .client-section-heading {
        color: var(--form-ink);
        font-weight: 800;
        text-transform: uppercase;
        font-size: .85rem;
        letter-spacing: .3px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .client-section-icon {
        width: 40px;
        height: 40px;
        background: var(--form-accent);
        color: #fff;
        font-size: 1.15rem;
        box-shadow: 0 9px 18px rgba(232, 101, 10, .22);
    }

    .client-field .form-label {
        color: var(--form-ink);
        font-size: .83rem;
        margin-bottom: .35rem;
    }

    .client-input {
        border: 1px solid #d8dfec;
        border-radius: 10px;
        min-height: 48px;
        color: var(--form-ink);
        box-shadow: none;
    }

    .client-input:focus {
        border-color: var(--form-accent);
        box-shadow: 0 0 0 .18rem rgba(232, 101, 10, .13);
    }

    .client-input-group .input-group-text {
        border: 1px solid #d8dfec;
        background: #fff;
        color: var(--form-accent);
        border-radius: 10px 0 0 10px;
        min-width: 46px;
        justify-content: center;
        font-size: 1.1rem;
    }

    .client-input-group .form-control,
    .client-input-group .form-select {
        border-left: 0;
        border-radius: 0 10px 10px 0;
    }

    .client-actions {
        background: #fff;
        border-top: 1px solid var(--form-line);
        padding: 1.2rem 1.45rem;
    }

    @media (max-width: 991.98px) {
        .client-hero-brand {
            border-right: 0;
            border-bottom: 1px solid rgba(255, 255, 255, .18);
        }

        .client-side-panel {
            border-right: 0;
            border-bottom: 1px solid var(--form-line);
        }
    }

    @media (max-width: 575.98px) {
        .client-section,
        .client-actions {
            padding-inline: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="client-create-shell">
    <div class="client-hero mb-4">
        <div class="row g-0 align-items-center">
            <div class="col-lg-4 client-hero-brand p-4">
                <div class="d-flex align-items-center gap-3">
                    <span class="client-logo-mark"><i class="bi bi-cart3"></i></span>
                    <div>
                        <div class="fw-black h2 mb-0" style="font-weight:900;">CRIC <span style="color:var(--form-accent-light);">CROC</span></div>
                        <div class="small opacity-75">Gestion de facturation & stock</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 p-4">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="client-title-icon"><i class="bi bi-person-fill"></i></span>
                        <div>
                            <h2 class="fw-bold mb-1">Nouveau client</h2>
                            <div class="opacity-75">Enregistrez les informations du client</div>
                        </div>
                    </div>
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-light" title="Fermer">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="client-form-wrap">
        <div class="row g-0">
            <aside class="col-lg-3 client-side-panel p-4 d-flex flex-column justify-content-between gap-4">
                <div class="text-center">
                    <div class="client-clipboard mx-auto">
                        <span class="client-avatar"><i class="bi bi-person-fill"></i></span>
                        <div class="client-paper-line"></div>
                        <div class="client-paper-line" style="width:72%;"></div>
                        <div class="client-paper-line" style="width:52%;"></div>
                    </div>
                </div>

                <div class="client-help-card p-3">
                    <div class="d-flex align-items-center gap-2 fw-bold mb-2" style="color:var(--form-ink);">
                        <i class="bi bi-info-circle-fill text-warning fs-4"></i>
                        Informations
                    </div>
                    <p class="mb-0 text-muted small">Veuillez remplir tous les champs obligatoires marques d'un <span class="text-danger fw-bold">*</span>.</p>
                </div>

                <div class="client-store-visual d-none d-lg-block"></div>
            </aside>

            <div class="col-lg-9 client-form-main">
                <form method="POST" action="{{ route('clients.store') }}">
                    @csrf

                    <section class="client-section">
                        <div class="client-section-heading">
                            <span class="client-section-icon"><i class="bi bi-person-badge"></i></span>
                            Identite du client
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Type de client <span class="text-danger">*</span></label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                    <select class="form-select client-input" name="type_client" aria-label="Type de client">
                                        <option value="Particulier" @selected(old('type_client', 'Particulier') === 'Particulier')>Particulier</option>
                                        <option value="Societe" @selected(old('type_client') === 'Societe')>Societe</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Nom du client <span class="text-danger">*</span></label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                    <input type="text" name="nom" class="form-control client-input @error('nom') is-invalid @enderror"
                                           value="{{ old('nom') }}" placeholder="Entrez le nom du client" required>
                                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Prenom(s)</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control client-input" name="prenoms"
                                           value="{{ old('prenoms') }}" placeholder="Entrez le prenom(s)">
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Raison sociale (pour societe)</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <input type="text" class="form-control client-input" name="raison_sociale"
                                           value="{{ old('raison_sociale') }}" placeholder="Entrez la raison sociale">
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Code client <span class="text-danger">*</span></label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                    <input type="text" name="code_client" class="form-control client-input @error('code_client') is-invalid @enderror"
                                           value="{{ old('code_client', $nextCode ?? 'CLT-AUTO') }}" readonly>
                                    @error('code_client')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Date d'enregistrement <span class="text-danger">*</span></label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                    <input type="date" name="date_enregistrement" class="form-control client-input @error('date_enregistrement') is-invalid @enderror"
                                           value="{{ old('date_enregistrement', date('Y-m-d')) }}">
                                    @error('date_enregistrement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="client-section">
                        <div class="client-section-heading">
                            <span class="client-section-icon"><i class="bi bi-telephone-fill"></i></span>
                            Contact
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Telephone <span class="text-danger">*</span></label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                    <input type="text" name="contact" class="form-control client-input @error('contact') is-invalid @enderror"
                                           value="{{ old('contact') }}" placeholder="+225 07 12 34 56 78" required>
                                    @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Telephone secondaire</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control client-input" name="telephone_secondaire"
                                           value="{{ old('telephone_secondaire') }}" placeholder="+225 01 23 45 67 89">
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Email</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                    <input type="email" class="form-control client-input" name="email"
                                           value="{{ old('email') }}" placeholder="exemple@email.com">
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">WhatsApp</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" class="form-control client-input" name="whatsapp"
                                           value="{{ old('whatsapp') }}" placeholder="+225 07 12 34 56 78">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="client-section">
                        <div class="client-section-heading">
                            <span class="client-section-icon"><i class="bi bi-geo-alt-fill"></i></span>
                            Adresse
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Pays <span class="text-danger">*</span></label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-flag-fill"></i></span>
                                    <select class="form-select client-input" name="pays">
                                        <option @selected(old('pays', "Cote d'Ivoire") === "Cote d'Ivoire")>Cote d'Ivoire</option>
                                        <option @selected(old('pays') === 'Senegal')>Senegal</option>
                                        <option @selected(old('pays') === 'Mali')>Mali</option>
                                        <option @selected(old('pays') === 'Burkina Faso')>Burkina Faso</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-buildings-fill"></i></span>
                                    <select class="form-select client-input" name="ville">
                                        <option @selected(old('ville', 'Abidjan') === 'Abidjan')>Abidjan</option>
                                        <option @selected(old('ville') === 'Bouake')>Bouake</option>
                                        <option @selected(old('ville') === 'Yamoussoukro')>Yamoussoukro</option>
                                        <option @selected(old('ville') === 'San Pedro')>San Pedro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Commune / Quartier</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-buildings"></i></span>
                                    <input type="text" class="form-control client-input" name="commune"
                                           value="{{ old('commune') }}" placeholder="Cocody / Angre">
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Adresse complete</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-map-fill"></i></span>
                                    <input type="text" name="adresse" class="form-control client-input @error('adresse') is-invalid @enderror"
                                           value="{{ old('adresse') }}" placeholder="Entrez l'adresse complete du client">
                                    @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="client-section">
                        <div class="client-section-heading">
                            <span class="client-section-icon"><i class="bi bi-info-circle-fill"></i></span>
                            Informations complementaires
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Groupe / Categorie</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
                                    <select class="form-select client-input" name="categorie">
                                        <option @selected(old('categorie', 'Client regulier') === 'Client regulier')>Client regulier</option>
                                        <option @selected(old('categorie') === 'Client VIP')>Client VIP</option>
                                        <option @selected(old('categorie') === 'Nouveau prospect')>Nouveau prospect</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Limite de credit (FCFA)</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                                    <input type="number" class="form-control client-input" name="limite_credit"
                                           value="{{ old('limite_credit', 500000) }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Condition de paiement</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar2-week-fill"></i></span>
                                    <select class="form-select client-input" name="condition_paiement">
                                        <option @selected(old('condition_paiement', 'Comptant') === 'Comptant')>Comptant</option>
                                        <option @selected(old('condition_paiement') === '7 jours')>7 jours</option>
                                        <option @selected(old('condition_paiement') === '15 jours')>15 jours</option>
                                        <option @selected(old('condition_paiement') === '30 jours')>30 jours</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 client-field">
                                <label class="form-label fw-semibold">Remise par defaut (%)</label>
                                <div class="input-group client-input-group">
                                    <span class="input-group-text"><i class="bi bi-percent"></i></span>
                                    <input type="number" class="form-control client-input" name="remise_defaut"
                                           value="{{ old('remise_defaut', 0) }}" min="0" max="100">
                                </div>
                            </div>
                            <div class="col-12 client-field">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea class="form-control client-input" name="notes" rows="3" placeholder="Ajoutez une note (optionnel)">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </section>

                    <div class="client-actions d-flex flex-column flex-md-row justify-content-end gap-2">
                        <button type="reset" class="btn btn-light border">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reinitialiser
                        </button>
                        <a href="{{ route('clients.index') }}" class="btn btn-light border">
                            <i class="bi bi-x-lg me-1"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-orange px-4">
                            <i class="bi bi-save me-1"></i>Enregistrer le client
                            <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
