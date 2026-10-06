<?php

/**
 * Fichier : app/Http/Controllers/StockController.php
 * Description : composant du projet Cricroc.
 */

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        $stocks = Stock::with('produit')
            ->orderBy('quantite_disponible')
            ->paginate(20);

        $ruptures = Stock::with('produit')
            ->whereColumn('quantite_disponible', '<=', 'seuil_alerte')
            ->count();

        return view('stock.index', compact('stocks', 'ruptures'));
    }

    public function mouvements(Stock $stock): View
    {
        $mouvements = $stock->mouvements()->latest()->paginate(20);
        return view('stock.mouvements', compact('stock', 'mouvements'));
    }

    public function entree(Request $request, Stock $stock): RedirectResponse
    {
        $validated = $request->validate([
            'quantite' => 'required|numeric|min:0.01',
            'motif'    => 'nullable|string|max:255',
        ]);

        $stock->enregistrerMouvement('entree', $validated['quantite'], $validated['motif'] ?? 'Approvisionnement');

        return back()->with('success', 'Entrée de stock enregistrée.');
    }

    public function sortie(Request $request, Stock $stock): RedirectResponse
    {
        $validated = $request->validate([
            'quantite' => 'required|numeric|min:0.01',
            'motif'    => 'nullable|string|max:255',
        ]);

        try {
            $stock->enregistrerMouvement('sortie', $validated['quantite'], $validated['motif'] ?? 'Sortie manuelle');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Sortie de stock enregistrée.');
    }

    public function updateSeuil(Request $request, Stock $stock): RedirectResponse
    {
        $validated = $request->validate([
            'seuil_alerte' => 'required|numeric|min:0',
        ]);

        $stock->update($validated);

        return back()->with('success', 'Seuil d\'alerte mis à jour.');
    }
    
    /**
     * Export CSV du stock.
     */
    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="stock.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['idstock', 'idproduit', 'produit_nom', 'quantite', 'seuil']);
            foreach (Stock::with('produit')->cursor() as $s) {
                fputcsv($handle, [$s->idstock, $s->idproduit, optional($s->produit)->nom, $s->quantite, $s->seuil]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
