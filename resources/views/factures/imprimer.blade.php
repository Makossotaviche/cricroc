{{--
    Vue : resources/views/factures/imprimer.blade.php
    Cette vue affiche les données de l'application Cricroc.
--}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $facture->idfacture }} — Cric Croc</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; font-size:13px; color:#222; padding:30px; }
        .header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:30px; }
        .logo { font-size:2rem; font-weight:900; color:#E8650A; letter-spacing:2px; }
        .company-info { font-size:.8rem; color:#555; margin-top:4px; }
        .facture-title { text-align:right; }
        .facture-title h2 { font-size:1.6rem; color:#1A1A2E; }
        .facture-title .num { color:#E8650A; font-size:1.1rem; font-weight:700; }
        .divider { border:none; border-top:2px solid #E8650A; margin:15px 0; }
        .parties { display:flex; gap:40px; margin-bottom:25px; }
        .partie { flex:1; }
        .partie h4 { font-size:.75rem; text-transform:uppercase; letter-spacing:1px; color:#888; margin-bottom:6px; }
        .partie p { font-size:.9rem; line-height:1.6; }
        table { width:100%; border-collapse:collapse; margin-bottom:20px; }
        thead th { background:#1A1A2E; color:#fff; padding:8px 10px; text-align:left; font-size:.8rem; text-transform:uppercase; }
        tbody td { padding:8px 10px; border-bottom:1px solid #eee; }
        tbody tr:nth-child(even) { background:#F8F9FA; }
        tfoot td { padding:8px 10px; font-weight:700; }
        .total-row { background:#FFF3E0 !important; color:#E8650A; font-size:1rem; }
        .footer-note { font-size:.78rem; color:#888; text-align:center; margin-top:30px; border-top:1px solid #eee; padding-top:10px; }
        .badge-paid { display:inline-block; background:#D1FAE5; color:#065F46; padding:3px 10px; border-radius:20px; font-size:.8rem; font-weight:600; }
        .badge-unpaid { display:inline-block; background:#FEF3C7; color:#92400E; padding:3px 10px; border-radius:20px; font-size:.8rem; font-weight:600; }
        @media print {
            body { padding:15px; }
            .no-print { display:none !important; }
        }
    </style>
</head>
<body>

<div class="no-print" style="text-align:right;margin-bottom:15px;">
    <button onclick="window.print()" style="background:#E8650A;color:#fff;border:none;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9rem;">
        🖨 Imprimer
    </button>
</div>

<div class="header">
    <div>
        <div class="logo">CRIC CROC</div>
        <div class="company-info">
            Pointe-Noire, République du Congo<br>
            Tél : +242 06 XXX XXXX<br>
            Email : contact@cricroc.cg
        </div>
    </div>
    <div class="facture-title">
        <h2>FACTURE</h2>
        <div class="num">#{{ str_pad($facture->idfacture, 6, '0', STR_PAD_LEFT) }}</div>
        <div style="color:#888;font-size:.85rem;margin-top:4px;">
            Date : {{ $facture->date_facture->format('d/m/Y') }}
        </div>
        <div style="margin-top:6px;">
            @if($facture->paiement)
                <span class="badge-paid">✔ Payée</span>
            @else
                <span class="badge-unpaid">⏳ En attente de paiement</span>
            @endif
        </div>
    </div>
</div>

<hr class="divider">

<div class="parties">
    <div class="partie">
        <h4>Émetteur</h4>
        <p>
            <strong>Cric Croc SARL</strong><br>
            Pointe-Noire, Congo<br>
            RCCM : XXX-XXX-XXXX
        </p>
    </div>
    <div class="partie">
        <h4>Facturé à</h4>
        <p>
            <strong>{{ $facture->commande->client->nom }}</strong><br>
            {{ $facture->commande->client->adresse ?? '' }}<br>
            {{ $facture->commande->client->contact ?? '' }}
        </p>
    </div>
    <div class="partie">
        <h4>Commande</h4>
        <p>
            Réf. commande : <strong>#{{ $facture->commande->idcommande }}</strong><br>
            Date commande : {{ $facture->commande->date_commande->format('d/m/Y') }}
        </p>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Sous-total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($facture->commande->lignes as $ligne)
        <tr>
            <td>{{ $ligne->produit->nom }}</td>
            <td>{{ $ligne->quantite }} {{ $ligne->produit->unite }}</td>
            <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
            <td><strong>{{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA</strong></td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="3" style="text-align:right;text-transform:uppercase;letter-spacing:1px;">Total TTC</td>
            <td style="font-size:1.1rem;">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
        </tr>
    </tfoot>
</table>

@if($facture->paiement)
<div style="background:#F0FDF4;border-left:4px solid #22C55E;padding:10px 15px;border-radius:6px;margin-bottom:20px;">
    <strong>Paiement reçu le {{ $facture->paiement->date_paiement->format('d/m/Y') }}</strong> —
    {{ number_format($facture->paiement->montant,0,',',' ') }} FCFA
    ({{ ucfirst(str_replace('_',' ',$facture->paiement->mode)) }})
</div>
@else
<div style="background:#FFFBEB;border-left:4px solid #F59E0B;padding:10px 15px;border-radius:6px;margin-bottom:20px;">
    <strong>Paiement attendu sous 30 jours.</strong> Merci de régler à la livraison ou par Mobile Money.
</div>
@endif

<div class="footer-note">
    Cric Croc — Spécialiste de la fabrication de chips artisanales au Congo.<br>
    Merci de votre confiance !
</div>
</body>
</html>
