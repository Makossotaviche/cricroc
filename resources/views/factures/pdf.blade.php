<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        @page { margin: 34px 38px 48px; }
        body { font-family: DejaVu Sans, sans-serif; color: #202633; font-size: 11px; line-height: 1.5; }
        .brand { color: #e8650a; font-size: 23px; font-weight: bold; letter-spacing: 1px; }
        .muted { color: #687386; }
        .header { width: 100%; border-bottom: 3px solid #e8650a; padding-bottom: 15px; margin-bottom: 22px; }
        .header td { vertical-align: top; }
        .title { text-align: right; font-size: 23px; font-weight: bold; }
        .number { color: #e8650a; font-size: 13px; font-weight: bold; }
        .parties { width: 100%; margin: 0 0 22px; }
        .parties td { width: 33%; vertical-align: top; padding-right: 12px; }
        .label { color: #687386; font-size: 9px; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        table.items { width: 100%; border-collapse: collapse; }
        .items th { background: #202633; color: white; padding: 9px 8px; text-align: left; }
        .items td { border-bottom: 1px solid #e5e8ed; padding: 8px; }
        .right { text-align: right; }
        .summary { width: 48%; margin: 14px 0 0 auto; border-collapse: collapse; }
        .summary td { padding: 5px 7px; }
        .summary tr:last-child { background: #fff1e6; color: #c75000; font-size: 13px; font-weight: bold; }
        .payments { margin-top: 25px; }
        .payments table { width: 100%; border-collapse: collapse; }
        .payments td, .payments th { padding: 6px; border-bottom: 1px solid #e5e8ed; text-align: left; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; border-top: 1px solid #dfe3e9; padding-top: 8px; text-align: center; color: #687386; font-size: 9px; }
    </style>
</head>
<body>
    <table class="header"><tr>
        <td><div class="brand">CRIC CROC</div><div class="muted">Pointe-Noire, République du Congo</div></td>
        <td class="title">FACTURE<br><span class="number">{{ $facture->numero_facture }}</span><br><span class="muted" style="font-size:10px;font-weight:normal">Date : {{ $facture->date_facture->format('d/m/Y') }}</span></td>
    </tr></table>

    <table class="parties"><tr>
        <td><div class="label">Émetteur</div><strong>Cric Croc</strong><br>Pointe-Noire, Congo</td>
        <td><div class="label">Facturé à</div><strong>{{ $facture->commande->client->nom }}</strong><br>{{ $facture->commande->client->adresse ?? '' }}<br>{{ $facture->commande->client->contact ?? '' }}</td>
        <td><div class="label">Commande</div>Référence : <strong>#{{ $facture->commande->idcommande }}</strong><br>Date : {{ $facture->commande->date_commande->format('d/m/Y') }}<br>Statut : {{ $facture->estPayee() ? 'Payée' : ($facture->montantPaye() > 0 ? 'Partiellement payée' : 'À payer') }}</td>
    </tr></table>

    <table class="items">
        <thead><tr><th>Produit</th><th>Quantité</th><th class="right">Prix unitaire</th><th class="right">Montant</th></tr></thead>
        <tbody>
        @foreach($facture->commande->lignes as $ligne)
            <tr><td>{{ $ligne->produit->nom }}</td><td>{{ number_format($ligne->quantite, 0, ',', ' ') }} {{ $ligne->produit->unite }}</td><td class="right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td><td class="right">{{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA</td></tr>
        @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr><td>Total HT</td><td class="right">{{ number_format($facture->montant_ht ?? $facture->montant_total, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td>TVA ({{ number_format($facture->taux_tva ?? 0, 2, ',', ' ') }} %)</td><td class="right">{{ number_format($facture->montant_tva ?? 0, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td>Total TTC</td><td class="right">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td></tr>
        <tr><td>Déjà payé</td><td class="right">{{ number_format($facture->montantPaye(), 0, ',', ' ') }} FCFA</td></tr>
        <tr><td>Reste à payer</td><td class="right">{{ number_format($facture->resteAPayer(), 0, ',', ' ') }} FCFA</td></tr>
    </table>

    @if($facture->paiements->isNotEmpty())
    <div class="payments"><div class="label">Historique des paiements</div><table>
        <thead><tr><th>Date</th><th>Mode</th><th class="right">Montant</th></tr></thead>
        <tbody>@foreach($facture->paiements as $paiement)<tr><td>{{ $paiement->date_paiement->format('d/m/Y') }}</td><td>{{ ucfirst(str_replace('_', ' ', $paiement->mode)) }}</td><td class="right">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td></tr>@endforeach</tbody>
    </table></div>
    @endif
    <div class="footer">Cric Croc · Facture {{ $facture->numero_facture }}</div>
</body>
</html>
