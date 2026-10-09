<?php

/**
 * Fichier : app/Http/Controllers/CommandeController.php
 * Description : composant du projet Cricroc.
 */

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Client;
use App\Models\Produit;
use App\Models\LigneCommande;
use App\Models\NotificationInterne;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    /**
     * Affiche la liste des commandes.
     *
     * Le contrôleur charge les informations liées au client, à la facture et à la
     * livraison pour présenter un aperçu complet des commandes.
     */
    public function index(): View
    {
        $commandes = Commande::with(['client', 'facture', 'livraison'])
            ->latest()
            ->paginate(15);

        return view('commandes.index', compact('commandes'));
    }

    /**
     * Affiche le formulaire de création d'une commande.
     *
     * La vue reçoit la liste des clients et des produits finis disponibles.
     */
    public function create(): View
    {
        $clients  = Client::orderBy('nom')->get();
        $produits = Produit::where('type', 'produit_fini')
            ->with('stock')
            ->get();

        return view('commandes.create', compact('clients', 'produits'));
    }

    /**
     * Enregistre une nouvelle commande et calcule son montant total.
     *
     * Les lignes de commande sont créées en base, puis le total est recalculé
     * pour remplacer la valeur initiale à zéro.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'idclient'             => 'required|exists:clients,idclient',
            'lignes'               => 'required|array|min:1',
            'lignes.*.idproduit'   => 'required|exists:produits,idproduit',
            'lignes.*.quantite'    => 'required|integer|min:1',
            'lignes.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $commande = Commande::create([
                'date_commande' => now()->toDateString(),
                'idclient'      => $request->idclient,
                'statut'        => 'en_attente',
                'montant_total' => 0,
            ]);

            foreach ($request->lignes as $ligne) {
                $commande->lignes()->create([
                    'idproduit'    => $ligne['idproduit'],
                    'quantite'     => $ligne['quantite'],
                    'prix_unitaire'=> $ligne['prix_unitaire'],
                ]);
            }

            $commande->recalculerTotal();
        });

        // ── Notification nouvelle commande ──
        $commande = Commande::with('client')->latest()->first();
        NotificationInterne::envoyerAuRole(
            'agent_commercial',
            '🛒 Nouvelle commande',
            "Commande #{$commande->idcommande} de {$commande->client->nom} — " .
            number_format($commande->montant_total, 0, ',', ' ') . ' FCFA',
            'bi-cart-plus',
            'primary',
            route('commandes.show', $commande)
        );

        return redirect()->route('commandes.index')
            ->with('success', 'Commande enregistrée avec succès.');
    }

    /**
     * Affiche les détails d'une commande.
     *
     * La méthode charge les lignes avec les produits, la facture et le paiement,
     * ainsi que la livraison et le livreur éventuel.
     */
    public function show(Commande $commande): View
    {
        $commande->load(['client', 'lignes.produit', 'facture.paiements', 'livraison.livreur']);
        return view('commandes.show', compact('commande'));
    }

    /**
     * Valide une commande en attente.
     *
     * La validation crée la facture, notifie le commercial et le gestionnaire de stock.
     */
    public function valider(Commande $commande): RedirectResponse
    {
        if ($commande->statut !== 'en_attente') {
            return back()->with('error', 'Cette commande ne peut plus être validée.');
        }

        $facture = $commande->valider();

        // ── Notifications commande validée ──
        NotificationInterne::envoyerAuRole(
            'agent_commercial',
            '✅ Commande validée',
            "Commande #{$commande->idcommande} validée. Facture #{$facture->idfacture} générée.",
            'bi-check-circle',
            'success',
            route('factures.show', $facture)
        );
        NotificationInterne::envoyerAuRole(
            'gestionnaire_stock',
            '📦 Commande à préparer',
            "Commande #{$commande->idcommande} validée — préparation du stock requise.",
            'bi-boxes',
            'warning',
            route('commandes.show', $commande)
        );

        return redirect()->route('commandes.show', $commande)
            ->with('success', "Commande validée. Facture #{$facture->idfacture} générée automatiquement.");
    }

    /**
     * Rejette une commande en attente.
     *
     * La commande est marquée comme rejetée et le commercial est notifié.
     */
    public function rejeter(Commande $commande): RedirectResponse
    {
        if ($commande->statut !== 'en_attente') {
            return back()->with('error', 'Cette commande ne peut plus être rejetée.');
        }

        $commande->update(['statut' => 'rejetee']);

        // ── Notification commande rejetée ──
        NotificationInterne::envoyerAuRole(
            'agent_commercial',
            '❌ Commande rejetée',
            "Commande #{$commande->idcommande} de {$commande->client->nom} a été rejetée.",
            'bi-x-circle',
            'danger',
            route('commandes.show', $commande)
        );

        return redirect()->route('commandes.index')
            ->with('success', 'Commande rejetée.');
    }
}
