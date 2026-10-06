<?php

/**
 * Fichier : app/Http/Controllers/ProduitController.php
 * Description : composant du projet Cricroc.
 */

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    /**
     * Affiche la liste des produits.
     *
     * Cette méthode charge le stock de chaque produit, trie par type puis par
     * nom, et renvoie une pagination pour l'affichage du catalogue.
     */
    public function index(): View
    {
        $produits = Produit::with('stock')->orderBy('type')->orderBy('nom')->paginate(20);
        return view('produits.index', compact('produits'));
    }

    /**
     * Affiche les détails d'un produit.
     */
    public function show(Produit $produit): View
    {
        return view('produits.show', compact('produit'));
    }

    /**
     * Affiche le formulaire de création d'un produit.
     */
    public function create(): View
    {
        return view('produits.create');
    }

    /**
     * Valide et enregistre un nouveau produit.
     *
     * Un produit est créé avec son type (matière première, emballage ou produit fini),
     * son prix unitaire et son unité de mesure. Un stock initial est également créé.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom'           => 'required|string|max:255',
            'type'          => 'required|in:matiere_premiere,produit_fini,emballage',
            'prix_unitaire' => 'required|numeric|min:0',
            'unite'         => 'required|string|max:50',
            'stock_initial' => 'nullable|numeric|min:0',
            'seuil_alerte'  => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $produit = Produit::create([
                'nom'           => $validated['nom'],
                'type'          => $validated['type'],
                'prix_unitaire' => $validated['prix_unitaire'],
                'unite'         => $validated['unite'],
            ]);

            Stock::create([
                'idproduit'           => $produit->idproduit,
                'quantite_disponible' => $validated['stock_initial'] ?? 0,
                'seuil_alerte'        => $validated['seuil_alerte'] ?? 10,
            ]);
        });

        return redirect()->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Affiche le formulaire d'édition pour un produit existant.
     */
    public function edit(Produit $produit): View
    {
        return view('produits.edit', compact('produit'));
    }

    /**
     * Met à jour les informations d'un produit.
     *
     * Seuls le nom, le type, le prix et l'unité peuvent être modifiés ici.
     */
    public function update(Request $request, Produit $produit): RedirectResponse
    {
        $validated = $request->validate([
            'nom'           => 'required|string|max:255',
            'type'          => 'required|in:matiere_premiere,produit_fini,emballage',
            'prix_unitaire' => 'required|numeric|min:0',
            'unite'         => 'required|string|max:50',
        ]);

        $produit->update($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit mis à jour.');
    }

    /**
     * Supprime un produit de la base de données.
     *
     * La suppression est définitive et n'est pas bloquée par le stock.
     */
    public function destroy(Produit $produit): RedirectResponse
    {
        $produit->delete();
        return redirect()->route('produits.index')
            ->with('success', 'Produit supprimé.');
    }

    /**
     * Export CSV des produits.
     */
    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="produits.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['idproduit', 'nom', 'type', 'prix_unitaire', 'unite']);
            foreach (Produit::cursor() as $p) {
                fputcsv($handle, [$p->idproduit, $p->nom, $p->type, $p->prix_unitaire, $p->unite]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
