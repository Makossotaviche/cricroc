<?php

/**
 * Fichier : app/Listeners/SendStockNotification.php
 * Description : Listener qui crée une notification quand un stock est ajouté.
 */

namespace App\Listeners;

use App\Events\StockAdded;
use App\Models\NotificationInterne;
use App\Models\User;

class SendStockNotification
{
    /**
     * Envoie une notification à tous les gestionnaires de stock.
     */
    public function handle(StockAdded $event): void
    {
        $nomProduit = $event->stock->produit?->nom ?? 'Produit inconnu';
        
        $titre = '📦 Nouveau stock reçu';
        $message = "Entrée de {$event->quantite} unité(s) pour {$nomProduit}. Motif : {$event->motif}";
        
        // Envoyer à tous les gestionnaires de stock et admins
        NotificationInterne::envoyerAuRole(
            'gestionnaire_stock',
            $titre,
            $message,
            'bi-box-seam',
            'primary',
            route('stock.index')
        );
    }
}
