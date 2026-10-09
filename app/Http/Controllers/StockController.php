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
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(): View
    {
        $types = [
            'matiere_premiere' => ['label' => 'Matières premières', 'icon' => 'bi-droplet-half', 'color' => 'warning'],
            'produit_fini' => ['label' => 'Produits finis', 'icon' => 'bi-box-seam', 'color' => 'success'],
            'emballage' => ['label' => 'Emballages', 'icon' => 'bi-inboxes', 'color' => 'primary'],
        ];

        $categories = [];
        foreach ($types as $type => $details) {
            $categories[] = array_merge($details, [
                'stocks' => Stock::with('produit')
                    ->whereHas('produit', fn ($query) => $query->where('type', $type))
                    ->orderBy('quantite_disponible')
                    ->paginate(20, ['*'], 'page_' . $type),
            ]);
        }

        $totalStocks = Stock::count();

        $ruptures = Stock::with('produit')
            ->whereColumn('quantite_disponible', '<=', 'seuil_alerte')
            ->count();

        return view('stock.index', compact('categories', 'totalStocks', 'ruptures'));
    }

    public function mouvements(Stock $stock): View
    {
        $mouvements = $stock->mouvements()->latest()->paginate(20);
        return view('stock.mouvements', compact('stock', 'mouvements'));
    }

    public function entree(Request $request, Stock $stock): RedirectResponse
    {
        $validated = $request->validate([
            'quantite' => 'required|integer|min:1',
            'motif'    => 'nullable|string|max:255',
        ]);

        $stock->enregistrerMouvement('entree', $validated['quantite'], $validated['motif'] ?? 'Approvisionnement');

        return back()->with('success', 'Entrée de stock enregistrée.');
    }

    public function sortie(Request $request, Stock $stock): RedirectResponse
    {
        $validated = $request->validate([
            'quantite' => 'required|integer|min:1',
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
            'seuil_alerte' => 'required|integer|min:0',
        ]);

        $stock->update($validated);

        return back()->with('success', 'Seuil d\'alerte mis à jour.');
    }
    
    public function inventaire(Request $request, Stock $stock): RedirectResponse
    {
        $validated = $request->validate([
            'quantite_physique' => 'required|integer|min:0',
            'motif' => 'required|string|max:255',
        ]);

        $resultat = DB::transaction(function () use ($stock, $validated) {
            $stockVerrouille = Stock::whereKey($stock->getKey())->lockForUpdate()->firstOrFail();
            $ecart = (int) $validated['quantite_physique'] - (int) $stockVerrouille->quantite_disponible;

            if ($ecart === 0) {
                return 0;
            }

            $type = $ecart > 0 ? 'entree' : 'sortie';
            $sens = $ecart > 0 ? '+' : '';
            $motif = 'Ajustement inventaire physique (' . $sens . $ecart . ') : ' . $validated['motif'];
            $stockVerrouille->enregistrerMouvement($type, abs($ecart), $motif);

            return $ecart;
        });

        if ($resultat === 0) {
            return back()->with('success', 'Inventaire enregistré : aucune différence à ajuster.');
        }

        return back()->with('success', 'Inventaire enregistré et ajustement justifié appliqué.');
    }

    /**
     * Export du stock au format Excel.
     */
    public function exportExcel()
    {
        $stocks = DB::table('stocks as s')
            ->leftJoin('produits as p', 'p.idproduit', '=', 's.idproduit')
            ->select('s.idstock', 's.idproduit', 'p.nom as produit', 'p.type', 'p.unite', 's.quantite_disponible', 's.seuil_alerte')
            ->orderBy('p.type')
            ->orderBy('p.nom')
            ->cursor();

        $rows = (function () use ($stocks) {
            foreach ($stocks as $stock) {
                $unite = $stock->type === 'produit_fini' ? 'ballot' : $stock->unite;
                yield [$stock->idstock, $stock->idproduit, $stock->produit, $stock->quantite_disponible, $stock->seuil_alerte, $unite];
            }
        })();

        return response(\App\Support\XlsxWriter::make(
            ['ID stock', 'ID produit', 'Produit', 'Quantite disponible', 'Seuil alerte', 'Unite'],
            $rows,
            'Stocks'
        ), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="stock.xlsx"',
        ]);
    }

    public function exportMouvementsExcel(Request $request)
    {
        $validated = $request->validate([
            'idstock' => 'nullable|integer|exists:stocks,idstock',
        ]);

        $idstock = $validated['idstock'] ?? null;
        $filename = $idstock ? 'historique-stock-' . $idstock . '.xlsx' : 'historique-stocks.xlsx';
        $mouvements = DB::table('mouvements_stock as m')
            ->join('stocks as s', 's.idstock', '=', 'm.idstock')
            ->join('produits as p', 'p.idproduit', '=', 's.idproduit')
            ->select('m.created_at', 'm.idstock', 'p.nom as produit', 'm.type', 'm.quantite', 'm.motif')
            ->when($idstock, fn ($query) => $query->where('m.idstock', $idstock))
            ->orderBy('m.created_at')
            ->cursor();

        $rows = (function () use ($mouvements) {
            foreach ($mouvements as $mouvement) {
                yield [$mouvement->created_at, $mouvement->idstock, $mouvement->produit, $mouvement->type, $mouvement->quantite, $mouvement->motif];
            }
        })();

        return response(\App\Support\XlsxWriter::make(
            ['Date', 'ID stock', 'Produit', 'Type', 'Quantite', 'Motif'],
            $rows,
            'Historique'
        ), 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

}
