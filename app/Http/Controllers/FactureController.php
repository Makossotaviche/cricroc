<?php

/**
 * Fichier : app/Http/Controllers/FactureController.php
 * Description : composant du projet Cricroc.
 */

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Paiement;
use App\Models\NotificationInterne;
use App\Models\ParametreFacturation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

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
        $factures = Facture::with(['commande.client'])->withSum('paiements', 'montant')
            ->latest()
            ->paginate(15);
        $tauxTva = ParametreFacturation::tauxTvaActuel();

        return view('factures.index', compact('factures', 'tauxTva'));
    }

    /**
     * Affiche le détail d'une facture.
     *
     * On charge la commande, les lignes de commande, les produits et le paiement.
     */
    public function show(Facture $facture): View
    {
        $facture->load(['commande.client', 'commande.lignes.produit', 'paiements']);
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

        try {
            $factureDevenuePayee = DB::transaction(function () use ($facture, $validated) {
                $facture = Facture::whereKey($facture->idfacture)->lockForUpdate()->firstOrFail();
                $dejaPaye = (float) $facture->paiements()->sum('montant');
                $reste = max(0, (float) $facture->montant_total - $dejaPaye);

                if ($reste <= 0) {
                    throw new \DomainException('Cette facture est déjà payée.');
                }
                if ((float) $validated['montant'] > $reste) {
                    throw new \DomainException('Le montant dépasse le reste à payer (' . number_format($reste, 0, ',', ' ') . ' FCFA).');
                }

                Paiement::create([
                    'montant' => $validated['montant'],
                    'date_paiement' => now()->toDateString(),
                    'mode' => $validated['mode'],
                    'idfacture' => $facture->idfacture,
                ]);

                $totalPaye = $dejaPaye + (float) $validated['montant'];
                if ($dejaPaye <= 0) {
                    $facture->load('commande.lignes.produit.stock');
                    foreach ($facture->commande->lignes as $ligne) {
                        $stock = $ligne->produit->stock;
                        if ($stock) {
                            $stock->enregistrerMouvement('sortie', $ligne->quantite, 'Vente commande #' . $facture->idcommande);
                            if ($stock->estEnRupture()) {
                                NotificationInterne::envoyerAuRole(
                                    'gestionnaire_stock',
                                    'Alerte rupture de stock',
                                    "Le produit {$ligne->produit->nom} est sous le seuil d'alerte ({$stock->quantite_disponible} {$ligne->produit->unite} restant).",
                                    'bi-exclamation-triangle',
                                    'danger',
                                    route('stock.index')
                                );
                            }
                        }
                    }
                }

                return $totalPaye >= (float) $facture->montant_total;
            });
        } catch (\DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
        NotificationInterne::envoyerAuRole(
            'agent_commercial',
            'Paiement reçu',
            "Paiement enregistré pour la facture #{$facture->idfacture} — " .
            number_format($validated['montant'], 0, ',', ' ') . ' FCFA (' . ucfirst(str_replace('_', ' ', $validated['mode'])) . ').',
            'bi-cash-coin',
            'success',
            route('factures.show', $facture)
        );

        return redirect()->route('factures.show', $facture)
            ->with('success', $factureDevenuePayee
                ? 'Paiement enregistré. Facture réglée et stock mis à jour.'
                : 'Paiement partiel enregistré dans l’historique de la facture.');
    }

    /**
     * Affiche la facture au format imprimable.
     *
     * Cette vue prépare la présentation d'une facture destinée à l'impression
     * ou à l'export PDF.
     */
    public function imprimer(Facture $facture): View
    {
        $facture->load(['commande.client', 'commande.lignes.produit', 'paiements']);
        return view('factures.imprimer', compact('facture'));
    }

    public function pdf(Facture $facture)
    {
        $facture->load(['commande.client', 'commande.lignes.produit', 'paiements']);

        return Pdf::loadView('factures.pdf', compact('facture'))
            ->setPaper('a4')
            ->download('facture-' . Str::slug($facture->numero_facture) . '.pdf');
    }

    public function updateTva(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'taux_tva' => 'required|numeric|min:0|max:100',
        ]);

        ParametreFacturation::enregistrerTauxTva((float) $validated['taux_tva']);

        return redirect()->route('factures.index')->with('success', 'Taux de TVA mis à jour.');
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
            fputcsv($handle, ['numero_facture', 'idcommande', 'date_facture', 'montant_ht', 'taux_tva', 'montant_tva', 'montant_ttc', 'etat_paiement']);
            foreach (Facture::withSum('paiements', 'montant')->cursor() as $f) {
                $paye = (float) ($f->paiements_sum_montant ?? 0);
                $etat = $paye >= (float) $f->montant_total ? 'payee' : ($paye > 0 ? 'partiellement_payee' : 'non_payee');
                fputcsv($handle, [$f->numero_facture, $f->idcommande, $f->date_facture, $f->montant_ht, $f->taux_tva, $f->montant_tva, $f->montant_total, $etat]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}


