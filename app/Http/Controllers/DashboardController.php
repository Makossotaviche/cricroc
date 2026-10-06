<?php

/**
 * Fichier : app/Http/Controllers/DashboardController.php
 * Description : composant du projet Cricroc.
 */

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Stock;
use App\Models\Facture;
use App\Models\Production;
use App\Models\Livraison;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord principal.
     *
     * La méthode calcule des statistiques globales (clients, commandes,
     * chiffre d'affaires mensuel, ruptures de stock, livraisons en cours) et
     * fournit les listes récentes nécessaires pour le tableau de bord.
     */
    public function index(): View
    {
        // Stats globales
        $stats = [
            'clients'           => Client::count(),
            'commandes_today'   => Commande::whereDate('created_at', today())->count(),
            'commandes_attente' => Commande::where('statut', 'en_attente')->count(),
            'ca_mois'           => Facture::whereMonth('date_facture', now()->month)
                                         ->whereYear('date_facture', now()->year)
                                         ->sum('montant_total'),
            'ruptures'          => Stock::whereColumn('quantite_disponible', '<=', 'seuil_alerte')->count(),
            'livraisons_cours'  => Livraison::where('statut', 'en_cours')->count(),
        ];

        // Dernières commandes
        $dernieresCommandes = Commande::with('client')
            ->latest()
            ->take(5)
            ->get();

        // Stocks en alerte
        $stocksAlerte = Stock::with('produit')
            ->whereColumn('quantite_disponible', '<=', 'seuil_alerte')
            ->get();

        // Productions récentes
        $dernieresProductions = Production::with('produit')
            ->latest('date_production')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'dernieresCommandes',
            'stocksAlerte',
            'dernieresProductions'
        ));
    }

    /**
     * Retourne les données de tendance des ventes (derniers 12 mois).
     */
    public function trendSales(Request $request)
    {
        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('Y-m');
            $montant = Facture::whereYear('date_facture', $date->year)
                ->whereMonth('date_facture', $date->month)
                ->sum('montant_total');
            $data[] = (float) $montant;
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
}
