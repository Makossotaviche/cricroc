<?php

/**
 * Fichier : app/Http/Controllers/FactureController.php
 * Description : composant du projet Cricroc.
 */

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Paiement;
use App\Models\NotificationInterne;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FactureController extends Controller
{
    /**
     * Affiche la liste des factures.
     *
     * Les factures sont chargées avec la commande associée, le client et le
     * paiement pour fournir une vue complète du statut financier.
     */
    public function index(): View
    {
        $factures = Facture::with(['commande.client', 'paiement'])
            ->latest()
            ->paginate(15);

        return view('factures.index', compact('factures'));
    }

    /**
     * Affiche le détail d'une facture.
     *
     * On charge la commande, les lignes de commande, les produits et le paiement.
     */
    public function show(Facture $facture): View
    {
        $facture->load(['commande.client', 'commande.lignes.produit', 'paiement']);
        return view('factures.show', compact('facture'));
    }

    /**
     * Enregistre le paiement d'une facture.
     *
     * Cette méthode vérifie que l'utilisateur est agent commercial, empêche le
     * paiement si la facture est déjà réglée, crée l'enregistrement de paiement,
     * met à jour le stock des produits vendus, puis envoie des notifications.
     */
    public function enregistrerPaiement(Request $request, Facture $facture): RedirectResponse
    {
        if (!auth()->user()->isAgentCommercial()) {
            abort(403);
        }

        if ($facture->estPayee()) {
            return back()->with('error', 'Cette facture est déjà payée.');
        }

        $validated = $request->validate([
            'montant' => 'required|numeric|min:0.01',
            'mode'    => 'required|in:especes,mobile_money,virement',
        ]);

        Paiement::create([
            'montant'       => $validated['montant'],
            'date_paiement' => now()->toDateString(),
            'mode'          => $validated['mode'],
            'idfacture'     => $facture->idfacture,
        ]);

        // Mise à jour du stock (sortie) après paiement confirmé
        foreach ($facture->commande->lignes as $ligne) {
            $stock = $ligne->produit->stock;
            if ($stock) {
                $stock->enregistrerMouvement('sortie', $ligne->quantite, 'Vente commande #'.$facture->idcommande);

                // Notification rupture de stock si seuil atteint
                if ($stock->estEnRupture()) {
                    NotificationInterne::envoyerAuRole(
                        'gestionnaire_stock',
                        '⚠️ Rupture de stock',
                        "Le produit \"{$ligne->produit->nom}\" est en dessous du seuil d'alerte ({$stock->quantite_disponible} {$ligne->produit->unite} restant).",
                        'bi-exclamation-triangle',
                        'danger',
                        route('stock.index')
                    );
                }
            }
        }

        // Notification paiement reçu
        NotificationInterne::envoyerAuRole(
            'agent_commercial',
            '💰 Paiement reçu',
            "Facture #{$facture->idfacture} payée — " .
            number_format($validated['montant'], 0, ',', ' ') . ' FCFA (' . ucfirst(str_replace('_', ' ', $validated['mode'])) . ').',
            'bi-cash-coin',
            'success',
            route('factures.show', $facture)
        );

        return redirect()->route('factures.show', $facture)
            ->with('success', 'Paiement enregistré et stock mis à jour.');
    }

    /**
     * Affiche la facture au format imprimable.
     *
     * Cette vue prépare la présentation d'une facture destinée à l'impression
     * ou à l'export PDF.
     */
    public function imprimer(Facture $facture): View
    {
        $facture->load(['commande.client', 'commande.lignes.produit', 'paiement']);
        return view('factures.imprimer', compact('facture'));
    }

    /**
     * Export CSV des factures.
     */
    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="factures.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['idfacture', 'idcommande', 'date_facture', 'montant_total', 'etat_paiement']);
            foreach (Facture::with('paiement')->cursor() as $f) {
                $etat = $f->paiement ? 'payee' : 'non_payee';
                fputcsv($handle, [$f->idfacture, $f->idcommande, $f->date_facture, $f->montant_total, $etat]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
