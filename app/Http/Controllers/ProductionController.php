<?php

namespace App\Http\Controllers;

use App\Events\ProductionCreated;
use App\Models\ConsommationProduction;
use App\Models\Produit;
use App\Models\Production;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductionController extends Controller
{
    public function index(): View
    {
        $productions = Production::with('produit')->latest('date_production')->paginate(20);
        return view('production.index', compact('productions'));
    }

    public function create(): View
    {
        $produitsFinis = Produit::where('type', 'produit_fini')->with('stock')->orderBy('nom')->get();
        $matieresPremiere = Produit::whereIn('type', ['matiere_premiere', 'emballage'])->with('stock')->orderBy('nom')->get();
        return view('production.create', compact('produitsFinis', 'matieresPremiere'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'productions' => 'required|array|min:1',
            'productions.*.idproduit' => 'required|distinct|exists:produits,idproduit',
            'productions.*.quantite_produite' => 'required|integer|min:1',
            'productions.*.consommations' => 'nullable|array',
            'productions.*.consommations.*.idproduit' => 'required|exists:produits,idproduit',
            'productions.*.consommations.*.quantite_consommee' => 'required|integer|min:1',
        ]);

        try {
            $productions = DB::transaction(function () use ($validated) {
                $created = [];

                foreach ($validated['productions'] as $ligne) {
                    $produitFini = Produit::findOrFail($ligne['idproduit']);
                    if ($produitFini->type !== 'produit_fini') {
                        throw new \DomainException($produitFini->nom . " n'est pas un produit fini.");
                    }

                    $consommations = $ligne['consommations'] ?? [];
                    foreach ($consommations as $item) {
                        $matiere = Produit::findOrFail($item['idproduit']);
                        if (!in_array($matiere->type, ['matiere_premiere', 'emballage'], true)) {
                            throw new \DomainException($matiere->nom . " n'est pas une matiere premiere ou un emballage.");
                        }
                    }

                    $production = Production::create([
                        'date_production' => now()->toDateString(),
                        'quantite_produite' => $ligne['quantite_produite'],
                        'idproduit' => $ligne['idproduit'],
                    ]);

                    foreach ($consommations as $item) {
                        ConsommationProduction::create([
                            'idproduction' => $production->idproduction,
                            'idproduit' => $item['idproduit'],
                            'quantite_consommee' => $item['quantite_consommee'],
                        ]);
                    }

                    $stockProduitFini = Stock::where('idproduit', $ligne['idproduit'])->lockForUpdate()->first();
                    if (!$stockProduitFini) {
                        $stockProduitFini = Stock::create([
                            'idproduit' => $ligne['idproduit'],
                            'quantite_disponible' => 0,
                            'seuil_alerte' => 0,
                        ]);
                    }
                    $stockProduitFini->enregistrerMouvement(
                        'entree', (float) $ligne['quantite_produite'], 'Production #' . $production->idproduction
                    );

                    $created[] = $production;
                }

                return $created;
            });
        } catch (\DomainException $e) {
            return back()->withInput()->withErrors(['productions' => $e->getMessage()]);
        }

        foreach ($productions as $production) {
            event(new ProductionCreated($production));
        }

        return redirect()->route('production.index')->with(
            'success', count($productions) . ' production(s) enregistree(s) et stocks mis a jour.'
        );
    }

    public function show(Production $production): View
    {
        $production->load(['produit', 'consommations.produit']);
        return view('production.show', compact('production'));
    }

    public function exportCsv()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['idproduction', 'date_production', 'produit', 'quantite_produite']);
            foreach (Production::with('produit')->orderBy('date_production')->cursor() as $production) {
                fputcsv($handle, [$production->idproduction, $production->date_production->format('Y-m-d'), $production->produit?->nom, $production->quantite_produite]);
            }
            fclose($handle);
        }, 'productions.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
