<?php

/**
 * Fichier : database/seeders/DemoDataSeeder.php
 * Description : composant du projet Cricroc.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Livraison;
use App\Models\Production;
use App\Models\ConsommationProduction;
use App\Models\User;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère les produits déjà créés par DatabaseSeeder. Ces références
        // permettent de simuler des productions et des commandes de démonstration.
        $chips_paquet = Produit::where('nom', 'like', '%Paquet%')->first();
        $chips_ballot = Produit::where('nom', 'like', '%Ballot%')->first();
        $semoule      = Produit::where('nom', 'like', '%Semoule%')->first();
        $mais         = Produit::where('nom', 'like', '%Maïs%')->first();
        $huile        = Produit::where('nom', 'like', '%Huile%')->first();
        $emballage    = Produit::where('nom', 'like', '%Emballage%')->first();
        $sachet       = Produit::where('nom', 'like', '%Sachets%')->first();

        // On arrête si les produits finis nécessaires n'existent pas encore.
        if (!$chips_paquet || !$chips_ballot) return;

        $agent    = User::where('role', 'agent_commercial')->first();
        $livreur  = User::where('role', 'livreur')->first();
        $clients  = Client::all();

        if ($clients->isEmpty()) return;

        // ── Productions de démonstration ────────────────────────────
        $prods = [
            ['produit' => $chips_paquet, 'qte' => 500, 'date' => now()->subDays(10)],
            ['produit' => $chips_paquet, 'qte' => 300, 'date' => now()->subDays(5)],
            ['produit' => $chips_ballot, 'qte' => 20,  'date' => now()->subDays(3)],
        ];

        foreach ($prods as $p) {
            if (!$p['produit']) continue;

            $production = Production::create([
                'date_production'   => $p['date']->toDateString(),
                'quantite_produite' => $p['qte'],
                'idproduit'         => $p['produit']->idproduit,
            ]);

            // Consommations fictives
            if ($semoule) {
                ConsommationProduction::create([
                    'idproduction'       => $production->idproduction,
                    'idproduit'          => $semoule->idproduit,
                    'quantite_consommee' => $p['qte'] * 0.05,
                ]);
            }
            if ($huile) {
                ConsommationProduction::create([
                    'idproduction'       => $production->idproduction,
                    'idproduit'          => $huile->idproduit,
                    'quantite_consommee' => $p['qte'] * 0.02,
                ]);
            }
            if ($sachet) {
                ConsommationProduction::create([
                    'idproduction'       => $production->idproduction,
                    'idproduit'          => $sachet->idproduit,
                    'quantite_consommee' => $p['qte'],
                ]);
            }
        }

        // ── Commandes de démonstration ───────────────────────────────
        $scenarios = [
            // Commande validée + facture payée + livraison livrée
            [
                'client'  => $clients[0],
                'statut'  => 'validee',
                'lignes'  => [
                    ['produit' => $chips_paquet, 'qte' => 50, 'prix' => 500],
                    ['produit' => $chips_ballot, 'qte' => 5,  'prix' => 10000],
                ],
                'paiement'  => true,
                'livraison' => 'livree',
                'date'      => now()->subDays(8),
            ],
            // Commande validée + facture non payée + livraison en cours
            [
                'client'  => $clients[1] ?? $clients[0],
                'statut'  => 'validee',
                'lignes'  => [
                    ['produit' => $chips_paquet, 'qte' => 100, 'prix' => 500],
                ],
                'paiement'  => false,
                'livraison' => 'en_cours',
                'date'      => now()->subDays(3),
            ],
            // Commande en attente
            [
                'client'  => $clients[2] ?? $clients[0],
                'statut'  => 'en_attente',
                'lignes'  => [
                    ['produit' => $chips_ballot, 'qte' => 10, 'prix' => 10000],
                ],
                'paiement'  => false,
                'livraison' => null,
                'date'      => now()->subDay(),
            ],
            // Commande rejetée
            [
                'client'  => $clients[3] ?? $clients[0],
                'statut'  => 'rejetee',
                'lignes'  => [
                    ['produit' => $chips_paquet, 'qte' => 200, 'prix' => 500],
                ],
                'paiement'  => false,
                'livraison' => null,
                'date'      => now()->subDays(6),
            ],
            // Commande d'aujourd'hui en attente
            [
                'client'  => $clients[0],
                'statut'  => 'en_attente',
                'lignes'  => [
                    ['produit' => $chips_paquet, 'qte' => 30, 'prix' => 500],
                    ['produit' => $chips_ballot, 'qte' => 2,  'prix' => 10000],
                ],
                'paiement'  => false,
                'livraison' => null,
                'date'      => now(),
            ],
        ];

        foreach ($scenarios as $s) {
            DB::transaction(function () use ($s, $livreur) {
                // Calcul montant total
                $total = collect($s['lignes'])->sum(fn($l) => $l['qte'] * $l['prix']);

                $commande = Commande::create([
                    'date_commande' => $s['date']->toDateString(),
                    'idclient'      => $s['client']->idclient,
                    'statut'        => $s['statut'],
                    'montant_total' => $total,
                ]);

                foreach ($s['lignes'] as $l) {
                    if (!$l['produit']) return;
                    LigneCommande::create([
                        'idcommande'    => $commande->idcommande,
                        'idproduit'     => $l['produit']->idproduit,
                        'quantite'      => $l['qte'],
                        'prix_unitaire' => $l['prix'],
                    ]);
                }

                // Facture si commande validée ou rejetée
                if (in_array($s['statut'], ['validee', 'rejetee'])) {
                    $facture = Facture::create([
                        'date_facture'  => $s['date']->addHour()->toDateString(),
                        'montant_total' => $total,
                        'idcommande'    => $commande->idcommande,
                    ]);

                    if ($s['paiement']) {
                        Paiement::create([
                            'montant'       => $total,
                            'date_paiement' => $s['date']->addHours(3)->toDateString(),
                            'mode'          => 'mobile_money',
                            'idfacture'     => $facture->idfacture,
                        ]);
                    }

                    if ($s['livraison']) {
                        Livraison::create([
                            'idcommande'     => $commande->idcommande,
                            'idlivreur'      => $livreur?->id,
                            'statut'         => $s['livraison'],
                            'date_livraison' => $s['livraison'] === 'livree'
                                ? $s['date']->addDays(2)->toDateString()
                                : null,
                        ]);
                    }
                }
            });
        }
    }
}
