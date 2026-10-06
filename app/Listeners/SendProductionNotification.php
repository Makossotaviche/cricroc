<?php

/**
 * Fichier : app/Listeners/SendProductionNotification.php
 * Description : Listener qui crée une notification quand une production est enregistrée.
 */

namespace App\Listeners;

use App\Events\ProductionCreated;
use App\Models\NotificationInterne;
use App\Models\Production;

class SendProductionNotification
{
    /**
     * Envoie une notification à tous les responsables de production.
     */
    public function handle(ProductionCreated $event): void
    {
        $production = $event->production;
        $nomProduit = $production->produit?->nom ?? 'Produit inconnu';

        $quantiteAujourdHui = Production::whereDate('date_production', now())
            ->where('idproduit', $production->idproduit)
            ->sum('quantite_produite');

        $titre = '🏭 Nouvelle production enregistrée';
        $message = "Aujourd'hui, {$quantiteAujourdHui} unité(s) de {$nomProduit} ont été produites.";

        NotificationInterne::envoyerAuRole(
            'gestionnaire_production',
            $titre,
            $message,
            'bi-hammer',
            'success',
            route('production.index')
        );
    }
}
