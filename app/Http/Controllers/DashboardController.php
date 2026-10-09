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
use App\Models\Paiement;
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
        $debut = now()->startOfMonth()->subMonths(11);
        $fin = now()->endOfMonth();
        $mois = collect(range(0, 11))->map(fn ($decalage) => $debut->copy()->addMonths($decalage));
        $cles = $mois->map(fn ($date) => $date->format('Y-m'))->all();

        $factures = Facture::query()
            ->selectRaw("DATE_FORMAT(date_facture, '%Y-%m') as mois, SUM(montant_total) as total")
            ->whereBetween('date_facture', [$debut->toDateString(), $fin->toDateString()])
            ->groupBy('mois')->pluck('total', 'mois');

        $paiements = Paiement::query()
            ->selectRaw("DATE_FORMAT(date_paiement, '%Y-%m') as mois, SUM(montant) as total")
            ->whereBetween('date_paiement', [$debut->toDateString(), $fin->toDateString()])
            ->groupBy('mois')->pluck('total', 'mois');

        $commandes = Commande::query()
            ->selectRaw("DATE_FORMAT(date_commande, '%Y-%m') as mois, COUNT(*) as total")
            ->where('statut', 'validee')
            ->whereBetween('date_commande', [$debut->toDateString(), $fin->toDateString()])
            ->groupBy('mois')->pluck('total', 'mois');

        $modesPaiement = Paiement::query()
            ->selectRaw('mode, SUM(montant) as total')
            ->whereYear('date_paiement', now()->year)
            ->groupBy('mode')->pluck('total', 'mode');

        return response()->json([
            'labels' => $mois->map(fn ($date) => $date->format('m/Y'))->values(),
            'facture' => collect($cles)->map(fn ($cle) => (float) ($factures[$cle] ?? 0))->values(),
            'encaisse' => collect($cles)->map(fn ($cle) => (float) ($paiements[$cle] ?? 0))->values(),
            'commandes' => collect($cles)->map(fn ($cle) => (int) ($commandes[$cle] ?? 0))->values(),
            'modes' => [
                'labels' => $modesPaiement->keys()->map(fn ($mode) => match ($mode) {
                    'especes' => 'Espèces',
                    'mobile_money' => 'Mobile Money',
                    'virement' => 'Virement',
                    default => ucfirst(str_replace('_', ' ', $mode)),
                })->values(),
                'data' => $modesPaiement->values()->map(fn ($total) => (float) $total),
            ],
            'annee' => now()->year,
        ]);
    }
}
