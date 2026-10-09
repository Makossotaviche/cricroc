<?php

/**
 * Fichier : database/seeders/DatabaseSeeder.php
 * Description : composant du projet Cricroc.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Stock;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Utilisateurs ──────────────────────────────────────────────────
        $users = [
            ['name' => 'Administrateur',        'email' => 'admin@cricroc.cg',               'role' => 'admin'],
            ['name' => 'Administrateur',        'email' => 'makossotaviche@gmail.com',        'role' => 'admin'],
            ['name' => 'Agent Commercial',       'email' => 'commercial@cricroc.cg',   'role' => 'agent_commercial'],
            ['name' => 'Gestionnaire de Stock',  'email' => 'stock@cricroc.cg',        'role' => 'gestionnaire_stock'],
            ['name' => 'Resp. Production',       'email' => 'production@cricroc.cg',   'role' => 'responsable_production'],
            ['name' => 'Livreur Jean',           'email' => 'livreur@cricroc.cg',      'role' => 'livreur'],
        ];

        foreach ($users as $u) {
            User::firstOrCreate(['email' => $u['email']], [
                'name'     => $u['name'],
                'password' => Hash::make('password'),
                'role'     => $u['role'],
            ]);
        }

        // ── Produits & Stocks ─────────────────────────────────────────────
        $produits = [
            // Matières premières : ingrédients et consommables utilisés pour fabriquer
            // les produits finis. Le carton de fromage est bien une matière première,
            // pas un emballage.
            ['nom' => 'Sacs de Semoule',     'type' => 'matiere_premiere', 'prix_unitaire' => 5000,  'unite' => 'sac',    'qte' => 100, 'seuil' => 20],
            ['nom' => 'Sacs de Maïs',        'type' => 'matiere_premiere', 'prix_unitaire' => 4500,  'unite' => 'sac',    'qte' => 80,  'seuil' => 30],
            ['nom' => 'Bidons d\'Huile',     'type' => 'matiere_premiere', 'prix_unitaire' => 8000,  'unite' => 'bidon',  'qte' => 50,  'seuil' => 20],
            ['nom' => 'Sacs de Sucre',       'type' => 'matiere_premiere', 'prix_unitaire' => 6000,  'unite' => 'sac',    'qte' => 40,  'seuil' => 10],
            ['nom' => 'Cartons de Formage',  'type' => 'matiere_premiere','prix_unitaire' => 500,   'unite' => 'carton', 'qte' => 300, 'seuil' => 50],

            // Emballages : matériaux de conditionnement. J'ajoute ici les sachets
            // balots pour que ces références soient bien dans la catégorie emballage.
            ['nom' => 'Rouleaux d\'Emballage','type'=> 'emballage',        'prix_unitaire' => 2000,  'unite' => 'rouleau','qte' => 200, 'seuil' => 30],
            ['nom' => 'Sachets Paquets',     'type' => 'emballage',        'prix_unitaire' => 100,   'unite' => 'sachet', 'qte' => 5000,'seuil' => 500],
            ['nom' => 'Sachets balots',      'type' => 'emballage',        'prix_unitaire' => 100,   'unite' => 'sachet', 'qte' => 5000,'seuil' => 500],

            // Produits finis : articles vendables fabriqués à partir des matières
            // premières et emballages.
            ['nom' => 'Chips Cric Croc Fromage (50f)',    'type' => 'produit_fini','prix_unitaire' => 50,   'unite' => 'ballot', 'qte' => 1000,'seuil' => 100],
            ['nom' => 'Chips Cric Croc Fromage (100f)',   'type' => 'produit_fini','prix_unitaire' => 100,  'unite' => 'ballot', 'qte' => 800, 'seuil' => 100],
            ['nom' => 'Chips Cric Croc Cacahuète (50f)',  'type' => 'produit_fini','prix_unitaire' => 50,   'unite' => 'ballot', 'qte' => 1000,'seuil' => 100],
            ['nom' => 'Popcorn Cric Croc Salé (100f)',   'type' => 'produit_fini','prix_unitaire' => 100,  'unite' => 'ballot', 'qte' => 600, 'seuil' => 80],
            ['nom' => 'Popcorn Cric Croc Caramel (100f)', 'type' => 'produit_fini','prix_unitaire' => 100,  'unite' => 'ballot', 'qte' => 600, 'seuil' => 80],
        ];

        foreach ($produits as $p) {
            // Crée le produit si nécessaire ou récupère l'enregistrement existant.
            // On utilise le couple nom/type pour éviter de dupliquer un produit déjà
            // défini dans la même catégorie.
            $produit = Produit::firstOrCreate(
                ['nom' => $p['nom'], 'type' => $p['type']],
                ['prix_unitaire' => $p['prix_unitaire'], 'unite' => $p['unite']]
            );

            if ($p['type'] === 'produit_fini' && $produit->unite !== 'ballot') {
                $produit->update(['unite' => 'ballot']);
            }

            // Initialise le stock du produit. Le seuil d'alerte permet de suivre
            // les produits qui doivent être réapprovisionnés.
            Stock::firstOrCreate(
                ['idproduit' => $produit->idproduit],
                ['quantite_disponible' => $p['qte'], 'seuil_alerte' => $p['seuil']]
            );
        }

        // ── Clients ───────────────────────────────────────────────────────
        $clients = [
            ['nom' => 'Supermarché Total',    'contact' => '06 100 0001', 'adresse' => 'Centre-ville, Pointe-Noire'],
            ['nom' => 'Boutique Chez Maman',  'contact' => '06 200 0002', 'adresse' => 'Loandjili, Pointe-Noire'],
            ['nom' => 'Épicerie du Port',     'contact' => '06 300 0003', 'adresse' => 'Port, Pointe-Noire'],
            ['nom' => 'Jean-Pierre MOUKALA',  'contact' => '06 400 0004', 'adresse' => 'Mvou-Mvou, Pointe-Noire'],
        ];

        foreach ($clients as $c) {
            Client::firstOrCreate(['nom' => $c['nom']], $c);
        }

        // ── Données de démonstration ──────────────────────────────────
        $this->call(DemoDataSeeder::class);
    }
}
